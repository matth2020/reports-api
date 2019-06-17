<?php

namespace App\Http\Controllers\Injection;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\LockableController;
use App\Models\DosingPlanDetails;
use App\Models\TreatPlanDetails;
use App\Models\TreatmentPlan;
use App\Models\Prescription;
use App\Models\Injection;
use App\Models\InjAdjust;
use App\Models\Compound;
use App\Models\Xisprefs;
use Carbon\Carbon;

class InjectionBaseController extends LockableController
{
    /**
     * Get reactions names from the system configuration
     * @return object containing two properties (local, systemic), each
     * of which is an array of reaction names.
     */
    protected static function getReactionNames()
    {
        $ReactionStrings = Xisprefs::firstOrFail();
        $LocalNames = explode(',', $ReactionStrings->reactNamesL);
        $SystemicNames = explode(',', $ReactionStrings->reactNamesS);
        $Reactions = app()->make('stdClass');
        $Reactions->systemic = $SystemicNames;
        $Reactions->local = $LocalNames;

        return $Reactions;
    }

    protected static function getAdjustmentsDue($PrescriptionId)
    {
        $adjusts = InjAdjust::where('prescription_id', $PrescriptionId)
            ->where('deleted', 'F')
            ->orderByRaw('STR_TO_DATE(date, "%c/%e/%Y") asc')
            ->get();

        return $adjusts;
    }

    protected static function getRemainingTP($TreatmentPlanId, $NextStep)
    {
        // make sure that if we have a next step, it isn't greater than the plan
        $MaxPlanStep = TreatPlanDetails::where('treatment_plan_id', $TreatmentPlanId)->max('step');

        if (!is_null($MaxPlanStep) && $MaxPlanStep != -1 && $MaxPlanStep < $NextStep) {
            // we have a valid max step and our next step is greater than that so fix it
            // aka...repeat top step
            $NextStep = $MaxPlanStep;
        }

        // we actually need the step from the last injection in the plan as well
        // because thats how we know the min/max days for the next step hence the
        // max(...below)
        $Plan = TreatPlanDetails::where('treatment_plan_id', $TreatmentPlanId)
            ->where('step', '>=', max($NextStep - 1, 0)) //get all of the plan that is greater or equal to the last step
            ->get()->toArray();

        $finalStepIdx = sizeof($Plan) - 1;
        if ($finalStepIdx >= 0) {
            //This is the case that should occur 99% of the time
            $finalStep = $Plan[$finalStepIdx];
        } else {
            //Old tps may not have steps which will result in step index
            //of -1. This "should" always be fixed before the api is
            //installed but in case its not, return the min step for
            //safety
            $finalStep = TreatPlanDetails::where('treatment_plan_id', $TreatmentPlanId)
                ->orderBy('dilution', 'desc')
                ->orderBy('dose', 'asc')
                ->first();
        }

        while (sizeof($Plan) < 4) {
            //Ensure the plan is at least 5 steps long. If not, we need to add the last step multiple times.
            array_push($Plan, $finalStep);
        }
        return $Plan;
    }

    protected static function findLastInjection($PrescriptionID, $PatientID)
    {
        try {
            return Injection::whereHas('compound.prescription', function ($query) use ($PrescriptionID, $PatientID) {
                $query->where('patient_id', $PatientID)
                    ->where('prescription_id', $PrescriptionID);
            })->where('deleted', 'F')
                ->orderBy('date', 'desc')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    protected static function getLastDetails($LastInjection, $prescriptionId)
    {
        if ($LastInjection instanceof Injection) {
            //if injection object
            $DoseRuleNamesId = $LastInjection->compound->prescription->doseRuleNames_id;
            $LastInjectionDate = Carbon::createFromFormat('Y-m-d H:i:s', $LastInjection->date);
        } elseif ($LastInjection instanceof injAdjust) {
            //if injAdjust object
            $DoseRuleNamesId = $LastInjection->prescription->doseRuleNames_id;
            $LastInjectionDate = Carbon::createFromFormat('Y-m-d', $LastInjection->date);
        } else {
            $DoseRuleNamesId = Prescription::find($prescriptionId)->doseRuleNames_id;
            $LastInjectionDate = Carbon::now(); //if there was no last injection use date now so
            // days late calculations will work out to 0 and we don't need a bunch of if/else
        }
        $lastDetails = app()->make('stdClass');
        $lastDetails->DoseRuleNamesId = $DoseRuleNamesId;
        $lastDetails->LastInjectionDate = $LastInjectionDate;
        return $lastDetails;
    }

    protected static function getLastReactions($LastInjection)
    {
        $lastReactions = app()->make('stdClass');
        if (!is_null($LastInjection) && $LastInjection->sysreaction != InjectionBaseController::getReactionNames()->systemic[0]) {
            //there was a systemic
            $lastReactions->ReactType = 'S';
            $lastReactions->ReactVal = $LastInjection->sysreaction;
        } elseif (!is_null($LastInjection)) {
            //there was a local (or the local was F)
            $lastReactions->ReactType = 'L';
            $lastReactions->ReactVal = $LastInjection->reaction;
        }
        return $lastReactions;
    }

    protected static function calculateDoseRulesAdjust($LastInjection, $LastPlanStep, $prescriptionId)
    {
        $lastDetails = self::getLastDetails($LastInjection, $prescriptionId);

        //calculate days late based on last injection
        //NOTE: this is slightly strange in the case when the tp has changed
        //between this injection and the last because we are still calculating
        //days late based on the last injection...
        $DaysSinceLast = $lastDetails->LastInjectionDate->startOfDay()->diffInDays(Carbon::now()->startOfDay());
        $DaysLate = isset($LastPlanStep) ? $DaysSinceLast - $LastPlanStep->maxInterval : 0;
        $DaysLate = $DaysLate >= 0 ? $DaysLate : 0;

        //Check for systemic reaction
        $DoseAdjustDetails = self::getLastReactions($LastInjection);

        try {
            if (!is_null($LastInjection)) {
                //NOTE: again, dose rules coming from last injections TP
                //apply dose rules to figure out how many steps back to adjust
                $StepsAdjusted = DosingPlanDetails::where('doseRuleNames_id', $lastDetails->DoseRuleNamesId)
                    ->where('reactType', $DoseAdjustDetails->ReactType) //dose rules for this reaction type
                    ->where('reactVal', $DoseAdjustDetails->ReactVal) //and this reaction value
                    ->where('start', '<=', $DaysLate) //where start is less than or equal to days late
                    ->where(function ($query) use ($DaysLate) {
                        $query->where('end', 'inf')  //and end is greater than days late (or inf)
                            ->orWhere('end', '>=', $DaysLate);
                    })->orderBy('start', 'asc') //order by days late increasing
                    ->pluck('delta') //return only the first delta.
                    ->first();
            } else {
                //If there was no last injection, there is no dose rules row to find so throw the exception
                //so that the catch returns good values
                throw new ModelNotFoundException();
            }
        } catch (ModelNotFoundException $e) {
            if (!is_null($lastDetails->DoseRuleNamesId) && $lastDetails->DoseRuleNamesId != -1 && !is_null($LastInjection)) {
                return null;
            } else {
                $StepsAdjusted = 1; //default to moving 1 step forward
                $DaysLate = 0;
            }
        }

        if (!is_numeric($StepsAdjusted)) {
            //Its probably Inf so we should Ask but even if its something else non numeric, we don't
            //know how to handle it and need to ask anyway.
            return null;
        }

        $DoseAdjustDetails->StepsAdjusted = $StepsAdjusted;
        $DoseAdjustDetails->DaysLate = $DaysLate;
        $DoseAdjustDetails->NextStep = !is_null($LastPlanStep) ? max(0, ($LastPlanStep->step + $StepsAdjusted)) : 0;

        return $DoseAdjustDetails;
    }

    protected static function findTpStep($LastInjection)
    {
        // Try to find treatment plan step by injection.tpstep_id
        if ($LastInjection->tpdetails_id != -1 &&
            !is_null($LastInjection->tpdails_id) &&
            $LastInjection->tpdetails_id != ''
        ) {
            try {
                return TreatPlanDetails::findOrFail($LastInjection->tpdetails_id);
            } catch (ModelNotFoundException $e) {
                // just continue on via remaining methods
            }
        }

        // If we still dont have the TP step, try finding it the hard way
        $TreatmentPlanId = !is_null($LastInjection->treatment_plan_id) ? $LastInjection->treatment_plan_id : $LastInjection->compound->prescription->treatment_plan_id;

        return self::findTpIdByDoseDilution($TreatmentPlanId, $LastInjection->compound->dilution, $LastInjection->dose);
    }

    protected static function findTpIdByDoseDilution($TreatmentPlanId, $dilution, $dose)
    {
        try {
            return TreatPlanDetails::where(function ($query) use ($dilution, $dose) {
                $query->where(function ($innerQuery) use ($dilution, $dose) {
                    $innerQuery->where('dilution', $dilution)
                        ->where('dose', '<=', $dose);
                })
                ->orWhere(function ($innerQuery) use ($dilution) {
                    $innerQuery->where('dilution', '>', (int) $dilution);
                });
            })->where('treatment_plan_id', $TreatmentPlanId)
            ->orderBy('dilution', 'asc')
            ->orderBy('dose', 'desc')
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // continue on
        }
        // if we still couldn't find it... return null and we can give an error message later
        return null;
    }

    protected function addFuturePlan($Chart, $TreatmentPlanId, $SingleStep = false)
    {
        $Prescription = Prescription::find($Chart->prescription_id);
        $Adjustments = $this::getAdjustmentsDue($Chart->prescription_id);
        if ($Adjustments->count() === 0) {
            //if there are no adjustments, the future plan picks up where the last injection left off
            $LastInjection = $this::findLastInjection($Chart->prescription_id, $this->RequestOptions->patient_id);
        } else {
            //if there are pending adjustments, the future plan picks up after the latest adjust.
            $LastInjection = $Adjustments[$Adjustments->count() - 1];
        }

        $TreatmentPlanStep = null;
        if ($LastInjection instanceof Injection) {
            // If LastInjection was an actual historical injection, find the step
            $TreatmentPlanStep = $this::findTpStep($LastInjection);
        }
        // see if the last tp_step was for the same tp. If the last injection was an
        // adjust (not from a tp step) or the tp has changed, we need to try to find
        // the equivilant step in the current tp by matching dose/dilution
        if ($LastInjection instanceof injAdjust || (!is_null($TreatmentPlanStep) && $TreatmentPlanStep->treatment_plan_id !== $Prescription->treatment_plan_id)) {
            // else if it was an adjust, find the step the hard way.
            $TreatmentPlanId = $Prescription->treatment_plan_id;
            $dilution = $LastInjection instanceof injAdjust ? $LastInjection->dilution : $LastInjection->compound->dilution;
            $TreatmentPlanStep = $this::findTpIdByDoseDilution($TreatmentPlanId, $dilution, $LastInjection->dose);
        }

        $DoseAdjustDetails = $this::calculateDoseRulesAdjust($LastInjection, $TreatmentPlanStep, $Chart->prescription_id);
        
        if (!is_null($DoseAdjustDetails)) {
            //If we found a treatment plan step, the next step is +1, if we didn't find a step it starts at 0
            $NextStep = isset($TreatmentPlanStep) ? $TreatmentPlanStep->step + 1 : 0;
            // if we found a dose adjustment, use it as the next step otherwise stick with what we had
            $NextStep = isset($DoseAdjustDetails) ? $DoseAdjustDetails->NextStep : $NextStep;
            // finally... if the currently selected next step is of the same dilution
            // but from a new bottle, we need to factor in the tp "steps_back"
            $NextStep = $this::newBottleStepsBackAdjust($NextStep, $LastInjection);

            //Query the remaining plan
            $Plan = $this::getRemainingTP($TreatmentPlanId, $NextStep);

            $Chart = $this::getRemainingPlan($DoseAdjustDetails, $Plan, $Chart, $LastInjection, $SingleStep);
        } else {
            if ($SingleStep) {
                //if singleStep then we are being called from injection due to cal only one point but
                //since we couldn't figure out the point, return null
                $NoPoint = app()->make('stdClass');
                $NoPoint->DoseAdjustDetails = null;
                return $NoPoint;
            }
            $InjPoint = app()->make('stdClass');
            $InjPoint->date = Carbon::now()->toDateTimeString();
            $InjPoint->dose = 'ASK';
            $InjPoint->dilution = 0;
            $InjPoint->note = 'Unable to calculate next dose. Please adjust manually.';
            $InjPoint->type = 'error';
            $Dilution = $this::newChartDilution(0, 'BLK', $InjPoint);
            $Chart->dilution[0] = $Dilution;
        }
        if (!is_null($TreatmentPlanStep)) {
            $Chart->last_tp_step = $TreatmentPlanStep;
        }
        return $Chart;
    }

    private static function newBottleStepsBackAdjust($ProposedStep, $LastInjection)
    {
        if (is_null($LastInjection) || $LastInjection instanceof injAdjust) {
            // If its the first injection or if they have manually adjusted we
            // need to recommend step 1 or what they told us to
            return $ProposedStep;
        }
        try {
            $LastCompound = $LastInjection->compound;
            $RX = $LastCompound->prescription;
            $TreatmentPlan = $RX->treatmentPlan;
            $StepDetail = TreatPlanDetails::where('treatment_plan_id', $TreatmentPlan->treatment_plan_id)
                ->where('step', $ProposedStep)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            //if we couldn't find the required info, just do the proposed step
            return $ProposedStep;
        }

        if ($LastCompound->dilution === $StepDetail->dilution) {
            // dilutions are the same, see if there is an active bottle of the new
            // dilution that has no injections... if so it will need steps back.
            try {
                $NextCompound = Compound::where('rx_id', $LastCompound->rx_id)
                    ->where('dilution', $StepDetail->dilution)
                    ->where('active', 'T')
                    ->whereDoesntHave('injections')
                    ->firstOrFail();


                $StepsBack = $TreatmentPlan->maint_steps_back;
                if (is_null($StepsBack) || $StepsBack === 0) {
                    return $ProposedStep;
                } else {
                    // steps back is a neg number so adding to proposed is stubtracting
                    // steps. Choosing the max between that and 0 ensures we never go
                    // off the end of the plan.
                    return max(($ProposedStep + $StepsBack) - 1, 0);
                }
            } catch (ModelNotFoundException $e) {
                //if no compound was active that had no injections, we should still
                //be predicting the original proposal.
                return $ProposedStep;
            }
        } else {
            // different dilution so steps back doesn't play a role
            return $ProposedStep;
        }
    }

    private static function getRemainingPlan($DoseAdjustDetails, $Plan, $Chart, $LastInjection, $SingleStep = false)
    {
        if ($LastInjection instanceof Injection) {
            $lastInjDate = isset($LastInjection->date) ? Carbon::createFromFormat('Y-m-d H:i:s', $LastInjection->date) : null;
        } else {
            $lastInjDate = isset($LastInjection->date) ? Carbon::createFromFormat('Y-m-d', $LastInjection->date) : null;
        }
        
        // This is pretty confusing... the $Plan includes all of the tp from
        // (and including) the last injection step forward. This is because
        // we have to figure out when this steps injection is due based on
        // the min/max interval of the previous step. The confusion comes into
        // play because, this means we need to skip the first step in $Plan since
        // it was the last injection...BUT... if no injections have ever been given
        // and we have returned the whole plan then we DO need to inject the first
        // step. So below we have to do work to tell the difference between the
        // case when we return the full plan and we DID already inject the first step
        // and the case when we return the full plan and we DID not already inject
        // the first step. This isn't as simple as seeing if $LastInjection is null
        // because dose rules may have also stepped us back into redoing step 0 so
        // we have to use $DoseAdjustDetails->NextStep to determine if step0 is the
        // next step or the last step.
        $doStep0Skip = $DoseAdjustDetails->NextStep != 0;
        foreach ($Plan as $Index => $Step) {
            if ($Index === 0 && $doStep0Skip) {
                // do nothing here... this step is the step for the previous injection,
                // we need it below where we do $Plan[$Index -1] but we don't want to
                // return it as any part of the remaing plan
                continue;
            }
            //Tps are hard... the min_max interval in the row(step) are how long to
            //go to the NEXT step, not how long to get to this step.. That means,
            //in all of the calculations below, we actually care about the min/max
            //interval of the previous step.
            if ($Index === 0 && !$doStep0Skip) {
                //if we are on index0 and not skipping it, we don't have a
                //a previous step to refer to so we have to pick a super wide
                //range for min/max interval
                $minInterval = 0;
                $maxInterval = 73000;
                //maxInterval used to be 10000000 but that resulted in a year in
                //the future of 29397 which made things explode. 73000 is a
                //randomly chosen large number that provides a reasonable future
                //date (20 years). If you are restarting a new tp for an existing
                //prescription 20 years after your last injection... you are late.
            } else {
                $minInterval = $Plan[$Index - 1]['minInterval'];
                $maxInterval = $Plan[$Index - 1]['maxInterval'];
            }
            //Convert each step into an injection point
            $AveInterval = ($minInterval + $maxInterval) / 2;
            //If its the first time though, the date is now, otherwise its last predicted date plus the interval.
            $injectionDue = !is_null($lastInjDate) ? $lastInjDate->copy()->addDays($AveInterval) : Carbon::now();

            $injectionDue = $injectionDue->lte(Carbon::now()) ? Carbon::now() : $injectionDue;
            if ($SingleStep) {
                //if singleStep then we are being called from injectionDue to calc only the next point
                //so just return the tp step and doseAdjust details
                $nextStep = app()->make('stdClass');
                $nextStep->tpStep = $Step;
                $nextStep->DoseAdjustDetails = $DoseAdjustDetails;
                $nextStep->date = !is_null($lastInjDate) ? $lastInjDate->toDateTimeString() : Carbon::now()->toDateTimeString();
                if (!is_null($lastInjDate)) {
                    $nextStep->min_date = $lastInjDate->startOfDay()->addDays($minInterval)->toDateTimeString();

                    $nextStep->max_date = $lastInjDate->endOfDay()->addDays($maxInterval)->toDateTimeString();
                }
                return $nextStep;
            }
            //Only the first (index 0) step can be a dose adjust so don't base dose adjust details
            //after that.
            $DoseAdjustDetails = $Index == 0 ? $DoseAdjustDetails : null;
            $Chart = self::addStepToChart($Step, $Chart, $injectionDue, $DoseAdjustDetails);
            $lastInjDate = $injectionDue;
        }

        return $Chart;
    }

    private static function newChartDilution($dilution, $Color, $InjPoint)
    {
        $Dilution = app()->make('stdClass');
        $Dilution->dilution = $dilution;
        $Dilution->color = $Color;
        $Dilution->data = [$InjPoint];
        return $Dilution;
    }

    private static function addStepToChart($Step, $Chart, $InjDate, $DoseAdjustDetails = null)
    {
        $InjPoint = app()->make('stdClass');
        $InjPoint->date = $InjDate->toDateTimeString();
        $InjPoint->dose = $Step['dose'];
        $InjPoint->type = 'Predicted';
        if (!is_null($DoseAdjustDetails) && $DoseAdjustDetails->StepsAdjusted != 1) {
            $InjPoint->type = 'Dose rules adjustment';
            $InjPoint->note = 'Adjusted '.$DoseAdjustDetails->StepsAdjusted.' due to ' . $DoseAdjustDetails->DaysLate . ' days late and ('.$DoseAdjustDetails->ReactType.')'.$DoseAdjustDetails->ReactVal.' reaction.';
        }

        //Now that we have build the injection point, we need to add it to the correct place
        //in the chart object
        return self::addChartInjPoint($Chart, $Step['dilution'], $Step['color'], $InjPoint);
    }

    private static function addChartInjPoint($Chart, $Dilution, $Color, $InjPoint)
    {
        if (isset($Chart->dilution[$Dilution])) {
            array_push($Chart->dilution[$Dilution]->data, $InjPoint);
        } else {
            // Dilution doesn't exist in the array yet so add it
            $DilutionObject = self::newChartDilution($Dilution, $Color, $InjPoint);
            $Chart->dilution[$Dilution] = $DilutionObject;
        }
        return $Chart;
    }
}

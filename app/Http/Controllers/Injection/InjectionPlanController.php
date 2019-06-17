<?php

namespace App\Http\Controllers\Injection;

use App\Http\Controllers\Injection\InjectionBaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\GeneralPurpose\SimpleInjection;
use Illuminate\Validation\Validator;
use App\Models\TreatPlanDetails;
use function DeepCopy\deep_copy;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\Injection;
use App\Models\Compound;
use App\Models\Patient;
use Carbon\Carbon;

class InjectionPlanController extends InjectionBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"InjectionPlan"},
     *     path="/patient/{patient_id}/injection_plan",
     *     summary="Returns a list of all InjectionPlans that apply to a given patient.",
     *     description="",
     *     operationId="api.InjectionPlan.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose injectionplans are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionPlan object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
    *     @SWG\Parameter(
    *        name="offset",
    *        in="query",
    *        description="Offset past first match. (Requires a limit value)",
    *        required=false,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *         name="limit",
    *         in="query",
    *         description="Maximum number of results to return.",
    *         required=false,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */
    public function index(request $request)
    {
        return $this->handleRequest($request, new Patient);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"InjectionPlan"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/injection_plan",
     *     summary="Returns a single InjectionPlan in the system identified by {id}.",
     *     description="",
     *     operationId="api.InjectionPlan.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose injectionplans are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="prescription_id",
     *        in="path",
     *        description="Id the prescription to generate a plan for.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionPlan object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */
    public function getRxInjectionPlan(request $request)
    {
        return $this->handleRequest($request, new Patient);
    }

    protected function queryWith($Query)
    {
        return $Query->with([
            'prescriptions' => function ($query) {
                if (!isset($this->RequestOptions->prescription_id)) {
                    // get all struckthrough = F rxs
                    $query->where('strikethrough', 'F')
                        ->has('compounds'); // only rxs that have compound rows
                } else {
                    // same as above but also limits results to one RX
                    $query->where('prescription_id', $this->RequestOptions->prescription_id)
                        ->where('strikethrough', 'F')
                        ->has('compounds');
                }
            },
            'prescriptions.compounds' => function ($query) {
                // sort by dilution
                $query->orderBy('dilution', 'desc');
            },
            'prescriptions.compounds.injections' => function ($query) {
                // Only get deleted=F injections
                $query->where('deleted', 'F')
                    ->orderBy('date', 'desc');
            },
            'prescriptions.treatmentPlan', // get rx treatment plans
            'prescriptions.dosingPlan', //get rx dosing plans
        ]);
    }

    protected function queryWhere($Query)
    {
        return $Query->where('patient_id', $this->RequestOptions->patient_id);
    }

    protected function queryModifier($Query)
    {
        return $Query->where('archived', 'F');
    }

    protected function finishAndFilter($Object)
    {
        // Because we used the patient model as the base for this, it will be returned as an array of matching
        // patients with only one element so we need to address $Object[0] to strip the outer array from the
        // result before returning it
        if (!is_null($Object)) {
            $Object = $this->finalize($Object[0]);
            if (!is_null($Object)) {
                if (method_exists($Object, 'filterProperties')) {
                    $Object = $Object->filterProperties($Object, $this->RequestOptions->filter);
                }
            }
        }

        return response()->json($Object, 200);
    }

    /**
     * called by the finalize customCollection method on each element of a
     * collection
     *
     * In this case, the finalize method takes a collection of injections
     * and converts them into an array of prescriptions. Each prescription
     * is an array of dilutions/vials and each dilution/vial is an array
     * of points ready to be easily graphed.
     *
     * @param  InjectionPlan $InjectionPlan object returned from the database
     * @param  request $request API request used to build filter.
     * @return InjectionPlan object
     */
    protected function finalize($Patient)
    {
        $Charts = array();

        foreach ($Patient->prescriptions as $Prescription) {
            //create an array using rx_id as the index containing the organized injection history for the rx
            $Chart = $this->newPrescription($Prescription);

            //Next pass the charts array through addAdjustments to add future adjustments
            $Chart = $this->addAdjustments($Chart);

            //Finally add the predicted future treatment plan
            $Chart = $this->addFuturePlanPoints($Chart, $Prescription);

            //now add the new chart to the array of charts
            array_push($Charts, $Chart);
        }
        return $Charts;
    }

    private function addFuturePlanPoints($Chart, $Prescription)
    {
        //only calculate future plan if a TP is assigned
        if ($Prescription->treatment_plan_id != -1 && !is_null($Prescription->treatment_plan_id)) {
            //Next pass the charts array through addFuturePlan to add the rest of the treatment plan.
            $FuturePlan = deep_copy($this->addFuturePlan($Chart, $Prescription->treatment_plan_id));
            unset($Chart->dilution);
            foreach ($FuturePlan->dilution as $FutureDilution) {
                $thisSeries = app()->make('stdClass');
                $thisSeries->points = [];
                $thisSeries->dilution = $FutureDilution->dilution;
                $thisSeries->color = $FutureDilution->color;
                foreach ($FutureDilution->data as $Injection) {
                    array_push(
                        $thisSeries->points,
                        [
                                'x' => $Injection->date,
                                'y' => $Injection->dose,
                                'type' => $Injection->type
                            ]
                    );
                }
                if (sizeOf($thisSeries->points) > 0) {
                    array_push($Chart->series, $thisSeries);
                }
            }
        }
        return $Chart;
    }

    /**
     * Create a new chart to begin storing injections of a distinct rx
     * @param  Injection $Injection An injection from the db
     * @return A data object representing a new RX which includes the
     *                injection point provided.
     */
    private function newPrescription(Prescription $Prescription)
    {
        // this prescription objectj isnt in the array yet so add it.
        $RxChart = app()->make('stdClass');
        $RxChart->dilution = array();
        
        $RxChart->prescription_id = $Prescription->prescription_id;
        $RxChart->prescription_number = (int) $Prescription->prescription_num;

        $RxChart->name = $Prescription->compounds[0]->name;

        $series = [];
        $thisSeries = app()->make('stdClass');
        $thisSeries->points = [];
        $thisSeries->dilution = null;
        $Sys = $this->RequestOptions->reactions->systemic[0];
        $Loc = $this->RequestOptions->reactions->local[0];
        foreach ($Prescription->injections->sortBy('date') as $Injection) {
            $type = 'Plan';
            $reaction = null;
            if ($Injection->reaction != $Loc && $Injection->sysreaction != $Sys) {
                $type = 'Reaction';
                $reaction = $Injection->sysreaction != $Sys ? $Injection->sysreaction : $Injection->reaction;
            } elseif (!is_null($Injection->inj_adjust_id)) {
                $type = 'Manual adjustment';
            } elseif ($Injection->is_rule_adjust === 'T') {
                $type = 'Rules based adjustment';
            } else {
                $type = 'Plan';
            }
            if ($Injection->compound->dilution === $thisSeries->dilution || is_null($thisSeries->dilution)) {
                // if dilution hasn't changed since last injection, its part of the
                // same chart line so keep adding to the array
                array_push($thisSeries->points, [
                'x' => $Injection->date,
                'y' => $Injection->dose,
                'type' => $type,
                'notes' => $Injection->notespatient,
                'reaction' => $reaction,
                'user' => $Injection->user->displayname
                ]);
                $thisSeries->dilution = $Injection->compound->dilution; //overwrites with same
                $thisSeries->color = $Injection->compound->color; //overwrites with same
            } else {
                // since dilution is different, the last series is complete so push it and
                // start a new one.
                if (sizeOf($thisSeries->points) > 0) {
                    array_push($series, $thisSeries);
                }
                $thisSeries = app()->make('stdClass');
                $thisSeries->points = [[
                'x' => $Injection->date,
                'y' => $Injection->dose,
                'type' => $type,
                'notes' => $Injection->notespatient,
                'reaction' => $reaction,
                'user' => $Injection->user->displayname
                ]];
                $thisSeries->dilution = $Injection->compound->dilution; //overwrites with same
                $thisSeries->color = $Injection->compound->color; //overwrites with same
            }
        }
        //push the final series in progress
        if (sizeOf($thisSeries->points) > 0) {
            array_push($series, $thisSeries);
        }
        $RxChart->series = $series;

        return $RxChart;
    }

    private function addAdjustments($Chart)
    {
        $Adjusts = $this::getAdjustmentsDue($Chart->prescription_id);

        foreach ($Adjusts as $Adjust) {
            $thisSeries = app()->make('stdClass');
            $NextDate = Carbon::createFromFormat('Y-m-d', $Adjust->date);
            $thisSeries->points = [[
                'x' => $NextDate->gte(Carbon::now()) ? $NextDate->toDateTimeString() : Carbon::now()->toDateTimeString(),
                'y' => number_format($Adjust->dose, 3),
                'type' => 'Pending manual adjustment',
                'user' => $Adjust->adjby,
                'note' => $Adjust->reason
            ]];
            $thisSeries->dilution = $Adjust->dilution;
            if (sizeOf($thisSeries->points) > 0) {
                array_push($Chart->series, $thisSeries);
            }
        }
        // return the updated chart object
        return $Chart;
    }

    /**
     * Init function preforms actions that should happen at the start of every
     * request to this endpoint no mater the type.
     * @param  request $request    The API request
     * @param      $patient_id ID of the patient associated with the request
     * @return Init object         A general object containing info generated
     *                             during the init process.
     */
    protected function init()
    {
        $this->RequestOptions->reactions = $this::getReactionNames();
    }
}

<?php

namespace App\Http;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Config\ConfigController;
use App\Models\TreatPlanDetails;
use App\Models\ProviderConfig;
use App\Models\PatientConfig;
use App\Models\DosingPlanSet;
use App\Models\TreatmentPlan;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\PlanStep;
use App\Models\Postpone;
use App\Models\Provider;
use App\Models\Compound;
use App\Models\Xpsprefs;
use App\Models\Patient;
use App\Models\Message;
use App\Models\Extract;
use App\Models\Config;
use App\Models\Vial;
use Log;

class CustomValidator
{
    public function validateGte($attribute, $value, $parameters, $validator)
    {
        // laravels default gte doesn't do a numeric check
        if (!is_numeric($value)) {
            return false;
        }
        return (float)$value >= (float)$parameters[0];
    }
    public function validateNotAllowed($attribute, $value, $parameters, $validator)
    {
        return false;
    }
    public function validateLte($attribute, $value, $parameters, $validator)
    {
        // laravels default lte doesn't do a numeric check
        if (!is_numeric($value)) {
            return false;
        }
        return (float)$value <= (float)$parameters[0];
    }
    public function validateConfigMultiColumnKey($attribute, $value, $parameters, $validator)
    {
        $name = $value;
        $section = $parameters[0];
        //account for mysql defaults if the following werent provided
        $val = isset($parameters[1]) ? $parameters[1] : null;
        $app = isset($parameters[2]) ? $parameters[2] : 'ALL';
        $id = isset($parameters[3]) ? $parameters[3] : 0;

        $Count = Config::where('name', $name)
            ->where('section', $section)
            ->where('value', $val)
            ->where('app', $app)
            ->where('config_id', '<>', $id)
            ->count();

        return $Count == 0;
    }
    public function validateValidPatientLock($attribute, $value, $parameters, $validator)
    {
        if (strtoupper($value) !== 'LOCK') {
            // if this isn't a lock there is no need to check for conflicting locks.
            return true;
        }
        
        $PatientId = $parameters[0];
        $Value = $parameters[1];
        $Count  = PatientConfig::where('name', $value)
            ->where('patient_id', $PatientId)
            ->where('value', $Value)
            ->count();

        return $Count == 0 && strtoupper($value) === 'LOCK';
    }
    public function validateTreatmentPlanStepsZeroBased($attribute, $value, $parameters, $validator)
    {
        // find smallest step_num, verify it is zero

        $smallest = PHP_INT_MAX;

        foreach ($value as $key => $dilLevel) {
            foreach ($dilLevel['steps'] as $stepkey => $step) {
                $smallest = min($smallest, $step['step_number']);
            }
        }

        return $smallest === 0;
    }

    public function validateTreatmentPlanStepsIncrease($attribute, $value, $parameters, $validator)
    {
        $valid = true;

        // sort steps by step_num
        // verify that step_num increases by one

        $allSteps = TreatmentPlan::flattenDetails($value);

        usort($allSteps, array('App\Models\TreatmentPlan', 'cmpStepNum'));

        for ($i = 1; $i < count($allSteps); $i++) {
            if ($allSteps[$i]['step_number'] != $allSteps[$i - 1]['step_number'] + 1) {
                $valid = false;
            }
        }

        return $valid;
    }

    public function validateTreatmentPlanDosesIncrease($attribute, $value, $parameters, $validator)
    {
        // sort steps by step_num and by dose
        // verify that the two arrays are the same

        $allSteps = TreatmentPlan::flattenDetails($value);

        $copyForNumSort = $allSteps;
        $copyForDoseSort = $allSteps;

        usort($copyForNumSort, array('App\Models\TreatmentPlan', 'cmpStepNum'));
        usort($copyForDoseSort, array('App\Models\TreatmentPlan', 'cmpStepDose'));

        return ($copyForNumSort === $copyForDoseSort);
    }

    public function validateValidTpDetails($attribute, $value, $parameters, $validator)
    {
        $valid = true;
        $TpDetailsRow = new TreatPlanDetails();
        $TpStepRow = new PlanStep();
        foreach ($value as $key => $TPbottle) {
            if (!$TpDetailsRow->Validate($TPbottle, null)) {
                $valid = false;
                $Errors = $TpDetailsRow->errors();
                $validator->getMessageBag()->merge($Errors);
            }

            foreach ($TPbottle['steps'] as $key => $TPstep) {
                if (!$TpStepRow->Validate($TPstep, null)) {
                    $valid = false;
                    $Errors = $TpStepRow->errors();
                    $validator->getMessageBag()->merge($Errors);
                }
            }
        }
        return $valid;
    }

    public function validateValidDosingAdjustments($attribute, $value, $parameters, $validator)
    {
        $valid = true;
        foreach ($value as $Adjust) {
            if (!preg_match('/(^ASK$)|(^[-+]?[0-9]{1,2}$)/i', $Adjust)) {
                //if the adjustments aren't ASK and or a +/- two digit number
                //they are invalid
                $valid = false;
            }
            if (!$valid) {
                return false;
            }
        }
        return true;
    }
    public function validateValidDosingSets($attribute, $value, $parameters, $validator)
    {
        $valid = true;
        $DosingPlanSet = new DosingPlanSet();
        foreach ($value as $DPset) {
            if (!$DosingPlanSet->Validate($DPset, null)) {
                $valid = false;
                $Errors = $DosingPlanSet->errors();
                $validator->getMessageBag()->merge($Errors);
            }
        }
        return true;
    }
    public function validateHasAllReactionTypes($attribute, $value, $parameters, $validator)
    {
        //Get valid reaction types
        //figure out the valid reaction values
        $ConfigController = new ConfigController();

        $SearchConfig = new Config();
        $SearchConfig->name = 'reaction_names';
        $SearchConfig->app = 'XIS';
        $SearchConfig->config_id = null;
        $SearchConfig->value = null;

        $fakeRequest = Request::create('/v1/config/_search', 'POST', $SearchConfig->toArray());
        $data = new \Symfony\Component\HttpFoundation\ParameterBag;
        $data->add($SearchConfig->toArray());
        $fakeRequest->setJson($data);

        $result = $ConfigController->searchConfig($fakeRequest);

        $ReactionTypes = $result->getData()[0]->value;
        //see if the plan has the same number of reaction types as the system is using. The -1 accounts for the systemic=F and local=F case which is really just the normal TP with no adjustments so it isn't included in the DP.
        if (count($value) !== (count($ReactionTypes->local) + count($ReactionTypes->systemic) - 1)) {
            return false;
        } else {
            //plan had the correct number of reactions listed so make sure that each of them has the correct name for the system
            foreach ($value as $key => $planSet) {
                //first check local reaction names
                if ($planSet['reaction_type'] === 'LOCAL') {
                    if (!in_array($planSet['reaction_value'], $ReactionTypes->local)) {
                        return false;
                    }
                } else {
                    //next check systemic reactions... remove the first systemic
                    //from the check because it amounts to the -1 in the size check above.
                    if (!in_array($planSet['reaction_value'], array_splice($ReactionTypes->systemic, 1))) {
                        return false;
                    }
                }
            }
        }
        //plan passed all checks so return true
        return true;
    }
    public function validateValidQuestionType($attribute, $value, $parameters, $validator)
    {
        //valid if value is "text" (case insensitive) or contains at least one comma
        if (strtoupper($value) === 'TEXT') {
            return true;
        } elseif (preg_match('/,/', $value)) {
            return true;
        }

        return false;
    }

    public function validateFmd($attribute, $value, $parameters, $validator)
    {
        return preg_match('/^(<\?xml version=\"[0-9.]{3}\" encoding=\"UTF-8\"\?><Fid><Bytes>[a-zA-Z0-9\/+=]*<\/Bytes><Format>[0-9]*<\/Format><Version>[0-9.]{5}<\/Version><\/Fid>(\r\n)?){0,10}$/', $value);
    }

    public function validateBadAnswers($attribute, $value, $parameters, $validator)
    {
        if (is_null($value)) {
            return true; // null is always valid for bad_answer
        } elseif ($parameters[0] === 'text') {
            return false; // null is required for answers of type text
        } else {
            //now we have to make sure every element in bad_answer is in the answer array.
            //the answers array is all of the attached parameters.
            $BadAnswers = explode(',', $value);
            //make sure all good answers are in the set of valid answers
            foreach ($BadAnswers as $Answer) {
                if (!in_array($Answer, $parameters)) {
                    return false;
                }
            }
        }


        return true;
    }

    public function validateValidQuestionAnswer($attribute, $value, $parameters, $validator)
    {
        $BadAnswers = explode(',', $value);

        //make sure all good answers are in the set of valid answers
        foreach ($BadAnswers as $key => $Answer) {
            if (!in_array($Answer, $parameters)) {
                return false;
            }
        }

        return true;
    }

    public function validateValidMultiAnswer($attribute, $value, $parameters, $validator)
    {
        $MultiAnswers = explode(',', $value);
        $AllowableAnswers = $parameters;
        //make sure all answers provided are among the allowable answers
        foreach ($MultiAnswers as $key => $Answer) {
            if (!in_array($Answer, $AllowableAnswers)) {
                return false;
            }
        }

        return true;
    }

    public function validateExtractCSV($attribute, $value, $parameters, $validator)
    {
        $Extracts = explode(',', strtoupper($value));

        foreach ($Extracts as $Index => $Extract_id) {
            try {
                $Extract = new Extract();
                $Extract = Extract::findOrFail($Extract_id);
            } catch (ModelNotFoundException $e) {
                return false;
            }
        }

        return true;
    }

    public function validateOutdatesCSV($attribute, $value, $parameters, $validator)
    {
        $test1 = preg_match('/^[0-9]+(,[0-9]+)*$/', $value);
        $Outdates = explode(',', $value);
        $test2 = (count($Outdates) == 8);

        return $test1 && $test2;
    }

    public function validatePhone($attribute, $value, $parameters, $validator)
    {
        $search = array('(', ')', '-', ' ');
        $replace = array('', '', '', '');
        $result = str_replace($search, $replace, $value);

        return preg_match(
            "/\+\d{1,14}$/",
            $result
        );
    }

    public function validatePV1($attribute, $value, $parameters, $validator)
    {
        return (is_null($value) || $value == '') ? true : preg_match("/^PV1/i", $value);
    }

    public function validatePID($attribute, $value, $parameters, $validator)
    {
        return (is_null($value) || $value == '') ? true : preg_match("/^PID/i", $value);
    }

    public function validateDilution($attribute, $value, $parameters, $validator)
    {
        return in_array($value, [0, 1, 10, 100, 1000, 10000, 100000, 100000, 10000000]);
    }

    public function validateInQuestionnaires($attribute, $value, $parameters, $validator)
    {
        foreach ($value as $key => $RequestedId) {
            try {
                Questionnaire::where('deleted', 'F')->findOrFail($RequestedId);
            } catch (ModelNotFoundException $e) {
                return false;
            }
        }
        return true;
    }

    public function validateInReactions($attribute, $value, $parameters, $validator)
    {
        //figure out the valid reaction values
        $ConfigController = new ConfigController();

        $SearchConfig = new Config();
        $SearchConfig->name = 'reaction_names';
        $SearchConfig->app = 'XIS';
        $SearchConfig->config_id = null;
        $SearchConfig->value = null;

        $fakeRequest = Request::create('/v1/config/_search', 'POST', $SearchConfig->toArray());
        $data = new \Symfony\Component\HttpFoundation\ParameterBag;
        $data->add($SearchConfig->toArray());
        $fakeRequest->setJson($data);

        $result = $ConfigController->searchConfig($fakeRequest);
        
        $ReactionTypes = $result->getData()[0]->value;
        $SysReactions = $ReactionTypes->systemic;
        $ReactionsAny = array_merge($ReactionTypes->local, $SysReactions);

        switch ($parameters[0]) {
            case 'local':
                return in_array($value, $ReactionTypes->local);
            case 'systemic':
                return in_array($value, $ReactionTypes->systemic);
            case 'any':
                return in_array($value, $ReactionsAny);
            default:
                return false;
        }
    }
    public function validateValidPriority($attribute, $value, $parameters, $validator)
    {
        $pref = Xpsprefs::first();
        $Priorities = explode(',', $pref->priority_names);
        return $value <= sizeOf($Priorities);
    }
    public function validateValidVialBarcode($attribute, $value, $parameters, $validator)
    {
        if (!isset($parameters[0])) {
            return null;
        }
        $CompoundId = $parameters[0];
        $result = Vial::where('barcode', $value)->where('compound_id', '<>', $CompoundId)->get()->count() == 0;
        return $result;
    }
    public function validateTpDoseDilution($attribute, $value, $parameters, $validator)
    {
        $dose = $parameters[0];
        $dilution = $parameters[1];
        $prescription_id = $parameters[2];

        // Find the treatment plan associated with the prescription
        try {
            $TreatmentPlanId = Prescription::findOrFail($prescription_id)->treatment_plan_id;
        } catch (ModelNotFoundException $e) {
            return false;
        }
        // Find the minimum dose and dilution pair from the treatment plan
        try {
            $MinStep = TreatPlanDetails::where('treatment_plan_id', $TreatmentPlanId)
                                        ->orderBy('dilution', 'desc')
                                        ->orderBy('dose', 'asc')
                                        ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
        // Find the maximum dose and dilution pair from the treatment plan
        try {
            $MaxStep = TreatPlanDetails::where('treatment_plan_id', $TreatmentPlanId)
                                        ->orderBy('dilution', 'asc')
                                        ->orderBy('dose', 'desc')
                                        ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        //See if dose/dilution pair is greater than minimum pair.
        $GreaterThanMinStep = (($dose >= $MinStep->dose) && ($dilution == $MinStep->dilution)) || ($dilution < $MinStep->dilution);

        //See if dose/dilution pair is less than maximum pair
        $LessThanMaxStep = (($dose <= $MaxStep->dose) && ($dilution == $MaxStep->dilution)) || ($dilution > $MaxStep->dilution);

        return $GreaterThanMinStep && $LessThanMaxStep;
    }

    public function validateTpDilution($attribute, $value, $parameters, $validator)
    {
        $dilution = $parameters[0];
        $prescription_id = $parameters[1];

        // Find the treatment plan associated with the prescription
        try {
            $TreatmentPlanId = Prescription::findOrFail($prescription_id)->treatment_plan_id;
        } catch (ModelNotFoundException $e) {
            return false;
        }
        // Find a minimum dilution step
        try {
            $MinDil = TreatPlanDetails::where('treatment_plan_id', $TreatmentPlanId)
                                        ->orderBy('dilution', 'asc')
                                        ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
        // Find a maximum dilution step
        try {
            $MaxDil = TreatPlanDetails::where('treatment_plan_id', $TreatmentPlanId)
                                        ->orderBy('dilution', 'desc')
                                        ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return $dilution <= $MaxDil->dilution && $dilution >= $MinDil->dilution;
    }
    public function validateRxDilution($attribute, $value, $parameters, $validator)
    {
        try {
            $ProviderConfig = ProviderConfig::findOrFail($parameters[0]);
        } catch (ModelNotFoundException $e) {
            return false;
        }

        if ($ProviderConfig->profileRate == -1) {
            //custom dilutions
            $DilutionsCSV = $ProviderConfig->dilutions10;

            $AvailDilutions = explode(',', $DilutionsCSV);

            foreach ($AvailDilutions as $Dil) {
                if ($value == $Dil) {
                    //if $value equals one of the dilutions from the providerConfig return true
                    return true;
                }
            }

            //if the dilution was not found in the provider config return false
            return false;
        } elseif ($ProviderConfig->profileRate == 5) {
            //standard dilutions 5
            $DilutionsCSV = $ProviderConfig->dilutions5;

            $AvailDilutions = explode(',', $DilutionsCSV);

            foreach ($AvailDilutions as $Dil) {
                if ($value == pow(5, $Dil)) {
                    //if $value equals one of the dilutions from the providerConfig return true
                    return true;
                }
            }

            //if the dilution was not found in the provider config return false
            return false;
        } else {
            //standard dilutions 10
            $DilutionsCSV = $ProviderConfig->dilutions10;

            $AvailDilutions = explode(',', $DilutionsCSV);

            foreach ($AvailDilutions as $Index1 => $Dil) {
                if ($ValidBottle->dilution == pow(10, $Dil)) {
                    //if $value equals one of the dilutions from the providerConfig return true
                    return true;
                }
            }

            //if the dilution was not found in the provider config return false
            return false;
        }
    }

    public function validateDistinctPatient($attribute, $value, $parameters, $validator)
    {
        $Count = Patient::where('firstname', $parameters[1])
            ->where('mi', $parameters[2])
            ->where('lastname', $parameters[3])
            ->where('dob', $parameters[4])
            ->where('chart', $parameters[5])
            ->where('patient_id', '!=', $parameters[0])
            ->where('archived', 'F')
            ->count();

        return $Count == 0;
    }

    public function validateDecimal52($attribute, $value, $parameters, $validator)
    {
        return is_numeric($value) && (preg_match("/^-?[0-9]{1,3}(\.[0-9]{1,2})?$/", $value) === 1);
    }

    public function validateDecimal63($attribute, $value, $parameters, $validator)
    {
        return is_numeric($value) && (preg_match("/^-?[0-9]{1,3}(\.[0-9]{1,3})?$/", $value) === 1);
    }

    public function validateDecimal73($attribute, $value, $parameters, $validator)
    {
        return is_numeric($value) && (preg_match("/^[0-9]{1,4}(\.[0-9]{1,3})?$/", $value) === 1);
    }

    public function validateStandard($attribute, $value, $parameters, $validator)
    {
        return preg_match("~^[a-zA-Z0-9-\./\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_:%\"]*$~", $value);
    }

    public function validateSeason($attribute, $value, $parameters, $validator)
    {
        return preg_match("~^\*?\d\d?/\d\d?$~", $value);
    }

    public function validateChangeReason($attribute, $value, $parameters, $validator)
    {
        return preg_match("~^[a-zA-Z0-9-\./\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_:%§\"]*$~", $value);
    }

    public function validateNotes($attribute, $value, $parameters, $validator)
    {
        return preg_match("~^[a-zA-Z0-9-\./\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$~", $value);
    }

    public function validateZipcode($attribute, $value, $parameters, $validator)
    {
        return preg_match('/^[0-9a-zA-Z-]{0,45}$/', $value);
    }

    public function validateInventoryInHistory($attribute, $value, $parameters, $validator)
    {
        $Inventory = array();

        if (!is_null($parameters[0]) && $parameters[0] != 0) {
            //if RX_id was provided try getting extract list

            try {
                //load the RX with all of its compound and vial info
                $RX = prescription::with('compound.vial')->find($parameters[0]);
                //if it was found build an array of inventory items
                //get all vials for first compound associated with the RX.
                foreach ($RX->compound->first()->vial as $Index => $Vial) {
                    $Inventory[$Index] = $Vial->inventory_id;
                }

                return in_array($value, $Inventory);
            } catch (ModelNotFoundException $e) {
                //if it wasn't found move to else if
            }
        } elseif (!is_null($parameters[1]) && $parameters[1] != '') {
            //if a postpone ID was provided try getting extract list from that
            try {
                //load the RX with all of its compound and vial info
                $POST = postpone::with('cpid1.vial', 'cpid2.vial', 'cpid3.vial', 'cpid4.vial', 'cpid5.vial', 'cpid6.vial', 'cpid7.vial', 'cpid8.vial')->find($parameters[1]);
                //if it was found, collect inventory_ids

                //first compound cant be null so dont check...just get its inventory ids
                foreach ($POST->cpid1->first()->vial as $Index => $Vial) {
                    $Inventory[$Index] = $Vial->inventory_id;
                }

                return array_search($value, $Inventory) !== false;
            } catch (ModelNotFoundException $e) {
                //if it wasn't found exit ifs and return true
            }
        }
        //else no RX or Postpone to match so inventory is good
        return true;
    }

    public function validateValidSize($attribute, $value, $parameters, $validator)
    {
        try {
            $SizeCSV = config::where('name', 'sizes')->first()->value;
        } catch (ModelNotFoundException $e) {
            return $value === '5 mL' || $value === '10 mL' || $value === '15 mL';
        }

        $Sizes = explode(',', $SizeCSV);

        return in_array($value, $Sizes);
    }

    public function validateMatchQueuedName($attribute, $value, $parameters, $validator)
    {
        if (is_null($parameters[0])) {
            return true;
        }

        try {
            $cpid = postpone::where('deleted', 'F')->findOrFail($parameters[0])->compound_id1;
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return $value == compound::findOrFail($cpid)->name;
    }

    // public function validateMatchQueuedRx($attribute, $value, $parameters, $validator)
    // {
    //     if (is_null($parameters[0])) {
    //         return true;
    //     }

    //     try {
    //         $cpid = postpone::where('deleted', 'F')->findOrFail($parameters[0])->compound_id1);
    //     } catch (ModelNotFoundException $e) {
    //         return false;
    //     }

    //     return $value == compound::findOrFail($cpid)->rx_id;
    // }

    public function validateMatchRxName($attribute, $value, $parameters, $validator)
    {
        if (is_null($parameters[0])) {
            return true;
        }

        try {
            return $value == compound::where('deleted', 'F')->where('rx_id', $parameters[0])->
            firstOrFail()->name;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function validateOptins($attribute, $value, $parameters, $validator)
    {
        $OptIns = explode(',', $value);

        $Good = true;

        foreach ($OptIns as $Index => $OptIn) {
            try {
                $Message = Message::where('deleted', 'F')->findOrFail($OptIn);
            } catch (ModelNotFoundException $e) {
                $Good = false;

                break;
            }
        }

        return $Good;
    }

    public function validateColorCSV($attribute, $value, $parameters, $validator)
    {
        $Names = explode(',', strtoupper($value));

        $Good = true;

        foreach ($Names as $Index => $Color) {
            switch ($Color) {
                case 'RED':
                    break;
                case 'YLW':
                    break;
                case 'BLUE':
                    break;
                case 'GRN':
                    break;
                case 'SLVR':
                    break;
                case 'ORNG':
                    break;
                case 'PRPL':
                    break;
                case 'WHT':
                    break;
                case 'LTGR':
                    break;
                case 'LTBL':
                    break;
                case 'PINK':
                    break;
                case 'GOLD':
                    break;
                case '':
                    break;
                default:
                    $Good = false;
            }
        }

        return $Good;
    }

    public function validateColor($attribute, $value, $parameters, $validator)
    {
        $Good = true;

        switch ($value) {
            case 'RED':
                break;
            case 'YLW':
                break;
            case 'BLUE':
                break;
            case 'GRN':
                break;
            case 'SLVR':
                break;
            case 'ORNG':
                break;
            case 'PRPL':
                break;
            case 'WHT':
                break;
            case 'LTGR':
                break;
            case 'LTBL':
                break;
            case 'PINK':
                break;
            case 'GOLD':
                break;
            case '':
                break;
            default:
                $Good = false;
        }

        return $Good;
    }
}

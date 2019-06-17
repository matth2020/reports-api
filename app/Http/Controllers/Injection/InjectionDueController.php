<?php

namespace App\Http\Controllers\Injection;

use App\Http\Controllers\Injection\InjectionBaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\GeneralPurpose\SimpleInjection;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Injection;
use App\Models\Compound;
use App\Models\Vial;
use Carbon\Carbon;

class InjectionDueController extends InjectionBaseController
{
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"InjectionDue"},
     *     path="/patient/{patient_id}/injection_due",
     *     summary="Returns a list of all injectionsDue  in the system.",
     *     description="",
     *     operationId="api.injectionDue.index",
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Injections are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionDue object fields to return.",
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
    public function index(request $request)
    {
        $this::getRequestOptions($request);

        //get patients prescriptions
        $Prescriptions = Prescription::where('strikethrough', 'F')
            ->where('patient_id', $this->RequestOptions->patient_id)->get();

        $RXarray = array();
        $base_URL = '/v1/patient/' . $this->RequestOptions->patient_id;
        foreach ($Prescriptions as $Prescription) {
            // for each prescription make a request to the single prescription endpoint
            $fakeRequest = Request::create($base_URL . '/prescription/' . $Prescription->prescription_id . '/injection_due', 'GET');
            $result = $this->getInjectionDue($fakeRequest, $this->RequestOptions->patient_id, $Prescription->prescription_id);
            $RxDue = $result->getData();
            array_push($RXarray, $RxDue);
        }
        //No finish and filter because this goes through the finishAndFilter
        //as part of the getInjectionsDue method in the foreach loop above
        return response()->json($RXarray);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"InjectionDue"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/injection_due",
     *     summary="Returns a single injectionDue in the system identified by {id}.",
     *     description="",
     *     operationId="api.injectionDue.index.id",
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Injections are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription to fetch details for.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionDue object fields to return.",
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
    public function getInjectionDue(request $request, $patient_id, $prescription_id, $forensic = false)
    {
        $this::getRequestOptions($request);
        $this->RequestOptions->isForensic = $forensic;
        try {
            $Prescription = Prescription::where('patient_id', $this->RequestOptions->patient_id)
                ->findOrFail($this->RequestOptions->prescription_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }


        $LastInjection = $this::findLastInjection($this->RequestOptions->prescription_id, $this->RequestOptions->patient_id);

        $RxDetails = !is_null($LastInjection) ? $LastInjection->compound->prescription : Prescription::find($this->RequestOptions->prescription_id);

        $Adjusts = $this::getAdjustmentsDue($this->RequestOptions->prescription_id);

        $InjectionsDue = app()->make('stdClass');
        $NextInjection = app()->make('stdClass');

        if ($Adjusts->count() > 0) {
            //add the next adjustment and return
            $NextInjection->dilution = $Adjusts[0]->dilution;
            $NextInjection->dose = $Adjusts[0]->dose;
            $NextInjection->site = !is_null($Prescription->site) ? $Prescription->site : 'ask';
            $NextInjection->type = 'Pending Adjustment';
            $NextInjection->injection_adjust_id = $Adjusts[0]->injAdjust_id;
            $NextInjection->note = 'Adjusted by: ' . $Adjusts[0]->user->displayname . "\nReason: " . $Adjusts[0]->reason . "\nDate: " . $Adjusts[0]->date;
            $NextInjection->forensic_adjust = $Adjusts[0];
        } elseif (is_null($Prescription->treatment_plan_id) || $Prescription->treatment_plan_id == -1) {
            $NextInjection->dilution = 'ask';
            $NextInjection->dose = 'ask';
            $NextInjection->site = !is_null($Prescription->site) ? $Prescription->site : 'ask';
            $InjectionsDue->name = 'No treatment plan assigned';
            $NextInjection->note = "No treatment plan assigned";
        } else {
            $NextStep = $this->addFuturePlan($Prescription, $Prescription->treatment_plan_id, true /*singleStep*/);
            if (!is_null($NextStep->DoseAdjustDetails)) {
                $NextInjection = $this->buildDoseRulesNextInj($NextStep, $NextInjection, $Prescription);
            } else {
                $NextInjection->dilution = 'ask';
                $NextInjection->dose = 'ask';
                $NextInjection->site = !is_null($Prescription->site) ? $Prescription->site : 'ask';
                $NextInjection->note = "Unable to calculate next step.";
            }
        }

        $SimpleLastInjection = new SimpleInjection();
        if (!is_null($LastInjection)) {
            $SimpleLastInjection->injection_id = $LastInjection->injection_id;
            $SimpleLastInjection->date = $LastInjection->date;
            $SimpleLastInjection->dose = $LastInjection->dose;
            $SimpleLastInjection->type = 'last_injection';
            $SimpleLastInjection->site = $LastInjection->site;
            $SimpleLastInjection->note = $LastInjection->notes_user;
            $SimpleLastInjection->vial_id = $LastInjection->compound->compound_id;
            $SimpleLastInjection->dilution = $LastInjection->compound->dilution;
            $SimpleLastInjection->color = $LastInjection->compound->color;
            $SimpleLastInjection->reaction = $LastInjection->getReaction();
            $SimpleLastInjection->bottle_number = $LastInjection->compound->bottleNum;
            $InjectionsDue->last_injection = $SimpleLastInjection;
        }

        $InjectionsDue->prescription_id = $this->RequestOptions->prescription_id;
        $InjectionsDue->prescription_number = $RxDetails->prescription_num;
        $InjectionsDue->next_injection = $NextInjection;
        $InjectionsDue->days_since_last = !is_null($LastInjection) ? Carbon::createFromFormat('Y-m-d H:i:s', $LastInjection->date)->setTime(0, 0, 0)->diffInDays(now()->setTime(0, 0, 0)) : null;

        return $this->finishAndFilter($InjectionsDue);
    }

    private function buildDoseRulesNextInj($NextStep, $NextInjection, $Prescription)
    {
        $DoseAdjustDetails = $NextStep->DoseAdjustDetails;
        if ($DoseAdjustDetails->StepsAdjusted !== "1" && $DoseAdjustDetails->StepsAdjusted !== "+1") {
            $NextInjection->type = 'Dose rules adjustment';
            $StepsAdjusted = $DoseAdjustDetails->StepsAdjusted;
            $DaysLate = $DoseAdjustDetails->DaysLate;
            $ReactType = isset($DoseAdjustDetails->ReactType) ? $DoseAdjustDetails->ReactType : 'Unknown';
            $ReactVal = isset($DoseAdjustDetails->ReactVal) ? $DoseAdjustDetails->ReactVal : 'Unknown';
            $reaction = isset($DoseAdjustDetails->ReactType) ? '('.$ReactType.') '.$ReactVal.' reaction.' : 'no reaction';
            $NextInjection->note = 'Adjusted '.$StepsAdjusted.' steps due to ' . $DaysLate . ' days late and '.$reaction;
        } else {
            $NextInjection->type = 'Per treatment plan';
            $NextInjection->note = 'Next step per plan. No dose rules adjustments required.';
        }

        if (isset($NextStep->min_date) && !Carbon::createFromFormat('Y-m-d H:i:s', $NextStep->min_date)->lte(Carbon::now())) {
            $NextInjection->type = 'Not due';
            $NextInjection->note = 'This injection is not due until '.$NextStep->min_date;
        }

        if (isset($NextStep->min_date)) {
            $NextInjection->min_date = $NextStep->min_date;
        }
        if (isset($NextStep->max_date)) {
            $NextInjection->max_date = $NextStep->max_date;
        }
        $NextInjection->dilution = $NextStep->tpStep['dilution'];
        $NextInjection->dose = $NextStep->tpStep['dose'];
        $NextInjection->site = $Prescription->site;
        $NextInjection->date = $NextStep->date;
        $NextInjection->forensic_nextInjection = $NextStep;
        $NextInjection->barcode = Vial::whereHas('compound', function ($query) use ($NextStep) {
            $query->where('rx_id', $this->RequestOptions->prescription_id)
                ->where('dilution', $NextStep->tpStep['dilution'])
                ->where('active', 'T');
        })
            ->orderBy('mixdate', 'desc')
            ->pluck('barcode')
            ->first();
        return $NextInjection;
    }

    protected function finalize($InjectionDue)
    {
        if (!$this->RequestOptions->isForensic) {
            //If this was not a forensic request, remove the extra details
            unset($InjectionDue->next_injection->forensic_nextInjection);
            unset($InjectionDue->next_injection->forensic_adjust);
        }
        return $InjectionDue;
    }
}

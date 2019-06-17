<?php

namespace App\Http\Controllers\Prescription;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\LockableController;
use Illuminate\Validation\Validator;
use function DeepCopy\deep_copy;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Compound;
use App\Models\Profile;
use App\Models\Dosing;
use DB;

class PrescriptionController extends LockableController
{
    public static $requiredLocks = ['mixingLock', 'injectionLock'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Prescription"},
     *     path="/patient/{patient_id}/prescription",
     *     summary="Returns a list of all Prescriptions that apply to a given patient.",
     *     description="",
     *     operationId="api.Prescription.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Prescriptions are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Prescription object fields to return.",
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
        return $this::handleRequest($request, new Prescription);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Prescription"},
     *     path="/patient/{patient_id}/prescription/{id}",
     *     summary="Returns a single Prescription in the system identified by {id}.",
     *     description="",
     *     operationId="api.Prescription.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Prescriptions are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the Prescription to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Prescription object fields to return.",
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
    public function getPrescription(request $request)
    {
        return $this::handleRequest($request, new Prescription);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Prescription"},
     *     path="/patient/{patient_id}/prescription/_search",
     *     summary="Returns a list Prescriptions in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.Prescription.searchPrescription",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="Prescription object",
     *        in="body",
     *        description="Prescription object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Prescription"),
     *     ),
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Prescription is to be created.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Prescription object fields to return.",
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
    public function searchPrescription(request $request)
    {
        return $this::handleRequest($request, new Prescription);
    }

    /**
     * Create a new prescription object
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Prescription"},
     *     path="/patient/{patient_id}/prescription",
     *     summary="Create a new prescription object.",
     *     operationId="api.Prescription.createPrescription",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Prescriptions are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="Prescription object",
     *        in="body",
     *        description="Prescription object to be created",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Prescription"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Prescription object fields to return.",
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
    public function createPrescription(request $request)
    {
        $this->getRequestOptions($request);
        $this->RequestOptions->user_id = $request->user()->user_id;

        try {
            $Profile = Profile::findOrFail($request->input('profile_id'));
        } catch (ModelNotFoundException $e) {
            $Profile = null;
            // return it as a validation error for bad profile_id
        }

        $request->merge([
            'strike_through' => $request->exists('strike_through') ? $request->input('strike_through') : 'F',
            'patient_id' => $this->RequestOptions->patient_id,
            'user_id' => $this->RequestOptions->user_id,
            'timestamp' => null,
            'custom_units' => null,
            'provider_config_id' => $request->input('profile_id'),
            'source' => 'API',
            'fold' => isset($Profile->profileRate) ? $Profile->profileRate : null
        ]);

        if ($request->exists('extracts')) {
            $Extracts = $request->input('extracts');
            foreach ($Extracts as $Idx => $Extract) {
                $Extracts[$Idx]['dose'] = 0.00;
            }
            $request->merge([
                'extracts' => $Extracts
            ]);
        }

        $ClinicId = $request->exists('clinic') ? $request->input('clinic')['clinic_id'] : $request->input('clinic_id');
        if (!is_null($ClinicId)) {
            $request->merge(['clinic_id' => $ClinicId]);
        }
        $ProviderId = $request->exists('provider') ? $request->input('provider')['provider_id'] : $request->input('provider_id');
        if (!is_null($ProviderId)) {
            $request->merge(['provider_id' => $ProviderId]);
        }

        return $this::handleRequest($request, new Prescription);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Prescription"},
     *     path="/patient/{patient_id}/prescription/{id}",
     *     summary="Update a prescription object.",
     *     description="",
     *     operationId="api.Prescription.updatePrescription",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose prescription is to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the prescription to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="Prescription object",
     *        in="body",
     *        description="Prescription object containing only the fields that need to be updated. Currently only treatment_plan_id, dosing_plan_id, prescription_note, strike_through, strike_through_reason, clinic_id, provider_id, and injection_site may be altered.",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/InjAdjust"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Prescription object fields to return.",
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
    public function updatePrescription(request $request)
    {
        //Below I replace all data attached to ensure we don't accidentally let the user update
        //something that they shouldnt

        $TpId = $request->input('treatment_plan_id');
        $DpId = $request->input('dosing_plan_id');
        $RxNote = $request->input('prescription_note');
        $Strike = $request->input('strike_through');
        $StrikeNote = $request->input('strike_through_reason');
        $ClinicId = $request->input('clinic_id');
        $ProviderId = $request->input('provider_id');
        $Site = $request->input('injection_site');

        //replace the data with a blank array
        $request->replace([]);
        //now conditionally merge in the allowed values if present
        if ($request->exists('profile_id')) {
            try {
                $Profile = Profile::findOrFail($request->input('profile_id'));
            } catch (ModelNotFoundException $e) {
                $Profile = null;
                // return it as a validation error for bad profile_id
            }
            $request->merge([
                'profile_id' => $request->input('profile_id'),
                'fold' => isset($Profile->profileRate) ? $Profile->profileRate : null
            ]);
        }
        if (!is_null($TpId)) {
            $request->merge([
                'treatment_plan_id' => $TpId
            ]);
        }
        if (!is_null($DpId)) {
            $request->merge([
                'dosing_plan_id' => $DpId,
            ]);
        }
        if (!is_null($RxNote)) {
            $request->merge([
                'prescription_note' => $RxNote,
            ]);
        }
        if (!is_null($Strike)) {
            $request->merge([
                'strike_through' => $Strike,
            ]);
        }
        if (!is_null($StrikeNote)) {
            $request->merge([
                'strike_through_reason' => $StrikeNote,
            ]);
        }
        if (!is_null($ClinicId)) {
            $request->merge([
                'clinic_id' => $ClinicId,
            ]);
        }
        if (!is_null($ProviderId)) {
            $request->merge([
                'provider_id' => $ProviderId,
            ]);
        }
        if (!is_null($Site)) {
            $request->merge([
                'injection_site' => $Site
            ]);
        }
        return $this::handleRequest($request, new Prescription);
    }

    protected function queryWith($Query)
    {
        return $Query->select('prescription_id', '5or10', 'patient_id', 'timestamp', 'multiplier', 'user_id', 'provider_id', 'provider_config_id', 'clinic_id', 'prescription_num', 'strikethrough', 'strikethrough_reason', 'prescription_note', 'customUnits', 'treatment_plan_id', 'doseRuleNames_id', 'priority', 'site', 'external_id', 'created_at', 'created_by', 'updated_at', 'updated_by')
            ->with(
                'compounds:rx_id,compound_id,active,dilution,bottleNum,name,color,size,currVol',
                'compounds.vials:compound_id,dosing_id,barcode,inventory_id,traylocation,postponed,outdate,transaction,diltPos',
                'compounds.vials.dosing:dosing_id,ent_dilution,extract_id',
                'compounds.vials.dosing.extract:extract_id,name,isDiluent',
                'compounds.vials.inventory.extract:extract_id,name,isDiluent',
                'clinic:clinic_id,name',
                'user:user_id,displayname',
                'dosingPlan:doseRuleNames_id,name',
                'provider:provider_id,displayname',
                'treatmentPlan:treatment_plan_id,name'
            );
    }

    protected function queryWhere($Query)
    {
        return $Query->where('patient_id', $this->RequestOptions->patient_id);
    }

    public function finalize($Object)
    {
        return $this->buildRx($Object);
    }

    protected function validateAndSave(request $request, $Object = null)
    {
        if ($this->getLock()) {
            $ValidationErrors = [];
            // validate prescription row
            if (!$Object->validate($request->all(), $this->RequestOptions->id)) {
                $ValidationErrors = array_merge($ValidationErrors, $Object->errors()->toArray());
            }
            //now validate each dosing row
            if ($request->exists('extracts')) {
                foreach ($request->input('extracts') as $Idx => $dosingRow) {
                    $dosing = new Dosing;
                    if (!$dosing->validate($dosingRow, null)) {
                        $Errors = $dosing->errors()->toArray();
                        if (sizeOf($ValidationErrors)>0 || $this->RequestOptions->isCreate) {
                            // if the prescription failed validation, the prescription_id
                            // will be bad so remove it from the dosing validation errors
                            if (isset($Errors['prescription_id'])) {
                                unset($Errors['prescription_id']);
                            }
                        }
                        if (sizeOf($Errors) > 0) {
                            $ValidationErrors['extract_'.$Idx] = $Errors;
                        }
                    }
                }
            } elseif ($this->RequestOptions->isCreate && $this->RequestOptions->request['outsourced'] !== 'T') {
                $ValidationErrors['extracts'] = ['The extracts field is required.'];
            }
            if (sizeOf($ValidationErrors) > 0) {
                return response()->json($ValidationErrors, 400);
            }

            DB::transaction(function () use ($request, $Object, &$primaryId) {
                if ($this->RequestOptions->isCreate) {
                    //if its a create assign the rx_num. Do this here so its in the
                    //transaction.
                    $request->merge(['prescription_number' => $this::allocateRxNum()]);
                }
                // Convert the property names to match db and save
                $Object = $this::APItoDB($request, $Object);

                $query = $Object->save();
                //If we are saving something with a null id it must have been a create
                //so use the objects primary key to find the created id.
                $primaryId = is_null($this->RequestOptions->id) ? $Object[$Object->getKeyName()] : $this->RequestOptions->id;

                if ($this->RequestOptions->isCreate && $request->input('extracts') !== null) {
                    //create orphan dosing rows for prescription object
                    foreach ($request->input('extracts') as $dosingRow) {
                        $dosing = new Dosing;
                        $dosing->extract_id = $dosingRow['extract_id'];
                        $dosing->prescription_id = $primaryId;
                        $dosing->save();
                    }
                }
            });
            // Fetch the resulting object and return it
            $Query = App()->make(get_class($Object));
            $newObject = $this->queryWith($Query)->find($primaryId);
            return $this->finishAndFilter($newObject);
        } else {
            return response()->json('Another user currently owns one or more locks required to perform this action. Please try again later.', 401);
        }
    }

    private function buildRx(Prescription $Prescription)
    {
        $Bottles = $Prescription->compounds;
        unset($Prescription->compounds);

        $minCompoundId = $Bottles->min('compound_id');

        if (sizeof($Bottles) > 0) {
            // find extracts from first bottle

            $Extracts = [];
            foreach ($Bottles[0]->vials as $bottleExt) {
                $obj = deep_copy($bottleExt->dosing);
                unset($obj->dosing_id);
                $obj->name = $obj->extract->name;
                $obj->is_diluent = $obj->extract->isDiluent;
                unset($obj->extract);
                unset($obj->ent_dilution);
                array_push($Extracts, $obj);
            }
            $Prescription->extracts = $Extracts;
        } else {
            //find extracts from dosing rows
            foreach ($Prescription->extracts as $Extract) {
                $Extract->extract_id = $Extract->extract->extract_id;
                $Extract->name = $Extract->extract->name;
                $Extract->is_diluent = $Extract->extract->isDiluent;
                unset($Extract->extract);
                unset($Extract->weight);
                unset($Extract->ent_dilution);
                unset($Extract->dosing_id);
            }
        }

        $SetsArray = [];
        foreach ($Bottles as $Bottle) {
            //set the rx name to the name of the first bottle
            if ($minCompoundId === $Bottle->compound_id) {
                $Prescription->name = $Bottle->name;
            }
            if (sizeOf($Bottle->vials) > 0 && isset($SetsArray[$Bottle->vials[0]->transaction])) {
                array_push($SetsArray[$Bottle->vials[0]->transaction], $this->buildBottle($Bottle));
            } elseif (sizeOf($Bottle->vials) > 0) {
                $SetsArray[$Bottle->vials[0]->transaction] = [$this->buildBottle($Bottle)];
            } else {
                // not vials in this compound?
            }
        }
        // fix set objects
        $Sets = [];
        foreach ($SetsArray as $index => $Set) {
            $Object = app()->make('stdClass');
            $Object->order_id = (int)$index - 800000;
            $Object->transaction = $index;
            $Object->vials = $Set;
            array_push($Sets, $Object);
        }
        $Prescription->treatment_sets = $Sets;

        unset($Prescription->clinic_id);
        unset($Prescription->doseRuleNames_id);
        unset($Prescription->treatment_plan_id);
        unset($Prescription->user_id);
        unset($Prescription->provider_id);

        $Prescription->everBeenMixed();
        unset($Prescription->compounds);

        return $Prescription;
    }

    private function buildBottle($Bottle)
    {
        $Vials = $Bottle->vials;
        unset($Bottle->vials);
        $Extracts = array();
        foreach ($Vials as $Vial) {
            $Extract = $this->buildExtract($Vial);
            array_push($Extracts, $Extract);
        }
        if (sizeof($Extracts)>0) {
            $Bottle->extracts = $Extracts;
        }
        if ($Vials->count() > 0) {
            $Bottle->barcode = $Vials->first()->barcode;
            $Bottle->tray_location = $Vials->first()->traylocation;
            $Bottle->mixed = $Vials->first()->postponed === 'F' ? 'T' : 'F';
            $Bottle->outdate = $Vials->first()->outdate;
            $Bottle->transaction = $Vials->first()->transaction;
        }
        $Bottle->level = $this->getLevel($Bottle);
        unset($Bottle->currVol);
        return $Bottle;
    }
    private function buildExtract($Vial)
    {
        if (!is_null($Vial->dosing)) {
            $Extract = app()->make('stdClass');
            $Extract->dose = $Vial->dosing->dose;
            $Extract->inventory_id = $Vial->inventory_id;
            $Extract->ent_dilution = $Vial->dosing->ent_dilution;
            $Extract->extract_id = $Vial->inventory->extract_id;
            $Extract->name = $Vial->inventory->extract->name;
            if ($Vial->inventory->extract->isDiluent === 'T') {
                $Extract->is_diluent = true;
                if (!is_null($Vial->diltPos) && $Vial->diltPos !== "") {
                    $Extract->diluent_position = (int) $Vial->diltPos;
                }
            }
            return $Extract;
        } else {
            $Extract = app()->make('stdClass');
            $Extract->Error = 'Missing dosing rows';
            return $Extract;
        }
    }
    private function getLevel($Object)
    {
        $CurrVol = $Object->currVol;
        $Size = (int) trim(str_replace("ml", "", strtolower($Object->size)));
        if ($Size > 0) {
            $levelInt = (int)($CurrVol / $Size * 100);
            $levelStr = strval($levelInt) . '%';
            return $levelStr;
        } else {
            return '0%';
        }
    }
}

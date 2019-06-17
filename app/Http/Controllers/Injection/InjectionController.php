<?php

namespace App\Http\Controllers\Injection;

use App\Http\Controllers\Injection\InjectionAdjustController;
use App\Http\Controllers\Injection\InjectionBaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;
use App\Models\TreatPlanDetails;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Injection;
use App\Models\InjAdjust;
use App\Models\Compound;
use Carbon\Carbon;
use DB;

class InjectionController extends InjectionBaseController
{
    public static $requiredLocks = ['injectionLock'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/injection",
     *     summary="Returns a list of all Injections that apply to a given patient.",
     *     description="",
     *     operationId="api.Injection.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Injections are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return.",
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
        return $this->handleRequest($request, new Injection);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/injection/{id}",
     *     summary="Returns a single Injection in the system identified by {id}.",
     *     description="",
     *     operationId="api.Injection.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Injections are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the Injection to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return.",
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
    public function getInjection(request $request)
    {
        return $this::handleRequest($request, new Injection);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/injection/_search",
     *     summary="Returns a list of Injections in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.Injection.searchInjection",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="Injection object",
     *        in="body",
     *        description="Injection object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Injection"),
     *     ),
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Injections are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return.",
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
    public function searchInjection(request $request)
    {
        return $this->handleRequest($request, new Injection);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/injection",
     *     summary="Create a new injection.",
     *     description="",
     *     operationId="api.injection.createInjection",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose injection is to be administered.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Injection object to be created in the system. (The injection_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Injection"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return",
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
    public function createInjection(Request $request, $patient_id, $user_id = null)
    {
        $this->getRequestOptions($request);
        $this->RequestOptions->user_id = is_null($user_id) ? $request->user()->user_id : $user_id;
        $timestamp = !is_null($request->json('datetime_administered')) ? $request->json('datetime_administered') : Carbon::now()->toDateTimeString();
        $request->merge([
            'patient_id' => $patient_id,
            'user_id' => is_null($user_id) ? $request->user()->user_id : $user_id,
            'datetime_administered' => $timestamp,
            'datetime_entered' => Carbon::now()->toDateTimeString()
        ]); //make patient_id available to the validator
        //If no reaction data was provided, we cant just use database defaults because the "no reaction" values
        //can be different between customers so we need to find out what they are and explicitly set them.
        $Reactions = $this::getReactionNames();

        if (is_null($request->input('local_reaction'))) {
            $request->merge(['local_reaction' => $Reactions->local[0]]);
        }
        if (is_null($request->input('systemic_reaction'))) {
            $request->merge(['systemic_reaction' => $Reactions->systemic[0]]);
        }
        return $this->handleRequest($request, new Injection);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/multiinjection",
     *     summary="Create multiple injections in a single transaction.",
     *     description="",
     *     operationId="api.injection.createMultiInjection",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose injection is to be administered.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Injection object to be created in the system. (The injection_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/Injection")
     *        ),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return",
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
    public function createMultiInjection(Request $request, $patient_id)
    {
        $this::getRequestOptions($request);
        //Note that the code below looks a little generic because I have started a first pass at
        //making something somewhat universal that eventually can be moved to controller for
        //standard array based create endpoints. AMH
        $Injection = new Injection; //just an instance to request the validator from
        $Errors = [];
        foreach ($request->all() as $key => $Object) {
            if ($key === 'transaction_id') {
                continue;
            }
            $timestamp = isset($Object['datetime_administered']) ? $Object['datetime_administered'] : Carbon::now()->toDateTimeString();
            //this is equivalent of the array merge that happens in the create endpoint but it must happen
            //here in order to be ready for validation.
            $Object['patient_id'] = $patient_id;
            $Object['user_id'] = $this->RequestOptions->user_id;
            $Object['datetime_administered'] = $timestamp;
            $Object['datetime_entered'] = Carbon::now()->toDateTimeString();
            $id = isset($Object[$Injection->getKeyName()]) ? $Object[$Injection->getKeyName()] : null;
            if (!$Injection->Validate($Object, $id)) {
                $Errors ['injection '.$key] = $Injection->errors();
            }
        }

        if (sizeof($Errors) > 0) {
            return response()->json($Errors, 400);
        }
        $Results = [];
        //no validation errors so now start a transaction and loop through single creates.
        DB::transaction(function () use ($request, $patient_id, $Results) {
            foreach ($request->all() as $key => $Object) {
                if ($key === 'transaction_id') {
                    continue;
                }
                $fakeRequest = Request::create('/v1/patient/' . $patient_id . '/injection', 'POST', $Object);
                $data = new \Symfony\Component\HttpFoundation\ParameterBag;
                $data->add($Object);
                $fakeRequest->setJson($data);

                $result = $this->createInjection($fakeRequest, $patient_id, $this->RequestOptions->user_id);

                if ($result->status() != 200) {
                    throw new Exception(); //if one of the injections doesnt record, throw an exception to
                    //hopefully break the transaction? This needs testing.
                }

                array_push($Results, $result->getData());
            }
        });
        //return overall result array
        return response()->json($Results);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/injection/{id}",
     *     summary="Mark a injection as deleted.",
     *     description="",
     *     operationId="api.injection.deleteInjection",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Injections are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the injection to mark deleted.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return.",
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
    public function deleteInjection(request $request)
    {
        return $this->handleRequest($request, new Injection);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/injection/{id}",
     *     summary="Update a injection object.",
     *     description="",
     *     operationId="api.injection.updateInjection",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Injections are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the injection to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="injection object",
     *        in="body",
     *        description="Injection object containing only the fields that need to be updated. (The injection_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Injection"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return.",
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
    public function updateInjection(Request $request)
    {
        $this::getRequestOptions($request);
        $this->RequestOptions->user_id = $request->user()->user_id;
        return $this->handleRequest($request, new Injection);
    }

    protected function queryWith($Query)
    {
        return $Query->with([
            'user:user_id,displayname', //pre load user displayname
            'compound:compound_id,rx_id,dilution,bottleNum,name', //preload relevant compound details
            'compound.vials:compound_id,barcode', //preload vial barcodes
            'compound.prescription:prescription_id,prescription_num' //preload rxnum
        ]);
    }

    protected function queryWhere($Query)
    {
        return $Query->whereHas('compound.prescription', function ($query1) {
            //must have rx row
                $query1->where('patient_id', $this->RequestOptions->patient_id); //rx row must be assigned to this ptnt
        });
    }

    protected function queryModifier($Query)
    {
        return $Query->where('deleted', 'F')
            ->orderBy('date', 'desc');
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  Injection $Injection object returned from the database
     * @param  $Filter of properties to include in returned object
     * @param  $AllowedFields
     * @return Injection object
     */
    protected function finalize($Injection)
    {
        $Injection->dilution = $Injection->compound->dilution;
        $Injection->prescription_number = $Injection->compound->prescription->prescription_num;
        $Injection->prescription_id = $Injection->compound->prescription->prescription_id;
        //replace the following belongsTo relationship objects with just the
        //object name.
        $Injection->barcode = $Injection->compound->vials[0]->barcode;
        $Injection->vial_number = $Injection->compound->bottleNum;
        $Injection->vial_name = $Injection->compound->name;
        $Injection->vial_id = $Injection->compound->compound_id;
        if (is_object($Injection->user)) {
            $user = $Injection->user->displayname;
            unset($Injection->user);
            $Injection->user = $user;
        }
        unset($Injection->compound);

        return $Injection;
    }

    private function updateBottleVolume($CompoundRequested, $request)
    {
        if ($this->RequestOptions->isCreate) {
            //reduce the bottle volume by the dose ammount with a minimum of 0
            $CompoundRequested->currVol = max(0, (float) $CompoundRequested->currVol - (float) $request->input('dose'));
            $CompoundRequested->save();
        } elseif (!is_null($request->input('dose'))) {
            //If its an update and the dose was included, we need to query what the original injection row was,
            //then update the bottle volume based on the diffence between the old injection details and the new
            //ones.
            $lastDose = Injection::find($this->RequestOptions->injection_id)->dose;
            $difference = (float) $lastDose - (float) $request->input('dose');
            $SizeString = strtoupper($CompoundRequested->size);
            $MaxSize = (float) str_replace(['ML',' '], '', $SizeString); //remove the non numeric elements
            $newVolume = (float) $CompoundRequested->currVol + (float) $difference;
            $newVolume = max(0, $newVolume); //cant be less than 0
            $newVolume = min($MaxSize, $newVolume); //Cant be larger than the bottle size

            $CompoundRequested->currVol = $newVolume;
            $CompoundRequested->save();
        }
        return;
    }

    private function updatePrescriptionSite(Prescription $Prescription, request $request)
    {
        $Prescription->site = $request['site'];
        $Prescription->save();
    }

    private function getInjectionDetails(request $request, $Object)
    {
        $data = $request->all();
        $CompoundRequested = $Object::getCompound($data);
        if (is_null($CompoundRequested)) {
            //This is an update and they didn't give us compound_id or barcode
            $InjectionRequested = Injection::with('compound')->find($this->RequestOptions->injection_id);
            $CompoundRequested = $InjectionRequested->compound;
        }
        $CompoundRequested->load('prescription');
        $Object['compound_id'] = $CompoundRequested->compound_id;

        $this->updateBottleVolume($CompoundRequested, $request);
        //removed 2-16-18 injections should not change default site
        //$this->updatePrescriptionSite($CompoundRequested->prescription, $request);

        //This get injectionDue is not the contoller method but the injection model method
        $InjectionDue = $Object::getInjectionDue($CompoundRequested->prescription->patient_id, $CompoundRequested->rx_id);

        if ($this->RequestOptions->isCreate && !$Object::isHistorical($request->all())) {
            if (isset($InjectionDue->next_injection->forensic_nextInjection)) {
                $ForensicDetails = $InjectionDue->next_injection->forensic_nextInjection;
            } elseif (isset($InjectionDue->next_injection->forensic_adjust)) {
                $ForensicDetails = $InjectionDue->next_injection->forensic_adjust;
                //now mark the adjust as deleted
                InjAdjust::find($ForensicDetails->injection_adjust_id)->markDeleted($this->RequestOptions);
            } else {
                $ForensicDetails = null;
            }
            $Object = self::addForensicDetails($ForensicDetails, $CompoundRequested, $Object);

            if (!Injection::isDue($InjectionDue->next_injection, $data) ||
                !Injection::isPredictedDose($InjectionDue->next_injection, $data) ||
                !Injection::isPredictedDilution($InjectionDue->next_injection, $CompoundRequested->dilution) ||
                $this::isAsk($InjectionDue)
            ) {
                $Object = self::generateAdjust($Object, $CompoundRequested, $request, $this->RequestOptions->user_id);
            }

            if (Injection::isLate($InjectionDue->next_injection, $data)) {
                $Patient = $CompoundRequested->prescription->patient;
                $Count = (int) $Patient->numLateInjections + 1;
                $Patient->numLateInjections = $Count;
                $Patient->save();
            }
        }
        return $Object;
    }

    private static function isAsk($InjectionDue)
    {
        $numericDose = is_numeric($InjectionDue->next_injection->dose);
        $numericDilution = is_numeric($InjectionDue->next_injection->dilution);
        if ($numericDilution && $numericDose) {
            return false; //if both numeric neither are ask
        }
        if (!$numericDose && $numericDilution) {
            return strtoupper($InjectionDue->next_injection->dose) === 'ASK';
        }
        if (!$numericDilution && $numericDose) {
            return strtoupper($InjectionDue->next_injection->dilution) === 'ASK';
        }
        if (!$numericDilution && !$numericDilution) {
            return (strtoupper($InjectionDue->next_injection->dilution) === 'ASK' && strtoupper($InjectionDue->next_injection->dose) === 'ASK');
        }
        //should never get here but if we do... it certainly wasnt ask
        return false;
    }

    private static function addForensicDetails($ForensicDetails, $CompoundRequested, $Object)
    {
        //Set attributes required for injection that aren't provided by user
        if (isset($ForensicDetails->tpStep)) {
            $Object->tpdetails_id = $ForensicDetails->tpStep->treatPlanDetails_id;
            $Object->tp_step = $ForensicDetails->tpStep->step;
            $Object->predicted_tpdetails_id = $ForensicDetails->tpStep->treatPlanDetails_id;
        }
        //Even if its null or -1, this was the tp on the rx at the time of injectin so we need to record it
        $Object->treatment_plan_id = $CompoundRequested->prescription->treatment_plan_id;
        //Was it a dose rules adjustment?
        if (isset($ForensicDetails->DoseAdjustDetails)) {
            $Object->is_rule_adjust = $ForensicDetails->DoseAdjustDetails->StepsAdjusted == 1 ? 'F' : 'T';
        } else {
            $Object->is_rule_adjust = 'F';
        }
        if (isset($ForensicDetails->injection_adjust_id)) {
            $Object->inj_adjust_id = $ForensicDetails->injection_adjust_id;
        }
        return $Object;
    }

    private static function generateAdjust($Object, $CompoundRequested, $request, $user_id)
    {
        //Something was different from predicted so we need to save an injAdjust
        $Adjust = new InjAdjust();
        $Adjust->dose = $Object['dose'];
        $Adjust->dilution = $CompoundRequested->dilution;
        $Adjust->reason = 'Auto generated adjust at injection time';
        $Adjust->date = Carbon::now()->format('n/j/Y');
        ;
        $Adjust->deleted = 'T'; //this will be immediately applied
        if (!is_null($request->input('override_non_xis_injection'))) {
            $Adjust->override_non_xis_injection = $request->input('override_non_xis_injection');
        }

        $fakeRequest = Request::create('/v1/patient/'. $CompoundRequested->prescription->patient_id.'/prescription/'.$CompoundRequested->rx_id.'/injectionadjust', 'POST', $Adjust->toArray());
        $data = new \Symfony\Component\HttpFoundation\ParameterBag;
        $data->add($Adjust->toArray());
        $fakeRequest->setJson($data);

        $InjAdjustController = new InjectionAdjustController();
        $result = $InjAdjustController->createInjectionAdjust($fakeRequest, $CompoundRequested->prescription->patient_id, $CompoundRequested->prescription->prescription_id, $user_id);

        if ($result->status() != 200) {
            return null;
        }

        $Adjust = $result->getData();

        //Even if it was a dose rules adjustment, its been overridden with a manual adjustment
        $Object->is_rule_adjust = 'F';
        $Object->inj_adjust_id = $Adjust->injection_adjust_id;

        $Dose = $Adjust->dose;
        $Dilution = $Adjust->dilution;
        $TPid = $CompoundRequested->prescription->treatment_plan_id;
        $TPstep = TreatPlanDetails::where('treatment_plan_id', $TPid)
            ->where('dilution', $Dilution)
            ->where('dose', $Dose)
            ->first();
        if (!is_null($TPstep)) {
            //We found a matching step so record its details
            $Object->tpdetails_id = $TPstep->treatPlanDetails_id;
            $Object->tp_step = $TPstep->step;
        }
        return $Object;
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);
        // See if we are giving the recommended injection... if not its already been validated and they
        // have submitted the required overrides, so save an injection adjust to link to.
        // Then for any injection, if its a manual adjust, create proper links, lookup tp step etc.
        DB::transaction(function () use ($request, $Object) {
            $Object = $this::getInjectionDetails($request, $Object);
            if (is_null($Object)) {
                //there was an error generating details (most likely creating an inj adjust)
                return response()->json('Error creating injection, if forcing injection details. Please try making a manual adjustment first.', 500);
            }
            $Object->save();
        });

        //If we are saving something with a null id it must have been a create
        //so use the objects primary key to find the created id.
        $primaryId = is_null($this->RequestOptions->id) ? $Object[$Object->getKeyName()] : $this->RequestOptions->id;

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($primaryId);
        return $this->finishAndFilter($newObject);
    }

    protected function querySearchModifier($Query)
    {
        $RxId = $this->RequestOptions->request->input('prescription_id');
        if (!is_null($RxId)) {
            $Query = $Query->whereHas('compound', function ($innerQuery) use ($RxId) {
                $innerQuery->where('rx_id', $RxId);
            });
        }
        return $Query;
    }
}

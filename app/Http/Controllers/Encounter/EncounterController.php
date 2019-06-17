<?php

namespace App\Http\Controllers\Encounter;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Models\Encounter;
use App\Models\Patient;
use Carbon\Carbon;
use DB;

class EncounterController extends Controller
{
    protected $ValidationErrors;
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Encounter"},
    *     path="/patient/{patient_id}/encounter",
    *     summary="Returns the current encounter object.",
    *     description="",
    *     operationId="api.Encounter.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose encounter should be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Encounter object fields to return.",
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
        return $this::handleRequest($request, new Encounter);
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Encounter"},
    *     path="/patient/{patient_id}/encounter/{id}",
    *     summary="Returns a single encounter in the system identified by {id}.",
    *     description="",
    *     operationId="api.encounter.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose encounter should be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the encounter to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Encounter object fields to return.",
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
    public function getEncounter(request $request)
    {
        return $this::handleRequest($request, new Encounter);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"Encounter"},
    *     path="/patient/{patient_id}/encounter",
    *     summary="Create a new encounter.",
    *     description="",
    *     operationId="api.encounter.createEncounter",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose encounter should be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="Encounter object to be created in the system. (The encounter_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Encounter"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Encounter object fields to return",
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
    public function createEncounter(Request $request)
    {
        return $this::handleRequest($request, new Encounter);
    }

    /**
    * Delete an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Delete(
    *     tags={"Encounter"},
    *     path="/patient/{patient_id}/encounter/{id}",
    *     summary="Mark a encounter as deleted.",
    *     description="",
    *     operationId="api.encounter.deleteEncounter",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the encounter to mark deleted.",
    *        required=true,
    *        type="integer",
    *      ),
    *      @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose encounter should be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Encounter object fields to return.",
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
    public function deleteEncounter(request $request)
    {
        //Really, deleting an encounter consists of updating an encounter state to logged_out so
        //just translate this into an update request.
        $this::getRequestOptions($request);

        $UpdateEncounterObj = new Encounter();
        $UpdateEncounterObj->state = 'logged_out';
        $UpdateEncounterObj->timeOut = Carbon::now()->toDateTimeString();

        $fakeRequest = Request::create('/v1/patient/' . $this->RequestOptions->patient_id . '/encounter/' . $this->RequestOptions->id, 'PUT', $UpdateEncounterObj->toArray());

        $data = new \Symfony\Component\HttpFoundation\ParameterBag;
        $data->add($UpdateEncounterObj->toArray());
        $fakeRequest->setJson($data);

        return $this->updateEncounter($fakeRequest);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Encounter"},
     *     path="/patient/{patient_id}/encounter/{encounter_id}",
     *     summary="Update a encounter object.",
     *     description="",
     *     operationId="api.encounter.updateEncounter",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Encounters are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *         name="encounter_id",
     *         in="path",
     *         description="The id of the encounter to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="login object",
     *        in="body",
     *        description="Encounter object containing only the fields that need to be updated. (The login_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Encounter"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Encounter object fields to return.",
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
    public function updateEncounter(Request $request)
    {
        return $this->handleRequest($request, new Encounter);
    }

    protected function validateAndSave(request $request, $Object = null)
    {
        // patient id was provided in the url (and stored in request options) but if we merge it into
        // the body data, then we can check it in the validator rather than manually.
        $request->merge(
            array(
                'patient_id' => $this->RequestOptions->patient_id
            )
        );

        $request = $this->fixCreatedUpdatedInfo($request);

        if ($Object->Validate($request->all(), $this->RequestOptions->id)) {
            //Passed basic validation (we have good data) but we still need to validate that what they
            //are trying to do is allowed for a create update call on encounter. This is the custom
            //encounter validation that ensures only allowed data is changed and that create only
            //creates if an existing encounter isn't open and update only updates an open encounter.

            //Check for an existing active encounter
            try {
                if ($this->RequestOptions->isCreate) {
                    // this if block doesn't make sense and needs to be
                    // checked in detail. I dont know why a patient_id of
                    // manualin would be used... this would be hitting
                    // POST /patient/manualin/encounter (might be valid)
                    // for login but I think it uses login endpoint
                    // then, even if we do get in the if... an encounter
                    // (login row) would never have a displayname of
                    // manualIn and archived=t, login doesn't even have those
                    // columns, that would be a query of patient table?
                    if (strtoupper($this->RequestOptions->patient_id) === 'MANUALIN') {
                        $ExistingEncounter = Encounter::where('displayname', 'manualIn')
                            ->where('archived', 'T')
                            ->firstOrFail();
                    } else {
                        $ExistingEncounter = Encounter::where('state', '<>', 'logged_out')
                            ->where('patient_id', $this->RequestOptions->patient_id)
                            ->orderBy('loginTime', 'desc')
                            ->firstOrFail();
                    }
                } else {
                    $ExistingEncounter = Encounter::where('state', '<>', 'logged_out')
                        ->findOrFail($this->RequestOptions->id);
                }
            } catch (ModelNotFoundException $e) {
                $ExistingEncounter = null;
            }

            if ($this->RequestOptions->isCreate) {
                if (!is_null($ExistingEncounter)) {
                    return response()->json(array('exists' => ['An existing encounter is already in progress for this patient. Reference encounter_id: ' . $ExistingEncounter->login_id]), 400);
                } else {
                    $Patient = Patient::find($this->RequestOptions->patient_id);
                    //create a new object where we can explicitly set the data we want saved
                    $ExistingEncounter = new Encounter;

                    $state = $request->json('state');
                    $clinicId = $request->json('clinic_id');
                    $name = $request->json('name');
                    $name = is_null($name) ? 'Manual login' : $name;

                    $name = strtoupper($Patient->displayname) === 'MANUALIN' ? $name : $Patient->displayname;

                    $ExistingEncounter->patient_id = $Patient->patient_id;
                    $ExistingEncounter->name = $name;
                    $ExistingEncounter->loginTime = Carbon::now()->toDateTimeString();
                    $ExistingEncounter->state = !is_null($state) ? $state : 'waiting_for_injection';
                    $ExistingEncounter->clinic_id = $clinicId;
                    $excuseTime = $request->json('excuse_time');
                    if (!is_null($excuseTime)) {
                        $ExistingEncounter->excuseTime = $excuseTime;
                    }
                    $timeOut = $request->json('scheduled_departure');
                    if (!is_null($timeOut)) {
                        $ExistingEncounter->timeOut = $timeOut;
                    }
                }
            } else { //if update
                if (is_null($ExistingEncounter)) {
                    return response()->json(array('exists' => ['The requested encounter is closed or does not exist.']), 400);
                } else {
                    $state = $request->json('state');
                    $excuseTime = $request->json('excuse_time');
                    $timeLeft = $request->json('last_departure_attempt');
                    $timeOut = $request->json('scheduled_departure');

                    if ($request->json('state') === 'waiting_for_injection') {
                        // this handles the case where they had been moved to excuse
                        // but were moved back
                        $ExistingEncounter->timeOut = !is_null($timeOut) ? $timeOut : null;
                        $ExistingEncounter->excuseTime = !is_null($excuseTime) ? $excuseTime : null;
                    } else {
                        $ExistingEncounter->excuseTime = !is_null($excuseTime) ? $excuseTime : $ExistingEncounter->excuseTime;
                        $ExistingEncounter->timeOut = !is_null($timeOut) ? $timeOut : $ExistingEncounter->timeOut;
                    }

                    $ExistingEncounter->state = !is_null($state) ? $state : $ExistingEncounter->state;
                    $ExistingEncounter->timeLeft = !is_null($timeLeft) ? $timeLeft : $ExistingEncounter->timeLeft;
                }
            }

            //Pass an empty request to saveAndQuery instead of the real one so that data provided
            //in the request doesn't overwrite the ExistingEncounter data in case they tried to write
            //to a field we don't allow (example, changing the displayname)
            $newRequest = request::create('/', 'GET', array(), array(), array(), array(), '');
            return $Object = $this->saveAndQuery($newRequest, $ExistingEncounter);
        } else {
            return response()->json($Object->errors(), 400);
        }
    }

    protected function queryModifier($Query)
    {
        return $Query->where('patient_id', $this->RequestOptions->patient_id)
                    ->where('state', '!=', 'logged_out')
                    ->orderBy('loginTime', 'desc');
    }
}

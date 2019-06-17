<?php

namespace App\Http\Controllers\Vial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Compound;
use App\Models\Vial;
use DB;

class VialController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Vial"},
    *     path="/patient/{patient_id}/prescription/{prescription_id}/vial",
    *     summary="Returns a list of all vials in the system.",
    *     description="",
    *     operationId="api.vial.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *      @SWG\Parameter(
    *         name="prescription_id",
    *         in="path",
    *         description="The id of the prescription whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Vial object fields to return.",
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
        return $this::handleRequest($request, new Compound);
    }
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Vial"},
    *     path="/patient/{patient_id}/vial",
    *     summary="Returns a list of all vials in the system.",
    *     description="",
    *     operationId="api.vial.getAllPatientVials",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Vial object fields to return.",
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
    public function getAllPatientVials(request $request)
    {
        $this::getRequestOptions($request);
        $Prescriptions = Prescription::where('patient_id', $this->RequestOptions->patient_id)
            ->where('strikethrough', 'F')
            ->pluck('prescription_id'); //returns an array of prescription_ids

        $base_URL = '/v1/patient/' . $this->RequestOptions->patient_id;
        $VialArray = [];
        foreach ($Prescriptions as $RxID) {
            // for each prescription make a request to the single prescription endpoint
            $fakeRequest = Request::create($base_URL . '/prescription/' . $RxID . '/vial', 'GET');
            $result = $this->index($fakeRequest, $this->RequestOptions->patient_id, $RxID);
            $RxVials = $result->getData();
            $VialArray = array_merge($VialArray, $RxVials);
        }
        //No finish and filter because this goes through the finishAndFilter
        //as part of the getInjectionsDue method in the foreach loop above
        return response()->json($VialArray);
    }
    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Vial"},
    *     path="/patient/{patient_id}/prescription/{prescription_id}/vial/{id}",
    *     summary="Returns a single vial in the system identified by {id}.",
    *     description="",
    *     operationId="api.vial.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the vial to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *      @SWG\Parameter(
    *         name="prescription_id",
    *         in="path",
    *         description="The id of the prescription whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Vial object fields to return.",
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
    public function getVial(request $request)
    {
        return $this::handleRequest($request, new Compound);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Vial"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/vial/_search",
     *     summary="Returns a list of vials in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.vial.searchVial",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="vial object",
     *        in="body",
     *        description="Vial object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Compound"),
     *     ),
     *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *      @SWG\Parameter(
    *         name="prescription_id",
    *         in="path",
    *         description="The id of the prescription whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Vial object fields to return.",
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
    public function searchVial(request $request)
    {
        return $this::handleRequest($request, new Compound);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"Vial"},
    *     path="/patient/{patient_id}/prescription/{prescription_id}/vial/{id}/",
    *     summary="Update a vial object.",
    *     description="",
    *     operationId="api.vial.updateVial",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the vial to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *      @SWG\Parameter(
    *         name="prescription_id",
    *         in="path",
    *         description="The id of the prescription whose vials are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="vial object",
    *        in="body",
    *        description="Vial object containing to be updated. The only field that may be changed is active.",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Compound"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Vial object fields to return.",
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
    public function updateVial(Request $request)
    {
        $this::getRequestOptions($request);

        $Active = !is_null($request->input('active')) ? $request->input('active') : null;
        $TrayLoc = !is_null($request->input('tray_location')) ? $request->input('tray_location') : null;

        try {
            $dilution = Compound::findOrFail($this->RequestOptions->id)->dilution;
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }

        //replace all data in the request with an emtpy array so we don't accidentally let the user
        //update something they shouldn't
        $request->replace([]);
        if (!is_null($Active)) {
            $request->merge([
                'active' => $Active
            ]);
        }
        if (!is_null($TrayLoc)) {
            $request->merge([
                'tray_location' => $TrayLoc
            ]);
        }

        $request->merge([
            'prescription_id' => $this->RequestOptions->prescription_id, //used during validation
            'dilution' => $dilution //used during validation
        ]);

        return $this::handleRequest($request, new Compound);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"Vial"},
    *     path="/patient/{patient_id}/prescription/{prescription_id}/vial",
    *     summary="Update an array of vial objects.",
    *     description="",
    *     operationId="api.vial.updateMultivial",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose vials are to be updated.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *      @SWG\Parameter(
    *         name="prescription_id",
    *         in="path",
    *         description="The id of the prescription whose vials are to be updated.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="vial object",
    *        in="body",
    *        description="An array of vial objects to be updated. The only field that may be changed is active, vial_id is required in each object.",
    *        required=true,
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Compound")
    *        ),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Vial object fields to return.",
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
    public function updateMultivial(Request $request)
    {
        $this::getRequestOptions($request);
        $Compound = new Compound;
        //becasue we can only have one active vial per dilution pre rx
        //we need to do all deactivations before we can do the activations
        //I do this by iterating through a loop of F and T and only acting on
        //objects where active matches the current element of the loop
        $Vials = [];
        DB::transaction(function () use ($request, $Compound, &$Vials) {
            $Errors = [];
            foreach (['F','T'] as $ActiveState) {
                foreach ($request->all() as $Vial) {
                    if ($Vial === 'transaction_id') {
                        continue;
                    }
                    $Active = isset($Vial['active']) ? $Vial['active'] : null;
                    if ($Active !== $ActiveState) {
                        // if the current object isn't being changed to the
                        // state of whatever we are looking at (active=F first
                        // then a second pass for active=T) skip the rest of the
                        // loop iteration
                        continue;
                    }

                    $CompoundId = isset($Vial['vial_id']) ? $Vial['vial_id'] : -1;

                    try {
                        $dilution = Compound::findOrFail($CompoundId)->dilution;
                    } catch (ModelNotFoundException $e) {
                        return response()->json('Resource could not be located.', 404);
                    }
                    //replace all data in the request with an emtpy array so we don't accidentally let the user
                    //update something they shouldnt
                    $VialObj = [
                    'active' => $Active,
                    'vial_id' => $Vial['vial_id'],
                    'prescription_id' => $this->RequestOptions->prescription_id,
                    'dilution' => $dilution
                    ];

                    // -1 signals that this is a multiupdate to the validation handler so
                    // that it can require vial_id in each object.
                    if (!$Compound->Validate($VialObj, -1)) {
                        $ErrorId = isset($Vial['vial_id']) ? $Vial['vial_id'] : -1;
                        $Errors [$ErrorId] = $Compound->errors()->all();
                    } else {
                        // Find the original answer
                        try {
                            $Vial = Compound::whereHas('prescription', function ($Query) {
                                $Query->where('patient_id', $this->RequestOptions->patient_id);
                            })->findOrFail($VialObj['vial_id']);
                        } catch (ModelNotFoundException $e) {
                            return response()->json('The requested resource could not be located', 404);
                        }

                        // we only set active so we only need to do the update if there is
                        // an active property on the request
                        if (isset($VialObj['active'])) {
                            $Vial->active = $VialObj['active'];
                            $Vial->save();
                        }

                        array_push($Vials, $Vial);
                    }
                }
            }
            if (sizeof($Errors) > 0) {
                return response()->json($Errors, 400);
            }
        });

        return $this->finishAndFilter($Vials);
    }

    protected function queryWith($Query)
    {
        return $Query->with(['vials:compound_id,barcode,mixdate,outdate,traylocation','user:user_id,displayname', 'prescription:prescription_id,prescription_num']);
    }

    protected function queryWhere($Query)
    {
        return $Query->where('rx_id', $this->RequestOptions->prescription_id)
                ->whereHas('prescription', function ($query) {
                    $query->where('patient_id', $this->RequestOptions->patient_id);
                })
                ->where(function ($innerQuery) {
                    // real mixed bottles
                    $innerQuery->whereHas('vials', function ($query) {
                        $query->whereNotNull('mixdate')
                            ->where('postponed', 'F');
                    })
                    // non xps bottles
                    ->orWhere(function ($innerQuery2) {
                        $innerQuery2->whereHas('prescription', function ($innerQuery3) {
                            $innerQuery3->where('source', 'NON-XPS');
                        })->whereHas('vials', function ($innerQuery3) {
                            $innerQuery3->where('postponed', 'F');
                        });
                    });
                });
    }

    protected function finalize($Object)
    {
        $Object->level = $this->getLevel($Object);
        unset($Object->currVol);
        unset($Object->provider_config_id);
        unset($Object->timestamp);
        $User = $Object->user->displayname;
        unset($Object->user);
        unset($Object->user_id);
        $Object->user = $User;
        $Object->barcode = $Object->vials[0]->barcode;
        $Object->mix_date = $Object->vials[0]->mixdate;
        $Object->out_date = $Object->vials[0]->outdate;
        $Object->tray_location = $Object->vials[0]->traylocation;
        unset($Object->vials);
        $Object->prescription_number = $Object->prescription->prescription_num;
        unset($Object->prescription);

        return $Object;
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // set tray location if present
        $trayLoc = $request->input('tray_location');
        if (!is_null($trayLoc)) {
            $Vials = Vial::where('compound_id', $this->RequestOptions->vial_id)
                ->get();
            foreach ($Vials as $Vial) {
                $Vial->traylocation = $trayLoc;
                $Vial->save();
            }
        }
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);
        $query = $Object->save();

        //If we are saving something with a null id it must have been a create
        //so use the objects primary key to find the created id.
        $primaryId = is_null($this->RequestOptions->id) ? $Object[$Object->getKeyName()] : $this->RequestOptions->id;

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($primaryId);
        return $this->finishAndFilter($newObject);
    }

    private function getLevel($Object)
    {
        $CurrVol = $Object->currVol;
        $Size = (int) trim(str_replace("ml", "", strtolower($Object->size)));
        $levelInt = (int)($CurrVol / $Size * 100);
        $levelStr = strval($levelInt) . '%';
        return $levelStr;
    }
}

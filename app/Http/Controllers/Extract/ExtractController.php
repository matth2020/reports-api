<?php

namespace App\Http\Controllers\Extract;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antigen_Extract;
use App\Models\Antigen;
use App\Models\Extract;
use App\Models\Inventory;
use Carbon\Carbon;
use DB;

class ExtractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Extract"},
     *     path="/extract",
     *     summary="Returns a list of all extracts in the system.",
     *     description="",
     *     operationId="api.extract.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Extract object fields to return.",
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
        return $this::handleRequest($request, new Antigen_Extract());
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Extract"},
     *     path="/profile/{profile_id}/extract",
     *     summary="Returns a list of all extracts in the system.",
     *     description="",
     *     operationId="api.extract.provider.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="profile_id",
     *        in="path",
     *        description="profile_id to return dosing for.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Extract object fields to return.",
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
    public function getAllProfileExtracts(request $request)
    {
        return $this::handleRequest($request, new Antigen_Extract());
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Extract"},
     *     path="/extract/{id}",
     *     summary="Returns a single extract in the system identified by {id}.",
     *     description="",
     *     operationId="api.extract.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the extract to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Extract object fields to return.",
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
    public function getExtract(request $request)
    {
        return $this::handleRequest($request, new Antigen_Extract());
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Extract"},
     *     path="/profile/{profile_id}/extract/{id}",
     *     summary="Returns a single extract in the system identified by {id} with provider dosing.",
     *     description="",
     *     operationId="api.extract.index.provider.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the extract to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="profile_id",
     *        in="path",
     *        description="profile_id of the dosing to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Extract object fields to return.",
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
    public function getProfileExtract(request $request)
    {
        return $this::handleRequest($request, new Antigen_Extract());
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Extract"},
     *     path="/extract/_search",
     *     summary="Returns a list of extracts in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.extract.searchExtract",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="extract object",
     *        in="body",
     *        description="Extract object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Extract"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Extract object fields to return.",
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
    public function searchExtract(request $request)
    {
        $Search = $this::handleRequest($request, new Antigen_Extract());
        $Data = [];
        if ($Search->status() == 200) {
            $Data = array_merge($Data, $Search->getData());
        } else {
            return $Search;
        }
        return response()->json($Data);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Extract"},
     *     path="/extract",
     *     summary="Create a new extract.",
     *     description="",
     *     operationId="api.extract.createExtract",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Extract object to be created in the system. (The extract_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Extract"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Extract object fields to return",
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
    public function createExtract(Request $request)
    {
        return $this::handleRequest($request, new Antigen_Extract());
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Extract"},
     *     path="/extract/{id}",
     *     summary="Mark a extract as deleted.",
     *     description="",
     *     operationId="api.extract.deleteExtract",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the extract to mark deleted.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Extract object fields to return.",
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
    public function deleteExtract(request $request)
    {
        return $this::handleRequest($request, new Antigen_Extract());
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Extract"},
     *     path="/extract/{id}",
     *     summary="Update a extract object.",
     *     description="",
     *     operationId="api.extract.updateExtract",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the extract to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="extract object",
     *        in="body",
     *        description="Extract object containing only the fields that need to be updated. (The extract_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Extract"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Extract object fields to return.",
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
    public function updateExtract(Request $request)
    {
        return $this::handleRequest($request, new Antigen_Extract());
    }

    protected function queryWith($Query)
    {
        $Query->with([
            'extract.unitType', 'compatibilityClass.incompatibleClasses1', 'compatibilityClass.incompatibleClasses2',
        ]);

        if (isset($this->RequestOptions->profile_id)) {
            $Query->whereHas('providerDefs', function ($innerQuery) {
                $innerQuery->where('deleted', 'F');
            })->with(['providerDefs'=> function ($innerQuery) {
                $innerQuery->where('provider_config_id', $this->RequestOptions->profile_id)
                        ->where('deleted', 'F');
            }]);
        }

        return $Query;
    }

    protected function saveAndQuery(request $request, $Object)
    {
        $extractId = 0;
        // because of the possible test_order shift, we will do this in a transaction
        DB::transaction(function () use ($request, $Object, &$extractId) {
            // Before doing the save... we need to search for any antigens that have test_order
            // greater than or equal to the new order and update them +1. This is only done
            // during create or if test_order is being changed. If test order is decreased
            // the old test_order value will leave a "hole" that needs to be filled.
            $RequestedTestOrder = $request->input('test_order');
            if (!is_null($RequestedTestOrder) && $RequestedTestOrder != $Object->test_order) {
                if ($this->RequestOptions->isCreate) {
                    //no holes to worry about just standard shift
                    $AntigensToShift = Antigen::where('test_order', '>=', $RequestedTestOrder)
                        ->where('deleted', 'F')
                        ->get();
                    $AdjustBy = 1;
                } elseif ($RequestedTestOrder < $Object->test_order) {
                    //moving up in order so shift everything greater to the point of the hole
                    $AntigensToShift = Antigen::where('test_order', '>=', $RequestedTestOrder)
                        ->where('test_order', '<=', $Object->test_order)
                        ->where('extract_id', '!=', $Object->extract_id)
                        ->where('deleted', 'F')
                        ->get();
                    $AdjustBy = 1;
                } else {
                    //moving down in order
                    $AntigensToShift = Antigen::where('test_order', '>=', $Object->test_order)
                        ->where('test_order', '<=', $RequestedTestOrder)
                        ->where('extract_id', '!=', $Object->extract_id)
                        ->where('deleted', 'F')
                        ->get();
                    $AdjustBy = -1;
                }
                // now loop through and apply adjusted to all selected.
                foreach ($AntigensToShift as $Antigen) {
                    $Antigen->test_order = $Antigen->test_order + $AdjustBy;
                    $Antigen->save();
                }
            }

            // Convert the property names to match db and save the extract and antigen

            if ($this->RequestOptions->isCreate) {
                $Extract = $this::APItoDB($request, new Extract());
                $Antigen = $this::APItoDB($request, new Antigen());

                $Extract->save();

                $extractId = $Extract->extract_id;

                $Antigen->extract_id = $extractId;

                $Antigen->save();
            } else {
                $extractId = $Object->extract_id;
                $currentExtract = Extract::find($extractId);
                $currentAntigen = Antigen::where(['extract_id' => $extractId])->firstOrFail();

                $Extract = $this::APItoDB($request, $currentExtract);
                $Antigen = $this::APItoDB($request, $currentAntigen);

                $Extract->update();
                $Antigen->update();
            }
        });

        // now read back what we saved
        $newObject = Antigen_Extract::find($extractId);

        return $this->finishAndFilter($newObject);
    }

    protected function fixWriteRequest(Request $request)
    {
        $Units = $request->input('units');
        if (!is_null($Units) && isset($Units['units_id'])) {
            $request->merge(['units' => $Units['units_id']]);
        }
        $Class = $request->input('compatibility_class');
        if (!is_null($Class) && isset($Class['compatibility_class_id'])) {
            $request->merge(['compatibility_class_id' => $Class['compatibility_class_id']]);
        }
    }

    protected function deleteFromRequest($Object)
    {
        try {
            $Query = $this->queryWith($Object);
            $Query = $this->queryWhere($Query);
            $Object = $Query->findOrFail($this->RequestOptions->id);
            // save shenanigans as above... the deleted antigen will create a hole
            // in test_order so we need to shift everything to fill it
            DB::transaction(function () use ($Object) {
                $AntigensToShift = Antigen::where('test_order', '>=', $Object['test_order'])
                        ->where('deleted', 'F')
                        ->where('extract_id', '!=', $this->RequestOptions->id)
                        ->get();
                $AdjustBy = -1;
                // now loop through and apply adjusted to all selected.
                foreach ($AntigensToShift as $Antigen) {
                    $Antigen->test_order = $Antigen->test_order + $AdjustBy;
                    $Antigen->save();
                }

                $Extract = Extract::where('extract_id', $this->RequestOptions->extract_id)->where('deleted', 'F')->get()->first();
                $Extract->markDeleted ($this->RequestOptions);

                $Antigen = Antigen::where('extract_id', $this->RequestOptions->extract_id)->where('deleted', 'F')->get()->first();
                $Antigen->markDeleted ($this->RequestOptions);

                // now deleted associated inventory
                $Inventory = Inventory::where('extract_id', $Object->extract_id)->where('deleted', 'F')->get();
                foreach ($Inventory as $Item) {
                    $Item->change_reason = 'Auto-deletion due to deletion of extract_id='.$Object->extract_id;
                    $Item->remove_time = Carbon::now()->toDateTimeString();
                    $Item->remove_by = $this->RequestOptions->user_id;
                    $Item->save();
                }
            });

            $Object = $Query->findOrFail($this->RequestOptions->id);

            return $this->finishAndFilter($Object);
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }
    }

    protected function finalize($Object)
    {
        if (isset($Object->compatibilityClass)) {
            $Object->compatibilityClass->incompatibleClasses();
            if (isset($Object->compatibilityClass->incompatibleClasses)) {
                foreach ($Object->compatibilityClass->incompatibleClasses as $class) {
                    unset($class->class_id_2);
                }
            }
        }

        if (!isset($Object->extract_id)) {
            $Extract = $this::APItoDB(new Request(['name' => $Object->name, 'compatibility_class_id' => $Object->compatibility_class_id]), new Extract());
            $currentAntigen = Antigen::where(['antigen_id' => $Object->antigen_id])->firstOrFail();
            $Antigen = $this::APItoDB(new Request(), $currentAntigen);

            $Extract->save();

            $extractId = $Extract->extract_id;

            $Object->extract_id = $extractId;

            $Antigen->extract_id = $extractId;
            $Antigen->for_test_only = 'T';

            $Antigen->update();
        }

        unset ($Object->antigen_id);

        if (isset($Object->units)) {
            $Object->units = ['units_id' => $Object->units];
        }

        if (isset($this->RequestOptions->profile_id)) {
            $dose = $Object->providerDefs->where('provider_config_id', $this->RequestOptions->profile_id)->first()['dose'];
            if (isset($dose)) {
                $Object->default_dose = $dose;
                $Object->profile_id = $this->RequestOptions->profile_id;
            }
        }
        if (isset($Object->unitType)) {
            $Object->units = $Object->unitType;
        }

        unset($Object->antigen_id);
        unset($Object->compatibility_class_id);

        return $Object;
    }
}

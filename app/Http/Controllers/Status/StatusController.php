<?php

namespace App\Http\Controllers\Status;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderStatus;
use App\Models\TreatmentSetStatus;
use App\Models\AccountStatus;

class StatusController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Status"},
    *     path="/status",
    *     summary="Returns a list of all status in the system.",
    *     description="",
    *     operationId="api.status.indexAll",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Status object fields to return.",
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
    public function indexAll(request $request)
    {
        $response = $this->index($request, 'purchase_order');
        $Data = $response->getData();
        $response2 = $this->index($request, 'treatment_set');
        $Data = array_merge($Data, $response2->getData());
        $response3 = $this->index($request, 'account');
        $Data = array_merge($Data, $response3->getData());
        return response()->json($Data);
    }
    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Status"},
     *     path="/status/_search",
     *     summary="Returns a list of statuss in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.status.searchAllStatus",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="status object",
     *        in="body",
     *        description="Status object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Status"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Status object fields to return.",
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
    public function searchAllStatus(request $request)
    {
        switch ($request->input('type')) {
            case "treatment_set":
                return $this::handleRequest($request, new TreatmentSetStatus);
            case "purchase_order":
                return $this::handleRequest($request, new PurchaseOrderStatus);
            case "account":
                return $this::handleRequest($request, new AccountStatus);
            case null:
                $response = $this->index($request, 'purchase_order');
                $Data = $response->getData();
                $response2 = $this->index($request, 'treatment_set');
                $Data = array_merge($Data, $response2->getData());
                $response3 = $this->index($request, 'account');
                $Data = array_merge($Data, $response3->getData());
                return response()->json($Data);
            default:
                return response()->json('The requested status type could not be found.', 404);
        }
    }
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Status"},
    *     path="/{type}/status",
    *     summary="Returns a list of all status in the system.",
    *     description="",
    *     operationId="api.status.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="type",
    *        in="path",
    *        description="type of status to return.",
    *        required=true,
    *        type="string",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Status object fields to return.",
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
    public function index(request $request, $type = null)
    {
        switch ($type) {
            case "treatment_set":
                return $this::handleRequest($request, new TreatmentSetStatus);
            case "purchase_order":
                return $this::handleRequest($request, new PurchaseOrderStatus);
            case "account":
                return $this::handleRequest($request, new AccountStatus);
            default:
                return response()->json('The requested status type could not be found.', 404);
        }
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Status"},
    *     path="/{type}/status/{id}",
    *     summary="Returns a single status in the system identified by {id}.",
    *     description="",
    *     operationId="api.status.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the status to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="type",
    *        in="path",
    *        description="type of status to return.",
    *        required=true,
    *        type="string",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Status object fields to return.",
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
    public function getStatus(request $request, $type = null)
    {
        switch ($type) {
            case "treatment_set":
                return $this::handleRequest($request, new TreatmentSetStatus);
            case "purchase_order":
                return $this::handleRequest($request, new PurchaseOrderStatus);
            case "account":
                return $this::handleRequest($request, new AccountStatus);
            default:
                return response()->json('The requested status type could not be found.', 404);
        }
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Status"},
     *     path="/{type}/status/_search",
     *     summary="Returns a list of statuss in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.status.searchStatus",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="status object",
     *        in="body",
     *        description="Status object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Status"),
     *     ),
     *     @SWG\Parameter(
    *        name="type",
    *        in="path",
    *        description="type of status to return.",
    *        required=true,
    *        type="string",
    *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Status object fields to return.",
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
    public function searchStatus(request $request, $type = null)
    {
        switch ($type) {
            case "treatment_set":
                return $this::handleRequest($request, new TreatmentSetStatus);
            case "purchase_order":
                return $this::handleRequest($request, new PurchaseOrderStatus);
            case "account":
                return $this::handleRequest($request, new AccountStatus);
            default:
                return response()->json('The requested status type could not be found.', 404);
        }
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"Status"},
    *     path="/{type}/status",
    *     summary="Create a new status.",
    *     description="",
    *     operationId="api.status.createStatus",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="type",
    *        in="path",
    *        description="type of status to return.",
    *        required=true,
    *        type="string",
    *     ),
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="Status object to be created in the system. (The status_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Status"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Status object fields to return",
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
    public function createStatus(Request $request, $type = null)
    {
        switch ($type) {
            case "treatment_set":
                return $this::handleRequest($request, new TreatmentSetStatus);
            case "purchase_order":
                return $this::handleRequest($request, new PurchaseOrderStatus);
            case "account":
                return $this::handleRequest($request, new AccountStatus);
            default:
                return response()->json('The requested status type could not be found.', 404);
        }
    }

    /**
    * Delete an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Delete(
    *     tags={"Status"},
    *     path="/{type}/status/{id}",
    *     summary="Mark a status as deleted.",
    *     description="",
    *     operationId="api.status.deleteStatus",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the status to mark deleted.",
    *        required=true,
    *        type="integer",
    *      ),
    *      @SWG\Parameter(
    *        name="type",
    *        in="path",
    *        description="type of status to return.",
    *        required=true,
    *        type="string",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Status object fields to return.",
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
    public function deleteStatus(request $request, $type = null)
    {
        switch ($type) {
            case "treatment_set":
                return $this::handleRequest($request, new TreatmentSetStatus);
            case "purchase_order":
                return $this::handleRequest($request, new PurchaseOrderStatus);
            case "account":
                return $this::handleRequest($request, new AccountStatus);
            default:
                return response()->json('The requested status type could not be found.', 404);
        }
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"Status"},
    *     path="/{type}/status/{id}",
    *     summary="Update a status object.",
    *     description="",
    *     operationId="api.status.updateStatus",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the status to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="type",
    *        in="path",
    *        description="type of status to return.",
    *        required=true,
    *        type="string",
    *     ),
    *     @SWG\Parameter(
    *        name="status object",
    *        in="body",
    *        description="Status object containing only the fields that need to be updated. (The status_id property cannot be updated and will be ignored)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Status"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Status object fields to return.",
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
    public function updateStatus(Request $request, $type = null)
    {
        switch ($type) {
            case "treatment_set":
                return $this::handleRequest($request, new TreatmentSetStatus);
            case "purchase_order":
                return $this::handleRequest($request, new PurchaseOrderStatus);
            case "account":
                return $this::handleRequest($request, new AccountStatus);
            default:
                return response()->json('The requested status type could not be found.', 404);
        }
    }

    protected function queryModifier($Query)
    {
        return $Query->orderBy('position', 'asc');
    }

    protected function finalize($Object)
    {
        if ($Object instanceof TreatmentSetStatus) {
            $Object->type='treatment_set';
        } elseif ($Object instanceof PurchaseOrderStatus) {
            $Object->type='purchase_order';
        } elseif ($Object instanceof AccountStatus) {
            $Object->type='account';
        }
        return $Object;
    }

    protected function handleStatusRequest($request, $type)
    {
        $this->getRequestOptions($request);
        switch ($type) {
            case "treatment_set":
                try {
                    $Count = TreatmentSetStatus::findOrFail($this->RequestOptions->id)->treatmentSets->count();
                    if ($Count > 0) {
                        return response()->json(['The selected status cannot be altered because it is assigned to '.$Count.' '.$type.'s'], 400);
                    }
                } catch (ModelNotFoundException $e) {
                    //do nothing, this will get caught in handle request
                }
                return $this::handleRequest($request, new TreatmentSetStatus);
            case "purchase_order":
                try {
                    $Count = PurchaseOrderStatus::findOrFail($this->RequestOptions->id)->purchaseOrders->count();
                    if ($Count > 0) {
                        return response()->json(['The selected status cannot be altered because it is assigned to '.$Count.' '.$type.'s'], 400);
                    }
                } catch (ModelNotFoundException $e) {
                    //do nothing, this will get caught in handle request
                }
                return $this::handleRequest($request, new PurchaseOrderStatus);
            case "account":
                try {
                    $Count = AccountStatus::findOrFail($this->RequestOptions->id)->purchaseOrders->count();
                    if ($Count > 0) {
                        return response()->json(['The selected status cannot be altered because it is assigned to '.$Count.' '.$type.'s'], 400);
                    }
                } catch (ModelNotFoundException $e) {
                    //do nothing, this will get caught in handle request
                }
                return $this::handleRequest($request, new AccountStatus);
            default:
                return response()->json('The requested status type could not be found.', 404);
        }
    }
}

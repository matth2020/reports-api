<?php

namespace App\Http\Controllers\Injection;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\LockableController;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\InjAdjust;
use Carbon\Carbon;

class InjectionAdjustController extends LockableController
{
    public static $requiredLocks = ['injectionLock'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"InjectionAdjust"},
     *     path="/patient/{patient_id}/injection_adjust",
     *     summary="Returns a list of all InjectionAdjusts that apply to a given patient.",
     *     description="",
     *     operationId="api.InjectionAdjust.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionAdjust object fields to return.",
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
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\JsonResponse
      *
      * @SWG\Get(
      *     tags={"InjectionAdjust"},
      *     path="/patient/{patient_id}/prescription/{prescription_id}/injection_adjust",
      *     summary="Returns a list of all InjectionAdjusts that apply to a given patient and prescription.",
      *     description="",
      *     operationId="api.InjectionAdjust.index",
      *     produces={
      *        "application/json"
      *     },
      *     consumes={
      *        "application/json"
      *     },
      *     @SWG\Parameter(
      *         name="patient_id",
      *         in="path",
      *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
      *         required=true,
      *         type="integer",
      *         format="int32"
      *      ),
      *     @SWG\Parameter(
      *         name="prescription_id",
      *         in="path",
      *         description="The id of the prescription to InjectionAdjusts are to be viewed.",
      *         required=true,
      *         type="integer",
      *         format="int32"
      *      ),
      *     @SWG\Parameter(
      *        name="fields",
      *        in="query",
      *        description="InjectionAdjust object fields to return.",
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
        return $this->handleRequest($request, new InjAdjust);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"InjectionAdjust"},
     *     path="/patient/{patient_id}/injection_adjust/{id}",
     *     summary="Returns a single InjectionAdjust in the system identified by {id}.",
     *     description="",
     *     operationId="api.InjectionAdjust.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the InjectionAdjust to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionAdjust object fields to return.",
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
    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"InjectionAdjust"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/injection_adjust/{id}",
     *     summary="Returns a single InjectionAdjust in the system identified by {id}.",
     *     description="",
     *     operationId="api.InjectionAdjust.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription to view InjectionAdjusts for.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the InjectionAdjust to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionAdjust object fields to return.",
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
    public function getInjectionAdjust(request $request)
    {
        $this::getRequestOptions($request);
        $this->RequestOptions->injection_adjust_id = $this->RequestOptions->id;
        return $this->handleRequest($request, new InjAdjust);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"InjectionAdjust"},
     *     path="/patient/{patient_id}/injection_adjust/_search",
     *     summary="Returns a list InjectionAdjusts in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.InjectionAdjust.searchInjectionAdjust",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="InjectionAdjust object",
     *        in="body",
     *        description="InjectionAdjust object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/InjAdjust"),
     *     ),
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionAdjust object fields to return.",
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
    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"InjectionAdjust"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/injection_adjust/_search",
     *     summary="Returns a list InjectionAdjusts in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.InjectionAdjust.searchInjectionAdjust",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="InjectionAdjust object",
     *        in="body",
     *        description="InjectionAdjust object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/InjAdjust"),
     *     ),
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionAdjust object fields to return.",
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
    public function searchInjectionAdjust(request $request)
    {
        return $this->handleRequest($request, new InjAdjust);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"InjectionAdjust"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/injection_adjust",
     *     summary="Create a new injectionadjust.",
     *     description="",
     *     operationId="api.injectionadjust.createInjectionAdjust",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="InjectionAdjust object to be created in the system. (The injectionadjust_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/InjAdjust"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionAdjust object fields to return",
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
    public function createInjectionAdjust(Request $request, $patient_id, $prescription_id, $user_id = null)
    {
        $this::getRequestOptions($request);
        //force the user_id to be the logged in user. Conditional arg is to allow passing of user_id
        //when this method is called from another methed (eg injectionController create)
        $user_id = !is_null($user_id) ? $user_id : $request->user()->user_id;
        $request->merge([
            'adjusted_by' => $user_id,
            'prescription_id' => $this->RequestOptions->prescription_id,
            'dilution' => $this->cleanDilutionData($request->input('dilution'))
        ]);

        return $this->handleRequest($request, new InjAdjust);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"InjectionAdjust"},
     *     path="/patient/{patient_id}/injection_adjust/{id}",
     *     summary="Mark a injectionadjust as deleted.",
     *     description="",
     *     operationId="api.injectionadjust.deleteInjectionAdjust",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the injectionadjust to mark deleted.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionAdjust object fields to return.",
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
    /**
      * Delete an object.
      *
      * @return \Illuminate\Http\JsonResponse
      *
      * @SWG\Delete(
      *     tags={"InjectionAdjust"},
      *     path="/patient/{patient_id}/prescription/{prescription}/injection_adjust/{id}",
      *     summary="Mark a injectionadjust as deleted.",
      *     description="",
      *     operationId="api.injectionadjust.deleteInjectionAdjust",
      *     produces={
      *        "application/json"
      *     },
      *     consumes={
      *        "application/json"
      *     },
      *     @SWG\Parameter(
      *         name="patient_id",
      *         in="path",
      *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
      *         required=true,
      *         type="integer",
      *         format="int32"
      *      ),
      *      @SWG\Parameter(
      *         name="prescription_id",
      *         in="path",
      *         description="The id of the prescription whose InjectionAdjusts are to be viewed.",
      *         required=true,
      *         type="integer",
      *         format="int32"
      *      ),
      *     @SWG\Parameter(
      *        name="id",
      *        in="path",
      *        description="Id of the injectionadjust to mark deleted.",
      *        required=true,
      *        type="integer",
      *      ),
      *     @SWG\Parameter(
      *        name="fields",
      *        in="query",
      *        description="InjectionAdjust object fields to return.",
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
    public function deleteInjectionAdjust(request $request)
    {
        $this::getRequestOptions($request);
        $this->RequestOptions->injection_adjust_id = $this->RequestOptions->id;
        return $this->handleRequest($request, new InjAdjust);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"InjectionAdjust"},
     *     path="/patient/{patient_id}/injection_adjust/{id}",
     *     summary="Update a injectionadjust object.",
     *     description="",
     *     operationId="api.injectionadjust.updateInjectionAdjust",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the injectionadjust to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="injectionadjust object",
     *        in="body",
     *        description="InjectionAdjust object containing only the fields that need to be updated. (The injectionadjust_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/InjAdjust"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="InjectionAdjust object fields to return.",
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
    /**
      * Update an object.
      *
      * @return \Illuminate\Http\JsonResponse
      *
      * @SWG\Put(
      *     tags={"InjectionAdjust"},
      *     path="/patient/{patient_id}/prescription/{prescription_id}/injection_adjust/{id}",
      *     summary="Update a injectionadjust object.",
      *     description="",
      *     operationId="api.injectionadjust.updateInjectionAdjust",
      *     produces={
      *        "application/json"
      *     },
      *     consumes={
      *        "application/json"
      *     },
      *     @SWG\Parameter(
      *         name="patient_id",
      *         in="path",
      *         description="The id of the patient whose InjectionAdjusts are to be viewed.",
      *         required=true,
      *         type="integer",
      *         format="int32"
      *      ),
      *      @SWG\Parameter(
      *         name="prescription_id",
      *         in="path",
      *         description="The id of the prescription whose InjectionAdjusts are to be viewed.",
      *         required=true,
      *         type="integer",
      *         format="int32"
      *      ),
      *     @SWG\Parameter(
      *        name="id",
      *        in="path",
      *        description="Id of the injectionadjust to update.",
      *        required=true,
      *        type="integer",
      *     ),
      *     @SWG\Parameter(
      *        name="injectionadjust object",
      *        in="body",
      *        description="InjectionAdjust object containing only the fields that need to be updated. (The injectionadjust_id property cannot be updated and will be ignored)",
      *        required=true,
      *        @SWG\Schema(ref="#/definitions/InjAdjust"),
      *     ),
      *     @SWG\Parameter(
      *        name="fields",
      *        in="query",
      *        description="InjectionAdjust object fields to return.",
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
    public function updateInjectionAdjust(Request $request)
    {
        $this::getRequestOptions($request);
        $this->RequestOptions->injection_adjust_id = $this->RequestOptions->id;
        $request->merge([
            'injection_adjust_id' => $this->RequestOptions->injection_adjust_id,
            'dilution' => $this->cleanDilutionData($request->input('dilution'))
        ]);
        return $this->handleRequest($request, new InjAdjust);
    }

    protected function queryWith($Query)
    {
        return $Query->with(['prescription:prescription_id,prescription_num', 'user:user_id,displayname']);
    }
    protected function queryWhere($Query)
    {
        $Query = $Query->whereHas('prescription', function ($query) {
            $query->where('patient_id', $this->RequestOptions->patient_id);
        });

        if (isset($this->RequestOptions->prescription_id)) {
            $Query->where('prescription_id', $this->RequestOptions->prescription_id);
        }

        return $Query;
    }
    protected function queryModifier($Query)
    {
        return $Query->where('deleted', 'F')
                ->orderBy('date', 'desc');
    }

    protected function cleanDilutionData($dilution)
    {
        $withoutRatio = str_replace('1:', '', $dilution);
        $withoutCommas = str_replace(',', '', $withoutRatio);
        return $withoutCommas;
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  InjectionAdjust $InjectionAdjust object returned from the database
     * @param  $Filter of properties to include in returned object
     * @param  $AllowedFields
     * @return InjectionAdjust object
     */
    protected function finalize($InjectionAdjust)
    {
        //replace the following belongsTo relationship objects with just the
        //object name.
        unset($InjectionAdjust->adjby); //adjby is a user_id and is used to link to the ->user relationship
        if (isset($InjectionAdjust->user)) {
            $InjectionAdjust->adjusted_by = $InjectionAdjust->user->displayname;
            unset($InjectionAdjust->user);
        }
        if (isset($InjectionAdjust->prescription)) {
            $InjectionAdjust->prescription_number = $InjectionAdjust->prescription->prescription_num;
            unset($InjectionAdjust->prescription);
        }

        return $InjectionAdjust;
    }
}

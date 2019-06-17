<?php

namespace App\Http\Controllers\DosingPlan;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Config\ConfigController;
use App\Http\Controllers\Controller;
use App\Models\DosingPlanDetails;
use App\Models\DosingPlanSet;
use Illuminate\Http\Request;
use App\Models\DosingPlan;
use App\Models\Config;
use DB;

class DosingPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"DosingPlan"},
     *     path="/dosing_plan",
     *     summary="Returns a list of all dosing plans in the system.",
     *     description="",
     *     operationId="api.dosingplan.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="DosingPlan object fields to return.",
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
        return $this::handleRequest($request, new DosingPlan);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"DosingPlan"},
     *     path="/dosing_plan/_search",
     *     summary="Returns a list dosing plans in the system matching the requested fields.",
     *     description="",
     *     operationId="api.dosingplan.searchDosingPlan",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="dosingplan object",
     *        in="body",
     *        description="DosingPlan object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/DosingPlan"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="DosingPlan object fields to return.",
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

    public function searchDosingPlan(Request $request)
    {
        return $this::handleRequest($request, new DosingPlan);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"DosingPlan"},
     *     path="/dosing_plan/{id}",
     *     summary="Returns a single dosingplan in the system identified by {id}.",
     *     description="",
     *     operationId="api.dosingplan.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the dosingplan item to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="DosingPlan object fields to return.",
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
    public function getDosingPlan(Request $request)
    {
        return $this::handleRequest($request, new DosingPlan);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"DosingPlan"},
     *     path="/dosing_plan",
     *     summary="Create a new dosingplan.",
     *     description="",
     *     operationId="api.dosingplan.createDosingPlan",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="dosingplan object",
     *        in="body",
     *        description="DosingPlan object to be created in the system. (The treatment_plan_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/DosingPlan"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="DosingPlan object fields to return.",
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
    public function createDosingPlan(Request $request)
    {
        return $this::handleRequest($request, new DosingPlan);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"DosingPlan"},
     *     path="/dosing_plan/{id}",
     *     summary="Mark a dosingplan as deleted.",
     *     description="",
     *     operationId="api.dosingplan.deleteDosingPlan",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the dosingplan item to delete.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="DosingPlan object fields to return.",
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
    public function deleteDosingPlan(request $request)
    {
        return $this::handleRequest($request, new DosingPlan);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"DosingPlan"},
     *     path="/dosing_plan/{id}",
     *     summary="Update a dosingplan object.",
     *     description="Install_date, install_by, remove_date, remove_by, barcode, and volume_current are managed by the system and cannot be altered via the API.",
     *     operationId="api.dosingplan.updateDosingPlan",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the dosingplan item to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="dosingplan object",
     *        in="body",
     *        description="DosingPlan object containing only the fields that need to be updated. (The extract_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/DosingPlan"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="DosingPlan object fields to return.",
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
    public function updateDosingPlan(Request $request)
    {
        return $this::handleRequest($request, new DosingPlan);
    }

    protected function queryWith($Query)
    {
        return $Query->with('DosingPlanDetails');
    }
    protected function queryModifier($Query)
    {
        return $Query
            ->where('deleted', 'F')
            ->whereHas('dosingPlanDetails', function ($query) {
                $query->orderBy('reactType', 'asc')
                    ->orderBy('reactVal', 'asc')
                    ->orderBy(DB::raw('CAST(start AS UNSIGNED)'), 'asc');
            });
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  Config $Contact object returned from the database
     * @param         $Filter  Array of properties the response objects should include
     * @return Contact object
     */
    protected function finalize($DosingPlan)
    {
        $DosingPlan = $this->organizePlans($DosingPlan);
        return $DosingPlan;
    }

    /**
     * Convert the dosing plan from the confusing way it is stored in the DB
     * to the way it is presented by the API
     * @param  DosingPlan $DosingPlan DosingPlan as returned by the DB
     * @return DosingPlan             DosingPlan organized as per API
     */
    public function organizePlans(DosingPlan $DosingPlan)
    {
        $DosingPlanDetails = $DosingPlan->DosingPlanDetails;
        unset($DosingPlan->DosingPlanDetails);
        $Plans = array();
        //create an of objects where each object is a plan and they
        //key is the reaction type and value.
        foreach ($DosingPlanDetails as $key => $Value) {
            //if an element exists for the reaction type and value,
            //add to it, otherwise create it
            if (($Value->end === 0 && $Value->start !== 0)||($Value->end==='Inf')) {
                $numDays = 0;
            } else {
                $numDays = $Value->end - $Value->start;
            }

            if (!array_key_exists($Value->reactType.$Value->reactVal, $Plans)) {
                $PlanSet = new DosingPlanSet();
                $PlanSet->reaction_type = $Value->reactType;
                $PlanSet->reaction_value = $Value->reactVal;

                //Current rules are stored one row per day. Old rules were stored with a start and end day. Using the for loop supports both.

                $adjustments = array();

                for ($days = 0; $days <= $numDays; $days++) {
                    array_push($adjustments, $Value->delta);
                }

                $PlanSet->adjustments = $adjustments;
                $Plans[$Value->reactType.$Value->reactVal] = $PlanSet;
            } else {
                $PlanSet = $Plans[$Value->reactType.$Value->reactVal];
                //Current rules are stored one row per day. Old rules were stored with a start and end day. Using the for loop supports both.
                $adjustments = $PlanSet->adjustments;

                for ($days = 0; $days <= $numDays; $days++) {
                    array_push($adjustments, $Value->delta);
                }

                $PlanSet->adjustments = $adjustments;
                $Plans[$Value->reactType.$Value->reactVal] = $PlanSet;
            }
        }
        //Create final (without funky key names) to return
        $FinalPlans = array();

        foreach ($Plans as $key => $value) {
            array_push($FinalPlans, $value);
        }

        $DosingPlan->plan = $FinalPlans;
        return $DosingPlan;
    }

    /**
     * @param Request $request
     * @param DosingPlan|null $DosingPlan
     * @return \App\Http\Controllers\model|\Illuminate\Http\JsonResponse
     */
    private function saveDosingPlan(request $request, dosingplan $DosingPlan = null)
    {
        $create = $this->RequestOptions->isCreate;

        $DosingPlan = is_null($DosingPlan) ? new DosingPlan : $DosingPlan;

        $DosingPlan = $this->APItoDB($request, $DosingPlan);

        $Plans = $request->json('plan');

        // return response()->json($this->OrganizePlans($DosingPlan->load('DosingPlanDetails')));

        //Everything checked out so assign all of the new values.
        //for each property, if the new value is null, use the existing value
        $DosingPlan->deleted = isset($request['deleted']) ? $DosingPlan->deleted : 'F';
        $Plans = !isset($request['plan']) ? $this->OrganizePlans($DosingPlan->load('DosingPlanDetails'))->plan : $Plans;
        $DosingPlanDetailRows = array();
        foreach ($Plans as $Index => $Plan) {
            $ReactionType = $Plan['reaction_type'];
            $ReactionValue = $Plan['reaction_value'];
            foreach ($Plan['adjustments'] as $Index => $Delta) {
                $DosingPlanDetails = new DosingPlanDetails();
                $DosingPlanDetails->end = $Index === count($Plan['adjustments']) - 1 ? 'Inf' : $Index;
                $DosingPlanDetails->delta = str_replace('+', '', $Delta);
                $DosingPlanDetails->reactVal = $ReactionValue;
                $DosingPlanDetails->reactType = $ReactionType;
                $DosingPlanDetails->start = $Index;
                //add the row to the of rows
                array_push($DosingPlanDetailRows, $DosingPlanDetails);
            }
        }

        //begin a transaction to ensure the entire plan saves properly or not
        //at all.
        DB::transaction(function () use ($DosingPlan, $DosingPlanDetailRows, $create) {
            //save the plan
            unset($DosingPlan->plan);
            $DosingPlan->save();
            //if there is an id, we are updating an existing so we need to
            //delete the old rows first
            if (!$create) {
                //delete all details rows for the plan to be updated before recreating them
                dosingPlanDetails::where('doseRuleNames_id', $DosingPlan->doseRuleNames_id)->delete();
                //now save the new details rows
            }

            foreach ($DosingPlanDetailRows as $DosingPlanDetails) {
                $DosingPlanDetails->doseRuleNames_id = $DosingPlan->doseRuleNames_id;
                //and save the row
                $DosingPlanDetails->save();
            }
        });
        try {
            $Object = new DosingPlan();
            $Query = $this->queryWith($Object);
            $newObject = $Query->findOrFail($DosingPlan->doseRuleNames_id);
            return $this::finishAndFilter($newObject);
        } catch (ModelNotFoundException $ex) {
            return response()->json('Resource could not be located.', 404);
        }
    }




    /**
     * Validate the dosing plan before saving
     * @param  request $request API request
     * @param      $id      ID of the dosing plan to save (null to create)
     * @return null
     */
    protected function validateAndSave(request $request, $Object = null)
    {
        $request = $this->fixCreatedUpdatedInfo($request);
        if ($Object->validate($request->all(), $this->RequestOptions->id)) {
            return $this->saveDosingPlan($request, $Object);
        } else {
            return response()->json($Object->errors(), 400);
        }
    }
}

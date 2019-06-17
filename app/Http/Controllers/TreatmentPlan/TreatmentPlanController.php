<?php

namespace App\Http\Controllers\TreatmentPlan;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\TreatPlanDetails;
use App\Models\TreatmentPlan;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\PlanBottle;
use App\Models\Injection;
use App\Models\PlanStep;
use Carbon\Carbon;
use DB;

class TreatmentPlanController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"TreatmentPlan"},
    *     path="/treatment_plan",
    *     summary="Returns a list of all treatmentplans in the system.",
    *     description="",
    *     operationId="api.treatmentplan.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="TreatmentPlan object fields to return.",
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
    *     @SWG\Parameter(
    *         name="sort",
    *         in="query",
    *         description="defines sort eg: (prop1:asc,prop2:asc,prop3:desc).",
    *         required=false,
    *         type="string",
    *         format="csv"
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
        return $this::handleRequest($request, new TreatmentPlan);
    }

    /**
    * Display a listing of the resource matching search criterion.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"TreatmentPlan"},
    *     path="/treatment_plan/_search",
    *     summary="Returns a list treatmentplans in the system matching the requested fields.",
    *     description="",
    *     operationId="api.treatmentplan.searchTreatmentPlan",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="treatmentplan object",
    *        in="body",
    *        description="TreatmentPlan object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/TreatmentPlan"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="TreatmentPlan object fields to return.",
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
    public function searchTreatmentPlan(Request $request)
    {
        return $this::handleRequest($request, new TreatmentPlan);
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"TreatmentPlan"},
    *     path="/treatment_plan/{id}",
    *     summary="Returns a single treatmentplan in the system identified by {id}.",
    *     description="",
    *     operationId="api.treatmentplan.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the treatmentplan item to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="TreatmentPlan object fields to return.",
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

    public function getTreatmentPlan(Request $request)
    {
        return $this::handleRequest($request, new TreatmentPlan);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"TreatmentPlan"},
    *     path="/treatment_plan",
    *     summary="Create a new treatmentplan.",
    *     description="",
    *     operationId="api.treatmentplan.createTreatmentPlan",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="treatmentplan object",
    *        in="body",
    *        description="TreatmentPlan object to be created in the system. (The treatment_plan_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/TreatmentPlanSwagObj"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="TreatmentPlan object fields to return.",
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
    public function createTreatmentPlan(Request $request)
    {
        return $this::handleRequest($request, new TreatmentPlan);
    }

    /**
    * Delete an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Delete(
    *     tags={"TreatmentPlan"},
    *     path="/treatment_plan/{id}",
    *     summary="Mark a treatmentplan as deleted.",
    *     description="",
    *     operationId="api.treatmentplan.deleteTreatmentPlan",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the treatmentplan item to delete.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="TreatmentPlan object fields to return.",
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

    public function deleteTreatmentPlan(request $request)
    {
        return $this::handleRequest($request, new TreatmentPlan);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"TreatmentPlan"},
    *     path="/treatment_plan/{id}",
    *     summary="Update a treatmentplan object.",
    *     description="Install_date, install_by, remove_date, remove_by, barcode, and volume_current are managed by the system and cannot be altered via the API.",
    *     operationId="api.treatmentplan.updateTreatmentPlan",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the treatmentplan item to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="treatmentplan object",
    *        in="body",
    *        description="TreatmentPlan object containing only the fields that need to be updated. (The extract_id property cannot be updated and will be ignored)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/TreatmentPlanSwagObj"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="TreatmentPlan object fields to return.",
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

    public function updateTreatmentPlan(Request $request)
    {
        return $this::handleRequest($request, new TreatmentPlan);
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  TreatmentPlan $TreatmentPlan object returned from the database
     * @param  request $request API request used to build filter.
     * @return TreatmentPlan object
     */
    protected function finalize($TreatmentPlan)
    {
        $TreatmentPlan = $this->loadTP($TreatmentPlan);
        return $TreatmentPlan;
    }

    /**
     * Save treatmentPlan object to the database.
     * @param  request       $request       API request
     * @param  treatmentplan $TreatmentPlan Treatment plan object to save (null to create)
     * @return The saved treatmentPlan object
     */
    private function saveTreatmentPlan(request $request, TreatmentPlan $TreatmentPlan = null)
    {
        //begin a transaction to ensure the entire plan saves properly or not
        //at all.
        DB::transaction(function () use (&$TreatmentPlan, $request) {
            if (!is_null($TreatmentPlan)) {
                // we were given a TP to update so we need to make sure its safe to do so,
                // and then copy the details to a new tp and mark the old one deleted.
                $CountActiveRx = Prescription::where('treatment_plan_id', $TreatmentPlan->treatment_plan_id)->where('strikethrough', 'F')->get()->count();
                $CountRx = Prescription::where('treatment_plan_id', $TreatmentPlan->treatment_plan_id)->get()->count();
                $CountInj = Injection::where('treatment_plan_id', $TreatmentPlan->treatment_plan_id)->get()->count();
                if ($CountRx == 0 && $CountInj == 0) {
                    // no rxs using this plan so its ok to change. Do nothing and continue
                } else {
                    // we need to make a copy to maintain existing db links but there are
                    // no active rxs pointing at this plan so we don't need to warn the user
                    $TreatmentPlan->deleted = 'T';
                    $TreatmentPlan->save();

                    // duplicate the plan
                    $newTp = $TreatmentPlan->replicate();
                    $newTp->deleted='F';
                    $newTp->save();

                    if ($CountActiveRx != 0) {
                        //update the active rxs to point to the new plan
                        Prescription::where('treatment_plan_id', $TreatmentPlan->treatment_plan_id)
                            ->where('strikethrough', 'F')
                            ->update(['treatment_plan_id' => $newTp->treatment_plan_id]);
                    }
                    $TreatmentPlan = $newTp;
                }
            } else {
                // Save new
                $TreatmentPlan = new TreatmentPlan();
            }

            $TreatmentPlan = $this->APItoDB($request, $TreatmentPlan);

            //Everything checked out so assign all of the new values.
            //for each property, if the new value is null, use the existing value
            //
            //Note the reason for manual assignment is that treatment plan as seen from
            //the API and that which is stored in the DB have very different structures.
            $TreatmentPlan->Deleted = isset($request['deleted']) ? $TreatmentPlan->deleted : 'F';

            $Details = $request->json('details');

            $Bottles = !isset($request['details']) ? [] : $Details;

            $TreatPlanDetailRows = array();
            foreach ($Bottles as $Bottle) {
                $Steps = $Bottle['steps'];
                foreach ($Steps as $Step) {
                    $TreatPlanDetails = new TreatPlanDetails();
                    $TreatPlanDetails->minInterval = $Step['min_interval'];
                    $TreatPlanDetails->maxInterval = $Step['max_interval'];
                    $TreatPlanDetails->dilution = $Bottle['dilution'];
                    $TreatPlanDetails->{'5or10'} = $Bottle['fold'];
                    $TreatPlanDetails->color = $Bottle['color'];
                    $TreatPlanDetails->dose = $Step['dose'];
                    $TreatPlanDetails->step = $Step['step_number'];
                    //add the row to the array of rows
                    array_push($TreatPlanDetailRows, $TreatPlanDetails);
                }
            }

            //save the plan
            $TreatmentPlan->save();

            if (count($TreatPlanDetailRows) > 0) {
                //delete old details rows if they exist;
                TreatPlanDetails::where('treatment_plan_id', $TreatmentPlan->treatment_plan_id)->delete();

                // add new ones
                foreach ($TreatPlanDetailRows as $TreatPlanDetails) {
                    //assign the plan_id
                    $TreatPlanDetails->treatment_plan_id = $TreatmentPlan->treatment_plan_id;
                    //and save the row
                    $TreatPlanDetails->save();
                }
            }
        });
        return $TreatmentPlan;
    }

    protected function validateAndSave(request $request, $Object = null)
    {
        if ($this->getLock()) {
            $request = $this->fixCreatedUpdatedInfo($request);
            if ($Object->validate($request->all(), $this->RequestOptions->id)) {
                if ($request->method() === 'PUT' && (
                    is_null($request->input('override_assigned_plan')) || !(
                        ($request->input('override_assigned_plan')[0] === true) ||
                        ($request->input('override_assigned_plan')[0] === 'T')
                    )
                )) {
                    // if its an update we need to see if the plan we are
                    // updating will modify active rxs and warnt he user.
                    $Count = Prescription::where('strikethrough', 'F')
                        ->where('treatment_plan_id', $this->RequestOptions->id)
                        ->get()
                        ->count();
                    if ($Count > 0) {
                        return response()->json(['override_assigned_plan' => ['This treatment plan is assigned to '.$Count. ' active prescriptions. To force this update resubmit the treatment plan object with the override_assigned_plan property equal to true.']], 400);
                    }
                }
                return $Object = $this->saveAndQuery($request, $Object);
            } else {
                return response()->json($Object->errors(), 400);
            }
        } else {
            return response()->json('Another user currently owns one or more locks required to perform this action. Please try again later.', 401);
        }
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this->saveTreatmentPlan($request, $Object);

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($Object->treatment_plan_id);
        return $this->finishAndFilter($newObject);
    }

    protected function queryWith($Query)
    {
        return $Query->with('treatmentPlanDetails');
    }

    protected function queryWhere($Query)
    {
        return $Query->whereHas('treatmentPlanDetails');
    }

    protected function queryModifier($Query)
    {
        return $Query->where('deleted', 'F');
    }

    /**
     * called when we already have the basics of the TP but we want to load the
     * remaining (step) details.
     * @param  TreatmentPlan $TreatmentPlan $TreatmentPlan object being built
     * @param  request|null  $Request       API request
     * @return The TreatmentPlan object in the proper API form.
     */
    private function loadTP(TreatmentPlan $TreatmentPlan, $Filter = -1)
    {
        if ($Filter===-1 || array_search('details', $Filter)===false) {
            $TreatPlanDetails = $TreatmentPlan->treatmentPlanDetails;

            unset($TreatmentPlan->treatmentPlanDetails);

            $PlanBottles= array();
            foreach ($TreatPlanDetails as $Step) {
                $PlanStep = new PlanStep();
                $PlanStep->step_number = $Step->step;
                $PlanStep->min_interval = $Step->minInterval;
                $PlanStep->max_interval = $Step->maxInterval;
                $PlanStep->dose = $Step->dose;

                $BottleID=null;
                foreach ($PlanBottles as $BottleIndex => $Bottle) {
                    if ($Bottle->dilution === $Step->dilution) {
                        $BottleID=$BottleIndex;
                        break;
                    }
                }

                if ($BottleID !== null) {
                    //if we find an existing bottle of the correct
                    //dilution, add the tp step to that bottle
                    $Steps = $PlanBottles[$BottleID]->steps;
                    array_push($Steps, $PlanStep);
                    $PlanBottles[$BottleID]->steps = $Steps;
                } else {
                    //if we didnt find a bottle id of the correct dilution
                    //push a new one onto the array
                    $newPlanBottle = new PlanBottle();
                    $newPlanBottle->dilution = $Step->dilution;
                    $newPlanBottle->steps = array($PlanStep);
                    $newPlanBottle->fold = $Step->{'5or10'};
                    $newPlanBottle->color = $Step->color;

                    array_push($PlanBottles, $newPlanBottle);
                }
            }

            $TreatmentPlan->details = $PlanBottles;
        }

        //If the user requested no treatment_plan_id we were unable
        //to remove it during the initial query like we usually do because
        //it was required to find the details (which they may have requested)
        //so check it now and remove if necessary
        if ($Filter !== -1 && array_search('treatment_plan_id', $Filter)===false) {
            unset($TreatmentPlan->treatment_plan_id);
        }

        return $TreatmentPlan;
    }
}

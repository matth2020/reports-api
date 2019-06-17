<?php

namespace App\Http\Controllers\Order;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Vial\VialController;
use App\Http\Controllers\LockableController;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\TreatmentSet;
use App\Models\Prescription;
use App\Models\Inventory;
// use App\Models\Postpone;
use App\Models\Compound;
use App\Models\Profile;
use App\Models\Extract;
use App\Models\Dosing;
use App\Models\Config;
use App\Models\Vial;
use Carbon\Carbon;
use DB;

class SetOrderController extends LockableController
{
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/set_order",
     *     summary="Returns a list of all set_orders in the system.",
     *     description="",
     *     operationId="api.setOrder.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose set_orders are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription whose set_orders are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="SetOrder object fields to return.",
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
        return $this::handleRequest($request, new TreatmentSet);
    }
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/set_order",
     *     summary="Returns a list of all set_orders in the system.",
     *     description="",
     *     operationId="api.setOrder.getAllPatientSetOrders",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose set_orders are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="SetOrder object fields to return.",
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
    public function getAllPatientSetOrders(request $request)
    {
        return $this::handleRequest($request, new TreatmentSet);
    }
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}",
     *     summary="Returns a list of all set_orders in the system.",
     *     description="",
     *     operationId="api.setOrder.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose set_orders are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription whose set_orders are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="set_order_id",
     *         in="path",
     *         description="The id of the set_order to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="SetOrder object fields to return.",
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
    public function getSetOrder(request $request)
    {
        return $this::handleRequest($request, new TreatmentSet);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}",
     *     summary="Mark a set_order as deleted.",
     *     description="",
     *     operationId="api.Order.deleteSetOrder",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose set_order is to be deleted.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription whose set_orders are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="set_order_id",
     *        in="path",
     *        description="Id of the set_order to deleted.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Set_order object fields to return.",
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
    public function deleteSetOrder(request $request)
    {
        // if its the only set order in the purchase_order tell them they
        // have to delete the po
        $this->getRequestOptions($request);
        try {
            $TreatmentSet = TreatmentSet::where('patient_id', $this->RequestOptions->patient_id)->where('prescription_id', $this->RequestOptions->prescription_id)->findOrFail($this->RequestOptions->set_order_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located.', 404);
        }
        $TreatmentSet->compoundDetails();
        $ValidationErrors = [];
        // make sure it isnt mixed
        if ($TreatmentSet->status === 'not queued') {
            array_push($ValidationErrors, "The requested set_order has already been mixed and can no longer be changed.");
        }
        // make sure it isn't the only ts left in the po
        $ParentPOCount = TreatmentSet::where('purchase_order_id', '=', $TreatmentSet->purchase_order_id)->count();
        if ($ParentPOCount <= 1) {
            array_push($ValidationErrors, "The requested set_order is the last set order in purchase_order ".$TreatmentSet->purchase_order_id.". If you still want to delete the set_order, please delete the whole purchase_order.");
        }

        if (sizeof($ValidationErrors) > 0) {
            return response()->json($ValidationErrors, 400);
        }

        DB::transaction(
            function () use ($TreatmentSet) {
                $DosingsToDelete = [];
                foreach ($TreatmentSet->compounds as $Compound) {
                    foreach ($Compound->vials as $Vial) {
                        if ($Vial->dosing) {
                            $DosingsToDelete[] = $Vial->dosing;
                        }
                        $Vial->delete();
                    }
                    $Compound->delete();
                }
                $TreatmentSet->delete();

                $DosingsToDelete = array_unique($DosingsToDelete);
                foreach ($DosingsToDelete as $Dosing) {
                    $Dosing->delete();
                }
            },
            3
        );

        return response()->json($TreatmentSet);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}",
     *     summary="Update a set_order object.",
     *     description="",
     *     operationId="api.Order.updateSetOrder",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose set_orders are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription whose set_orders are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="set_order_id",
     *         in="path",
     *         description="The id of the set_order to update.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="Set order object",
     *        in="body",
     *        description="Set order object containing only the fields that need to be updated. (The set_order_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/TreatmentSet"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Code object fields to return.",
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
    public function updateSetOrder(Request $request, $patient_id)
    {
        // Add the url patient_id to the request for validation
        $request->merge([
            'patient_id' => $patient_id,
            'user_id' => $request->user()->user_id,
        ]);

        return $this::handleRequest($request, new TreatmentSet);
    }

    /**
     * Update an object to mixed state.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/set_order/{set_order_id}/mix",
     *     summary="Update a set_order object to mixed state.",
     *     description="",
     *     operationId="api.Order.updateSetOrderMix",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose set_orders are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="set_order_id",
     *         in="path",
     *         description="The id of the set_order to update.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="mix object",
     *        in="body",
     *        description="",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/MixObject"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Code object fields to return.",
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
     * Update an object to mixed state.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}/mix",
     *     summary="Update a set_order object to mixed state.",
     *     description="",
     *     operationId="api.Order.updateSetOrderMix2",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose set_orders are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="prescription_id",
     *         in="path",
     *         description="The id of the prescription whose set_orders are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="set_order_id",
     *         in="path",
     *         description="The id of the set_order to update.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="mix object",
     *        in="body",
     *        description="",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/MixObject"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Code object fields to return.",
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
    protected function mixSetOrder(Request $request, $patient_id)
    {
        $PlusMinusDoseMargin = 0;
        $this->getRequestOptions($request);
        try {
            // find the order with the correct, patient, prescription,
            // and set_order ids
            $Query = $this->queryWith(new TreatmentSet);
            $Query = $this->queryWhere($Query);
            $SetOrder = $Query->findOrFail($this->RequestOptions->set_order_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located.', 404);
        }
        $SetOrder->compoundDetails();
        $SetOrder->extracts(true /*include ids*/);
        // verify that the set_order is queued
        if ($SetOrder->queue_state !== 'queued') {
            // at least some of the vial rows are mixed (probably all)
            return response()->json(['set_order_id' => ['The set_order is not in "queued" state and therefore cannot be mixed.']], 400);
        }
        // verify that the mix info provided dose/inventory is valid
        $ErrorArray = [];

        $RequestMixArray = $request->input('constituents');
        if (is_null($RequestMixArray)) {
            $ErrorArray = array_merge($ErrorArray, ['constituents' => ['The constituents property is required']]);
        } elseif (!is_array($RequestMixArray)) {
            $ErrorArray = array_merge($ErrorArray, ['constituents' => ['The constituents property must be an array']]);
        }
        $Dosings = $SetOrder->dosings;

        $MatchesArray = [];
        $EarliestOutdate = null;
        foreach ($RequestMixArray as $Index => $Constituent) {
            // find the dosing element with the same extract_id
            // if not found, do sub search
            // compare dose
            // delete from dosings
            try {
                $Inventory = Inventory::where('deleted', 'F')
                    ->findOrFail($Constituent['inventory_id']);
            } catch (ModelNotFoundException $e) {
                $ErrorArray = array_merge($ErrorArray, ['constituent '.$Index  => ['The requested inventory could not be located.']]);
                continue; //skip the rest of this mixArray loop iteration
            }
            if (is_null($EarliestOutdate) || Carbon::parse($Inventory->outdate)
                ->lt(Carbon::parse($EarliestOutdate))) {
                $EarliestOutdate = $Inventory->outdate;
            }
            // Store the inventory in the Constituent object so we can use it in
            // the advanced sub search further down without a second query
            $Constituent['Inventory'] = $Inventory;
            foreach ($Dosings as $DosingIdx => $Dosing) {
                if ($Dosing->extract_id === $Inventory->extract_id) {
                    // Ensure that the dose they submited is within the
                    // error margin
                    if ((floatVal($Dosing['dose']) + $PlusMinusDoseMargin) < $Constituent['dose'] ||
                        (floatVal($Dosing['dose']) - $PlusMinusDoseMargin) > $Constituent['dose']) {
                        // the dose was outside of the acceptable range
                        $ErrorArray = array_merge($ErrorArray, ['constituent '.$Index  => ['The constituent dose was outside the acceptable range. The prescription indicates a dose of '.$Dosing->dose.'mL +/- '.$PlusMinusDoseMargin.'mL']]);
                    }
                    $MatchObj = app()->make('stdClass');
                    $MatchObj->constituent = $Constituent;
                    $MatchObj->dosing = $Dosing;
                    array_push($MatchesArray, $MatchObj);
                    // unset this index to shorten future searches since
                    // its been accounted for now
                    unset($Dosings[$DosingIdx]);
                    // also unset the requestMix items that have been found
                    // so we don't research them when looking for subs
                    unset($RequestMixArray[$Index]);
                    break; // break out of the loop
                }
            }
        }

        // Anything remaining in Dosings and RequestMixArray were not
        // direct matches so we need to see if they are equivalent subs.
        foreach ($Dosings as $DosingIdx => $Dosing) {
            try {
                $Subs = Extract::where('deleted', 'F')->select('sub')->findOrFail($Dosing->extract_id);
            } catch (ModelNotFoundException $e) {
                $Subs = '';
            }
            $SubsArray = [];
            if (!is_null($Subs)) {
                $SubsArray = explode(',', $Subs);
            }
            $found = false;
            foreach ($SubsArray as $extract_id) {
                if ($found) {
                    // we found a match the last time through so we can stop
                    // searching.
                    break;
                }
                foreach ($RequestMixArray as $Index => $Constituent) {
                    if (!isset($Constituent['Inventory'])) {
                        // if we don't have inventory, we cant do the rest
                        // of the validation for this one so skip ahead
                        continue;
                    }
                    if ($extract_id === $Constituent['Inventory']->extract_id) {
                        // Ensure that the dose they submited is within the
                        // error margin
                        if ((floatVal($Dosing->dose) + $PlusMinusDoseMargin) < $Constituent->dose ||
                        (floatVal($Dosing->dose) - $PlusMinusDoseMargin) > $Constituent->dose) {
                            // the dose was outside of the acceptable range
                            $ErrorArray = array_merge($ErrorArray, ['constituent '.$Index  => ['The constituent dose was outside the acceptable range. The prescription indicates a dose of '.$Dosing->dose.'mL +/- '.$PlusMinusDoseMargin.'mL']]);
                        }
                        $MatchObj = app()->make('stdClass');
                        $MatchObj->constituent = $Constituent;
                        $MatchObj->dosing = $Dosing;
                        array_push($MatchesArray, $MatchObj);
                        // unset this index to shorten future searches since
                        // its been accounted for now
                        unset($Dosings[$DosingIdx]);
                        // also unset the requestMix items that have been found
                        // so we don't research them when looking for subs
                        unset($RequestMixArray[$Index]);
                        $found = true;
                        break; // break out of the loop
                    }
                }
            }
        }

        // Anything in the RequestMixArray was not a valid sub for anything
        foreach ($RequestMixArray as $Index => $Constituent) {
            $ErrorArray = array_merge($ErrorArray, ['constituent '.$Index  => 'The constituent was not a valid match or substitution for any extracts in this order']);
        }
        // Anything in the Dosings array did not have a constituent provided
        foreach ($Dosings as $DosingIdx => $Dosing) {
            $ErrorArray = array_merge($ErrorArray, ['extract_id '.$Dosing->extract_id => 'No constituent was provided that matches or is a valid substitute for this extract.']);
        }

        // validation is complete, if the errors array is non empty, we should
        // return it and quit
        if (sizeOf($ErrorArray) > 0) {
            return response()->json($ErrorArray, 400);
        }

        // we passed validation so now we can begin making the updates
        // during validation we added all of the matches to the MatchesArray
        // so we can just loop over that and preform the necessary actions.
        $TransactionSuccess = DB::transaction(
            function () use ($MatchesArray, $EarliestOutdate) {
                $TotalVol = 0;
                $CompoundIds = [];
                foreach ($MatchesArray as $Pair) {
                    $Constituent = $Pair->constituent;
                    $Dosing = $Pair->dosing;

                    $Vials = Vial::where('dosing_id', $Dosing->dosing_id)->get();
       
                    $Inventory = $Constituent['Inventory'];

                    // multiple vials for this dosing because there is one
                    // for each physical bottle (compound)
                    foreach ($Vials as $Vial) {
                        array_push($CompoundIds, $Vial->compound_id);
                        $Vial->inventory_id = $Constituent['inventory_id'];
                        $Vial->mixDate = Carbon::now()->toDateTimeString();
                        $Vial->postponed = 'F';
                        $Vial->outdate = $EarliestOutdate;
                        $Vial->labelOutdate = $EarliestOutdate;
                        $Vial->user_id = $this->RequestOptions->user_id;
                        $Vial->updated_at = Carbon::now()->toDateTimeString();
                        $Vial->updated_by = $this->RequestOptions->user_id;
      
                        $Vial->save();
                    }

                    if ($Dosing->extract_id != $Constituent['Inventory']->extract_id) {
                        $Row = Dosing::find($Dosing->dosing_id);
                        $Row->extract_id = $Constituent['Inventory']->extract_id;
                        $Row->save();
                    }

                    $newVol = round(floatVal($Inventory->currVol) - floatVal($Constituent['dose']), 2);
                    $Inventory->volumeCurrent = $newVol < 0 ? 0 : $newVol;
                    $Inventory->save();

                    $TotalVol = round(floatVal($TotalVol) + FloatVal($Constituent['dose']), 3);
                }
                $UniqueCompounds = array_unique($CompoundIds);
                foreach ($UniqueCompounds as $CompoundId) {
                    $Compound = Compound::findOrFail($CompoundId);
                    $Compound->currVol = $TotalVol;
                    $Compound->save();
                }
                return true;
            },
            3 /*deadlock retries*/
        );

        if (!$TransactionSuccess) {
            return response()->json('An error occurred when committing the order to the database. Please try again', 500);
        }

        // we saved everything ok so now query the newly created vials
        $fakeRequest = Request::create('/v1/patient/'.$this->RequestOptions->patient_id.'/prescription/'.$SetOrder->prescription_id.'/_search', 'POST', ['treatment_set_id' => $SetOrder->treatment_set_id]);
        $data = new \Symfony\Component\HttpFoundation\ParameterBag;
        $data->add(['treatment_set_id' => $SetOrder->treatment_set_id]);
        $fakeRequest->setJson($data);

        $VialController = new VialController;
        return $VialController->searchVial($fakeRequest);
    }

    protected function queryWith($Query)
    {
        return $Query->with('compounds.dosings', 'compounds.treatmentSet.status', 'prescription:prescription_id,prescription_num,provider_id,provider_config_id,priority,clinic_id,patient_id,timestamp,strikethrough,5or10', 'prescription.provider:provider_id,displayname')->select('treatment_set_id', 'clinic_id', 'provider_id', 'patient_id', 'transaction', 'purchase_order_id', 'prescription_id', 'status_id', 'source', 'updated_at', 'updated_by', 'created_at', 'created_by');
    }

    protected function queryWhere($Query)
    {
        $Query = $Query->where('patient_id', $this->RequestOptions->patient_id);
        if (isset($this->RequestOptions->prescription_id)) {
            $Query = $Query->where('prescription_id', $this->RequestOptions->prescription_id);
        }
        return $Query;
    }

    protected function finalize($Object)
    {
        // newer syntax. Finalize method added to the treatment_set (set_order) model which
        // contains additional methods to modify data returned.
        return $Object->finalize();
    }

    protected function validateAndSave(request $request, $TreatmentSet = null)
    {
        if ($this->getLock()) {
            // special validation
            // need to check that extracts are among prescription extracts
            //  -need to worry about substitutions? probably
            //  -should we require that they send all even if dosing hasn't changed
            //   or only the ones they want to make changes to?
            if (!is_null($TreatmentSet)) {
                $TreatmentSet->compoundDetails();
                if ($TreatmentSet->queue_state === 'not queued') {
                    return response()->json(['set_order_id' => 'The selected treatment set has already been mixed and can not be edited.'], 400);
                }
            }

            try {
                // start a database transaction
                $ValidationErrors = [];
                DB::transaction(
                    function () use ($request, &$ValidationErrors, &$TreatmentSet) {
                        $request = $this->fixCreatedUpdatedInfo($request);

                        if (isset($this->RequestOptions->set_order_id)) {
                            try {
                                $OriginalTreatmentSet = TreatmentSet::findOrFail($this->RequestOptions->set_order_id);
                            } catch (ModelNotFoundException $e) {
                                return response()->json('The selected resource could not be located.', 404);
                            }
                        } elseif (isset($this->RequestOptions->treatment_set_id)) {
                            try {
                                $OriginalTreatmentSet = TreatmentSet::findOrFail($this->RequestOptions->treatment_set_id);
                            } catch (ModelNotFoundException $e) {
                                return response()->json('The selected resource could not be located.', 404);
                            }
                        } else {
                            $OriginalTreatmentSet = new TreatmentSet;
                        }

                        // make purchase order row
                        $ValidationErrors = array_merge($ValidationErrors, $this->createUpdateTreatmentSet($request->all(), 0, $TreatmentSet['transaction'], $TreatmentSet['purchase_order_id'], $this->RequestOptions, $OriginalTreatmentSet));
                    },
                    3 /*deadlock retries*/
                );
            } catch (Exception $e) {
                if (sizeOf($ValidationErrors) > 0) {
                    return response()->json($ValidationErrors, 400);
                }
                return response()->json('An error occurred when committing the treatment set to the database. Please try again', 500);
            }
            if (sizeOf($ValidationErrors) > 0) {
                return response()->json($ValidationErrors, 400);
            }
            // if we made it this far, query the purchase_order and return it.
            $Query = $this->queryWith(new TreatmentSet);
            $Query = $this->queryWhere($Query);
            $Object = $Query->findOrFail($TreatmentSet->treatment_set_id);
            return $this->finishAndFilter($Object);
        } else {
            return response()->json('Another user currently owns one or more locks required to perform this action. Please try again later.', 401);
        }
    }

    public function createUpdateTreatmentSet($SetOrder, $SetOrderIdx, $NewTransactionNum, $PurchaseOrderId, $RequestOptions = null, $TreatmentSet = null)
    {
        if (is_null($RequestOptions)) {
            $RequestOptions = $this->RequestOptions;
        }
        if (is_null($TreatmentSet)) {
            $TreatmentSet = new TreatmentSet;
        }
        $TreatmentSet->compoundDetails();

        $PrescriptionId = isset($SetOrder['prescription_id']) ? $SetOrder['prescription_id'] : $TreatmentSet->prescription_id;
        $ProviderId = isset($SetOrder['provider_id']) ? $SetOrder['provider_id'] : $TreatmentSet->provider_id;
        $Priority = isset($SetOrder['status_id']) ? $SetOrder['status_id'] : $TreatmentSet->status_id;
        $ClinicId = isset($SetOrder['clinic_id']) ? $SetOrder['clinic_id'] : $TreatmentSet->clinic_id;

        // make treatment set row
        $TreatmentSet->patient_id = $RequestOptions->patient_id;
        if (!is_null($ProviderId)) {
            $TreatmentSet->provider_id = $ProviderId;
        }
        if (!is_null($Priority)) {
            $TreatmentSet->status_id = $Priority;
        }
        if (!is_null($PrescriptionId)) {
            $TreatmentSet->prescription_id = $PrescriptionId;
        }

        if (!is_null($ClinicId)) {
            $TreatmentSet->clinic_id = $ClinicId;
        }

        $TreatmentSet->source = 'API';


        // if this is an update operation, initially set these fields from the request, so we can validate them

        $isCreate = $TreatmentSet->treatment_set_id == null;

        if (!$isCreate) {
            if (isset($SetOrder['transaction'])){
                $TreatmentSet->transaction = $SetOrder['transaction'];
            }
            if (isset($SetOrder['purchase_order_id'])){
                $TreatmentSet->purchase_order_id = $SetOrder['purchase_order_id'];
            }
        }
        else {
            $TreatmentSet->transaction = isset($SetOrder['transaction']) ? $SetOrder['transaction'] : $NewTransactionNum;
            $TreatmentSet->purchase_order_id = isset($SetOrder['purchase_order_id']) ? $SetOrder['purchase_order_id'] : $PurchaseOrderId;
        }

        $SetOrderValidationErrors = [];

        if ($TreatmentSet->validate($TreatmentSet->toArray(), $TreatmentSet->treatment_set_id)) {
            if (sizeOf($SetOrderValidationErrors) == 0) {
                // unset extra properties we added to treatment set in the process
                // of calculating previous values (mostly for update and mostly
                // came when we called ->compoundDetails())
                unset($TreatmentSet->queue_state);
                unset($TreatmentSet->size);
                unset($TreatmentSet->dilutions);
                unset($TreatmentSet->tray_location);
                unset($TreatmentSet->name);
                unset($TreatmentSet->note);

                $TreatmentSet->transaction = !is_null($NewTransactionNum) ? $NewTransactionNum : $TreatmentSet->transaction;
                $TreatmentSet->purchase_order_id = $PurchaseOrderId;

                if (isset($SetOrder['updated_at'])) {
                    $TreatmentSet->updated_at = $SetOrder['updated_at'];
                }
                if (isset($SetOrder['updated_by'])) {
                    $TreatmentSet->updated_by = $SetOrder['updated_by'];
                }

                // now save it
                $TreatmentSet->save();
            }
        } else {
            $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                    ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], $TreatmentSet->errors()->toArray())
                    : $TreatmentSet->errors()->toArray();
        }

        // find the prescription so its properties can be used to find details
        // for the rows below.
        try {
            $Prescription = Prescription::findOrFail($PrescriptionId);
        } catch (ModelNotFoundException $e) {
            $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                    ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], ['The selected prescription_id is invalid.'])
                    : ['The selected prescription_id is invalid.'];
            $Prescription = null;
        }

        if (!isset($SetOrder['dosings']) || !is_array($SetOrder['dosings']) || sizeOf($SetOrder['dosings']) === 0) {
            $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                    ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], ['The dosings attribute must be a non-empty array.'])
                    : ['The dosings attribute must be a non-empty array.'];
            $SetOrder['dosings'] = [];
        }

        if ($Prescription && sizeOf($SetOrder['dosings']) > 0) {
            // if we cant even find the prescription or have no dosings, don't continue. We
            // cant really validate much more without that anyway.
            // find provider_config so it can be used to calculate values below
            $Profile = Profile::find($Prescription->provider_config_id);

            if (isset($SetOrder['dilutions']) && !is_array($SetOrder['dilutions'])) {
                // its set but not an array
                $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                    isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                        ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], ['The dilutions attribute must be a non-empty array.'])
                        : ['The dilutions attribute must be a non-empty array.'];
            } elseif (!isset($SetOrder['dilutions']) || sizeOf($SetOrder['dilutions']) === 0 || sizeOf($SetOrder['dilutions']) > 8) {
                // its an array larger than we can support
                $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                    isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                        ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], ['There must at least 1 and no more than 8 dilutions.'])
                        : ['There must at least 1 and no more than 8 dilutions.'];
            } else {
                // the array size is good but verify the extracts are all ok.
                $Prescription->everBeenMixed();
                $WrongExtracts = false;
                if ($Prescription->ever_been_mixed === 'T') {
                    // cant change the extracts anymore
                    $RxExtracts = Dosing::distinct()->select('extract_id')->where('prescription_id', '=', $PrescriptionId)->get();
                    if (sizeof($RxExtracts) !== sizeof($SetOrder['dosings'])) {
                        // dosing doesn't contain the right number of extracts
                        // and the rx has been mixed so these cant change
                        $WrongExtracts = true;
                    } else {
                        // see if every extract is present in both arrays
                        $FoundAll = true;
                        foreach ($RxExtracts as $RxExtract) {
                            $FoundExtract = false;
                            foreach ($SetOrder['dosings'] as $ReqExtract) {
                                if ($ReqExtract['extract_id'] === $RxExtract['extract_id']) {
                                    $FoundExtract = true;
                                    break;
                                }
                            }
                            $FoundAll = $FoundAll && $FoundExtract;
                        }
                        $WrongExtracts = !$FoundAll;
                    }
                    if ($WrongExtracts) {
                        $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                            isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                                ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], ['This prescription has been previously mixed so the included extracts may not be changed.'])
                                : ['This prescription has been previously mixed so the included extracts may not be changed.'];
                    }
                }
            }

            // start a postpone row
            // $Postpone = new Postpone;
            // $Postpone->postponeDate = Carbon::now()->toDateTimeString();
            // $Postpone->user_id = $RequestOptions->user->user_id;

            // sort dilutions array based on profile->numOrder
            // // numOrder 0 = descendingDilution 1A=100:1, 2A=10:1, 3A=1:1
            // numOrder 1 = ascendingDilution 1A=1:1, 2A=10:1, 3A=100:1
            $OrderDilutions = isset($SetOrder['dilutions']) ? $SetOrder['dilutions'] : [];
            if ($Profile->numorder == 'descending_dilution') {
                usort($OrderDilutions, [$this, "sortBottlesDesc"]);
            } else {
                usort($OrderDilutions, [$this, "sortBottlesAsc"]);
            }
            $Size = isset($SetOrder['size']) ? $SetOrder['size'] : null;
            $Name = isset($SetOrder['name']) ? $SetOrder['name'] : null; //might need past compound name here
            $Note = isset($SetOrder['note']) ? $SetOrder['note'] : null;
            // first remove any previous compounds that aren't in the dilutions arr
            foreach ($TreatmentSet->compounds as $TsCompound) {
                // while we are in here, if we weren't given size, name, or note
                // with the ts, we should try to get them from one of the existing
                // compounds
                $Size = !is_null($Size) ? $Size : $TsCompound->size;
                $Name = !is_null($Name) ? $Name : $TsCompound->name;
                $Note = !is_null($Note) ? $Note : $TsCompound->compound_note;
                if (!in_array($TsCompound->dilution, $OrderDilutions)) {
                    // they updated the treatment set without this dilution so we
                    // need to remove the compound and vial rows associated
                    // with it.
                    $TsCompound->vials()->delete();
                    $TsCompound->delete();
                }
            }
            foreach ($OrderDilutions as $PostponeIdx => $Dilution) {
                // verify the dilution is part of the profile and if
                // not add an error to the array.
                if (!$Profile->hasDilution($Dilution)) {
                    $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                        isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                            ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], ['Requested dilution '.$Dilution.' is not available in the requested profile.'])
                            : ['Requested dilution '.$Dilution.' is not available in the requested profile.'];
                }
                // if we are doing an update we need to find the correct compound
                $Compound = new Compound;
                foreach ($TreatmentSet->compounds as $TsCompound) {
                    if ($TsCompound->dilution == $Dilution) {
                        $Compound = $TsCompound;
                        break;
                    }
                }

                // make compound row
                $Compound->dilution = $Dilution;
                $Compound->user_id = $RequestOptions->user->user_id;
                $Compound->timestamp = Carbon::now()->toDateTimeString();
                if (!is_null($Note)) {
                    $Compound->compound_note = $Note;
                }
                $Compound->size = $Size;
                $Compound->name = $Name;

                $Compound->active = isset($Compound->active) ? $Compound->active : 'F';
                if (is_null($Compound->color)) {
                    $Compound->color = $Profile->getDilutionColor($Dilution);
                }
                if (is_null($Compound->bottleNum)) {
                    $Compound->bottleNum = $this->getBottleNum($Profile, $PrescriptionId, $Dilution);
                }
                $Compound->rx_id = $PrescriptionId;
                $Compound->provider_config_id = $Prescription->provider_config_id;
                $Compound->created_by = $RequestOptions->user->user_id;
                $Compound->treatment_set_id = $TreatmentSet->treatment_set_id;

                // Andrew: Regarding validation of profile_id when updating a PO -
                // Passing 'null' into the validator makes it think that the user is creating a new
                // Compound, which of course is not the case.
                // However, when I make it pass in $Compound->profile_id, then the 'sometimes'
                // validators don't run when they should (e.g., when creating a new PurchaseOrder)
                // and other tests fail. Complexity *= Complexity

                if ($Compound->validate($Compound->toArray(), null)) {
                    if (sizeOf($SetOrderValidationErrors) == 0) {
                        $Compound->save();
                    }
                    // add to the postpone row
                    // $Postpone['compound_id'. ($PostponeIdx + 1)] = $Compound->compound_id;
                } else {
                    $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                        isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                            ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], $Compound->errors()->toArray())
                            : $Compound->errors()->toArray();
                }
                $diltPos = 0;
                $DosingValidationErrors = [];
                foreach ($SetOrder['dosings'] as $DoseIdx => $Extract) {
                    if ($PostponeIdx == 0) {
                        // we only make new dosing rows on the first compound,
                        // all other compounds (bottles of different dilutions) will
                        // share the dosing rows
                    
                        // find the existing dosing row if one exists.
                        $Dosing = new Dosing;
                        foreach ($Compound->dosings as $TsDosing) {
                            if ($TsDosing->extract_id == $Extract['extract_id']) {
                                $Dosing = $TsDosing;
                                break;
                            }
                        }

                        $Dose = isset($Extract['dose']) ? $Extract['dose'] : $Dosing->dose;
                        $ExtractId = isset($Extract['extract_id']) ? $Extract['extract_id'] : $Dosing->extract_id;
                        $ENTdilution = isset($Extract['ent_dilution']) ? $Extract['ent_dilution'] : $Dosing->ent_dilution;

                        $Dosing->dose = $Dose;
                        $Dosing->extract_id=$ExtractId;
                        $Dosing->ent_dilution = $ENTdilution;
                        $Dosing->prescription_id = $PrescriptionId;
                        if ($Dosing->validate($Dosing->toArray(), null)) {
                            if (sizeOf($SetOrderValidationErrors) == 0  && sizeOf($DosingValidationErrors) == 0) {
                                $Dosing->save();
                            }
                        } else {
                            $DosingValidationErrors['dose_'.$DoseIdx] = $Dosing->errors()->toArray();
                        }
                        // find the inventory ID to use;
                        if (!isset($DosingValidationErrors['dose_'.$DoseIdx]['extract_id'])) {
                            $InventoryId = $this->findOrCreateInventory($ExtractId, $ENTdilution);
                        } else {
                            $InventoryId = 0;
                        }
                    } else {
                        //find the dilution from the first iteration of the
                        //compound loop
                        foreach ($TreatmentSet->compounds()->get() as $TsCompound) {
                            if ($TsCompound->dilution == $OrderDilutions[0]) {
                                // now find the $Dosing for that compound
                                foreach ($TsCompound->dosings()->get() as $TsDosing) {
                                    if ($TsDosing->extract_id == $Extract['extract_id']) {
                                        $Dosing = $TsDosing;
                                        break;
                                    }
                                }
                                break;
                            }
                        }

                        // now continue on creating vials like normal
                    }

                    $Dosing->vials();

                    $Vial = new Vial;
                    if(sizeof($Dosing->vials) > 0 && !$isCreate) {
                        foreach($Dosing->vials as $VialCheck) {
                            if($VialCheck->compound_id == $Compound->compound_id) {
                                $Vial = $VialCheck;
                            }
                        }
                    }

                    $TrayLocation = isset($SetOrder['tray_location']) ? $SetOrder['tray_location'] : $Vial->tray_location;

                    $Vial->dosing_id=$Dosing->dosing_id;
                    if (is_null($Vial->barcode)) {
                        $Vial->barcode = $this::allocateBarcode();
                    }
                    $Vial->traylocation = $TrayLocation;
                    $Vial->postponed = 'T';
                    if (is_null($Vial->transaction)) {
                        $Vial->transaction = $NewTransactionNum;
                    }
                    $Vial->treatment_plan_id = $Prescription->treatment_plan_id;
                    $Vial->compound_id = $Compound->compound_id;
                    $Vial->user_id = $RequestOptions->user->user_id;
                    $Vial->created_by = $RequestOptions->user->user_id;
                    $Vial->inventory_id = $InventoryId;
                    if ($Vial->validate(array_merge($Vial->toArray(), ['ignore_ids' => true]), null)) {
                        if (sizeOf($SetOrderValidationErrors) == 0 && sizeOf($DosingValidationErrors) == 0) {
                            $Extract = Extract::find($ExtractId);
                            if ($Extract->isDiluent === 'T') {
                                $Vial->diltPos = $diltPos;
                                $diltPos = $diltPos+1;
                            }
                            $Vial->save();
                        }
                    } else {
                        $DosingValidationErrors['dose_'.$DoseIdx] =
                            isset($DosingValidationErrors['dose_'.$DoseIdx])
                                ? array_merge($DosingValidationErrors['dose_'.$DoseIdx], $Vial->errors()->toArray())
                                : $Vial->errors()->toArray();
                    }
                }

                // now delete all dosings and vials that don't appear in the request

                foreach ($Compound->dosings as $TsDosing) {
                    $ExtractId = $TsDosing['extract_id'];

                    $found = false;
                    foreach ($SetOrder['dosings'] as $Extract) {
                        if ($Extract['extract_id'] === $ExtractId) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $TsDosing->vials();

                        foreach ($TsDosing->vials as $Vial) {
                            $Vial->delete();
                        }

                        $TsDosing->delete();
                    }
                }

                if (sizeOf($DosingValidationErrors) > 0) {
                    $SetOrderValidationErrors['set_order_'.$SetOrderIdx] =
                        isset($SetOrderValidationErrors['set_order_'.$SetOrderIdx])
                            ? array_merge($SetOrderValidationErrors['set_order_'.$SetOrderIdx], $DosingValidationErrors)
                            : $DosingValidationErrors;
                }
                // color is auto generated so if we have a color problem, we
                // really have a dilution or other problem that will be reported.
                unset($SetOrderValidationErrors['set_order_'.$SetOrderIdx]['color']);
            }
        }
        // now save the postpone row
        // if (sizeOf($SetOrderValidationErrors) == 0) {
        //     $Postpone->save();
        // }
        return $SetOrderValidationErrors;
    }

    protected function sortBottlesAsc($bottleA, $bottleB)
    {
        if ($bottleA == $bottleB) {
            return 0;
        }
        return ($bottleA < $bottleB) ? -1 : 1;
    }

    protected function sortBottlesDesc($bottleA, $bottleB)
    {
        if ($bottleA == $bottleB) {
            return 0;
        }
        return ($bottleA < $bottleB) ? 1 : -1;
    }


    protected function findOrCreateInventory($ExtractId, $ENTdilution = 0)
    {
        try {
            $Inventory = Inventory::where('extract_id', $ExtractId)
                ->where('dilutionENT', $ENTdilution)
                ->where(function ($Query) {
                    return $Query->where('deleted', 'F');
                })
                ->orWhere(function ($Query) {
                    return $Query->where('deleted', 'T')
                        ->where('installBy', 'API');
                })
                ->orderBy('deleted', 'T') //prefers deleted=F
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $Inventory = null;
        }
        if (is_null($Inventory)) {
            $Inventory = new Inventory();
            $Inventory->deleted = 'T';
            $Inventory->installBy = 'API';
            $Inventory->extract_id = $ExtractId;
            $Inventory->dilutionENT = $ENTdilution;
            $Inventory->door = -1;
            $Inventory->changereason = 'API set_order creation';
            $Inventory->save();
        }
        return $Inventory->inventory_id;
    }

    private function getBottleNum($ProviderConfig, $prescription_id, $dilution)
    {
        try {
            $StartLetter = Config::where('section', 'prefs')->where('name', 'vialStartLetter')->firstOrFail()->value;
        } catch (ModelNotFoundException $e) {
            //if there is no config entry for start letter, dont use one
            $StartLetter = '';
        }
        //select max bottle num and increment letter portion
        $LastBottle = Compound::where('rx_id', $prescription_id)->where('dilution', $dilution)->orderBy('bottleNum', 'desc')->first();
        $LastBottleNum = !is_null($LastBottle) ? $LastBottle->bottleNum : null;
        if (!is_null($LastBottleNum)) {
            preg_match_all('/^-?[0-9]+/', $LastBottleNum, $number);
            preg_match_all('/[a-zA-Z]+$/', $LastBottleNum, $letter);
            $nextLetter = isset($letter[0][0]) ? ++$letter[0][0] : 'A';
            $NextBottleNum = $number[0][0].$nextLetter;
        } else {
            //this bottle was never ordered before so select most dilute from history
            // then calc offset between most dilute and this bottle based on prov conf
            // then add offset to most diluts bottle num to create this bottle num
            $MostDiluteHistory = Compound::where('rx_id', $prescription_id)
                ->where('dilution', DB::raw("(select max(`dilution`) from compound where rx_id='".$prescription_id."')"))
                ->first();

            if (!is_null($MostDiluteHistory)) {
                $test = preg_match('/[0-9]+/', $MostDiluteHistory->bottleNum, $Matches);
                $MostDiluteBottleNum = $test === 1 ? $Matches[0] : 0;
            }
            $reverse = $ProviderConfig->numorder = 0 ? 1 : -1;
            if (is_null($MostDiluteHistory)) {
                //new never mixed rx
                $BottleNumVal = 1 + $ProviderConfig->offset;
            } elseif ($ProviderConfig->profileRate == -1) {
                //custom dilutions
                $DilutionsCSV = $ProviderConfig->dilutions10;
                $AvailDilutions = explode(",", $DilutionsCSV);
                $HistoryOffset = array_search(round($MostDiluteHistory->dilution), $AvailDilutions)+1;
                $CurrentOffset = array_search(round($dilution), $AvailDilutions)+1;
                $BottleNumVal = $MostDiluteBottleNum + ($reverse * ($HistoryOffset-$CurrentOffset));
            } elseif ($ProviderConfig->profileRate == 5) {
                //standard dilutions 5
                $DilutionsCSV = $ProviderConfig->dilutions5;
                $AvailDilutions = explode(",", $DilutionsCSV);
                $HistoryOffset = array_search(round(log($MostDiluteHistory->dilution, 5)), $AvailDilutions)+1;
                $CurrentOffset = array_search(round(log($dilution, 5)), $AvailDilutions)+1;
                $BottleNumVal = $MostDiluteBottleNum + ($reverse * ($HistoryOffset-$CurrentOffset));
            } else {
                //standard dilutions 10
                $DilutionsCSV = $ProviderConfig->dilutions10;
                $AvailDilutions = explode(",", $DilutionsCSV);
                $HistoryOffset = array_search(round(log($MostDiluteHistory->dilution, 10)), $AvailDilutions)+1;
                $CurrentOffset = array_search(round(log($dilution, 10)), $AvailDilutions)+1;
                $BottleNumVal = $MostDiluteBottleNum + ($reverse * ($HistoryOffset-$CurrentOffset));
            }
            $NextBottleNum=$BottleNumVal.$StartLetter;
        }
        return $NextBottleNum;
    }
}

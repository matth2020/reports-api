<?php

namespace App\Http\Controllers\Order;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Order\SetOrderController;
use App\Http\Controllers\LockableController;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use DB;

class PurchaseOrderController extends LockableController
{
    public static $requiredLocks = ['mixingLock'];
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Order"},
     *     path="/purchase_order",
     *     summary="Returns a list of all purchase_orders in the system.",
     *     description="",
     *     operationId="api.setOrder.getAllPurchaseOrders",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="PurchaseOrder object fields to return.",
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
    public function getAllPurchaseOrders(request $request)
    {
        return $this::handleRequest($request, new PurchaseOrder);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Order"},
     *     path="/purchase_order/_search",
     *     summary="Returns a list of purchase_orders that match the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.purchase_order.searchPurchaseOrder",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="purchase_order object",
     *        in="body",
     *        description="Purchase Order object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/PurchaseOrder"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Purchase Order object fields to return.",
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
    public function searchPurchaseOrder(request $request)
    {
        return $this::handleRequest($request, new PurchaseOrder);
    }
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/purchase_order",
     *     summary="Returns a list of all purchase_orders in the system.",
     *     description="",
     *     operationId="api.setOrder.getAllPatientPurchaseOrders",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose purchase_orders are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="PurchaseOrder object fields to return.",
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
    public function getAllPatientPurchaseOrders(request $request)
    {
        return $this::handleRequest($request, new PurchaseOrder);
    }
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Order"},
    *     path="/patient/{patient_id}/purchase_order/{purchase_order_id}",
    *     summary="Returns a list of all purchase_orders in the system.",
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
    *         description="The id of the patient whose purchase_orders are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *      @SWG\Parameter(
    *         name="purchase_order_id",
    *         in="path",
    *         description="The id of the purchase_order to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="PurchaseOrder object fields to return.",
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
    public function getPurchaseOrder(request $request)
    {
        return $this::handleRequest($request, new PurchaseOrder);
    }

    /**
     * Create a new purchase_order object
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/purchase_order",
     *     summary="Create a new purchase_order object.",
     *     operationId="api.PurchaseOrder.createPurchaseOrder",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose PurchaseOrder are to be created.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="PurchaseOrder object",
     *        in="body",
     *        description="PurchaseOrder object to be created",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/TreatmentSet"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="PurchaseOrder object fields to return.",
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
    public function createPurchaseOrder(request $request)
    {
        return $this::handleRequest($request, new PurchaseOrder);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/purchase_order/{purchase_order_id}",
     *     summary="Mark a purchase_order as deleted.",
     *     description="",
     *     operationId="api.Order.deletePurchaseOrder",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose purchase_order is to be deleted.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="purchase_order_id",
     *        in="path",
     *        description="Id of the purchase_order to deleted.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Purchase_order object fields to return.",
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
    public function deletePurchaseOrder(request $request)
    {
        $this->getRequestOptions($request);
        try {
            $PurchaseOrder = PurchaseOrder::whereHas('treatmentSets', function ($innerQuery) {
                return $innerQuery->where('patient_id', $this->RequestOptions->patient_id);
            })->findOrFail($this->RequestOptions->purchase_order_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located.', 404);
        }

        if ($PurchaseOrder->queueState()->queue_state === 'not queued') {
            return response()->json(['The selected purchase order has already been completed and cannot be changed.'], 400);
        }

        DB::transaction(
            function () use ($PurchaseOrder) {
                foreach ($PurchaseOrder->treatmentSets as $TreatmentSet) {
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
                }
                $PurchaseOrder->delete();
            },
            3
        );
        return response()->json($PurchaseOrder);
    }
    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Order"},
     *     path="/patient/{patient_id}/purchase_order/{purchase_order_id}",
     *     summary="Update a purchase_order object.",
     *     description="",
     *     operationId="api.Order.updatePurchaseOrder",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose purchase_orders are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="purchase_order_id",
     *         in="path",
     *         description="The id of the purchase_order to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="PurchaseOrder object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Parameter(
     *        name="Purchase order object",
     *        in="body",
     *        description="Purchase order object containing only the fields that need to be updated. (The purchase_order_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/PurchaseOrder"),
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
    public function updatePurchaseOrder(Request $request, $patient_id)
    {
        return $this::handleRequest($request, new PurchaseOrder);
    }

    protected function queryWhere($Query)
    {
        return $Query->whereHas('treatmentSets', function ($innerQuery) {
            if (isset($this->RequestOptions->patient_id)) {
                $innerQuery->where('patient_id', $this->RequestOptions->patient_id);
            }
        });
    }

    protected function queryWith($Query)
    {
        return $Query->with(['treatmentSets.compounds.dosings','treatmentSets.compounds.vials','treatmentSets.prescription.profile','treatmentSets.prescription.compounds']);
    }

    // martin questions
    // -cant remember why we return the full prescription relationship. should we just
    // bump priority out and not do that? what else in prescription do you need?
    // -cant find where compound_name exists... but maybe that is made at mixing time
    // and not order time?
    // -same for compound_note but it makes sense for that to be at mixing time I think
    // -vial bottle note.. is that mixing time as well
    // -vial.mixafter seems like we should include this with set_order?
    // -vial.diltpos do we need this?

    protected function validateAndSave(request $request, $PurchaseOrder = null)
    {
        if ($this->getLock()) {
            // special validation
            // need to check that extracts are among prescription extracts
            //  -need to worry about substitutions? probably


            // start a database transaction
            $ValidationErrors = [];
            $TransactionSuccess = DB::transaction(
                function () use ($request, &$ValidationErrors, &$PurchaseOrder) {
                    $request = $this->fixCreatedUpdatedInfo($request);

                    // make purchase order row
                    $SetOrderController = new SetOrderController;
                    if (is_null($PurchaseOrder)) {
                        $PurchaseOrder = new PurchaseOrder;
                    }

                    $ProvidedTreatmentSets = $request->input('set_orders');
                    if ($request->method() === 'PUT' && !is_null($ProvidedTreatmentSets)) {
                        // if we are updating this purchase_order, and the new purchase_order included set_orders
                        // then we need to deal with removing set_orders:
                        //
                        // for each existing set_order,
                        //   if it hasn't been mixed
                        //     if it doesn't exist in the provided purchase_order
                        //       delete it

                        foreach ($PurchaseOrder->treatmentSets as $TreatmentSet) {
                            if ($TreatmentSet->status !== 'not queued') {
                                $found = false;
                                foreach ($ProvidedTreatmentSets as $ProvidedTreatmentSet) {
                                    if ($ProvidedTreatmentSet['set_order_id'] === $TreatmentSet->treatment_set_id) {
                                        $found = true;
                                        break;
                                    }
                                }

                                if (!$found) {
                                    $TreatmentSet->delete();
                                }
                            }
                        }
                    }

                    $PurchaseOrder = $this::APItoDB($request, $PurchaseOrder);
                    if ($PurchaseOrder->validate($request->all(), $PurchaseOrder->purchase_order_id)) {
                        $PurchaseOrder->save();
                    } else {
                        $ValidationErrors = array_merge($ValidationErrors, $PurchaseOrder->errors()->toArray());
                    }
                    $SetOrderValidationErrors = [];
                    if (is_null($ProvidedTreatmentSets) && !isset($PurchaseOrder['purchase_order_id'])) {
                        $SetOrderValidationErrors = ['The set_orders field is required.'];
                    }

                    if ($PurchaseOrder->treatmentSets->count() > 0) {
                        $NextTransactionNum = $PurchaseOrder->treatmentSets[0]->transaction;
                    } else {
                        $NextTransactionNum = $this::allocateTransactionNum();
                    }

                    foreach ($ProvidedTreatmentSets as $SetOrderIdx => $SetOrder) {
                        $NewTransactionNum = $NextTransactionNum + $SetOrderIdx;

                        $ExistingTreatmentSet = null;
                        foreach ($PurchaseOrder->treatmentSets as $TreatmentSet) {
                            if (isset ($SetOrder['set_order_id']) && $SetOrder['set_order_id'] === $TreatmentSet->treatment_set_id) {
                                $ExistingTreatmentSet = $TreatmentSet;
                                break;
                            }
                        }

                        $Errors =
                            $SetOrderController->createUpdateTreatmentSet(
                                $SetOrder,
                                $SetOrderIdx,
                                $NewTransactionNum,
                                $PurchaseOrder->purchase_order_id,
                                $this->RequestOptions,
                                $ExistingTreatmentSet
                            );
                        $SetOrderValidationErrors = array_merge($SetOrderValidationErrors, $Errors);
                    }
                    $ValidationErrors = array_merge($ValidationErrors, $SetOrderValidationErrors);
                    if (sizeOf($ValidationErrors) > 0) {
                        DB::rollback();
                        return false;
                    }
                    return true;
                },
                3 /*deadlock retries*/
            );
            if (!$TransactionSuccess) {
                if (sizeOf($ValidationErrors) > 0) {
                    return response()->json($ValidationErrors, 400);
                }
                return response()->json('An error occurred when committing the order to the database. Please try again', 500);
            } elseif (sizeOf($ValidationErrors) > 0) {
                return response()->json($ValidationErrors, 400);
            }
            // if we made it this far, query the purchase_order and return it.

            $Query = $this->queryWith(new PurchaseOrder);
            $Query = $this->queryWhere($Query);
            try {
                $Object = $Query->findOrFail($PurchaseOrder->purchase_order_id);
            } catch (ModelNotFoundException $e) {
                return response()->json('An error occurred when committing the order to the database. Please try again', 500);
            }
            return $this->finishAndFilter($Object);
        } else {
            return response()->json('Another user currently owns one or more locks required to perform this action. Please try again later.', 401);
        }
    }

    protected function finalize($Object)
    {
        $AllQueued = true;
        $AnyQueued = false;
        foreach ($Object->treatmentSets as $set_order) {
            $set_order->finalize();
            $AllQueued = $AllQueued && $set_order->queue_state === "queued";
            $AnyQueued = $AnyQueued || $set_order->queue_state === "queued";
        }
        $Object->queue_state = 'complete';
        if ($AnyQueued) {
            $Object->queue_state = 'in progress';
        }
        if ($AllQueued) {
            $Object->queue_state = 'submitted';
        }
        $Object->set_orders = $Object->treatmentSets;
        unset($Object->treatmentSets);
        return $Object;
    }
}

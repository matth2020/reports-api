<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use GuzzleHttp\Client;
use DB;

class AddressController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Address"},
    *     path="/address",
    *     summary="Returns a list of all addresses in the system.",
    *     description="",
    *     operationId="api.address.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Address object fields to return.",
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
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Address"},
     *     path="/patient/{patient_id}/address",
     *     summary="Returns a list of all addresses in the system for the given patient.",
     *     description="",
     *     operationId="api.address.patientIndex",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="patient_id",
     *        in="path",
     *        description="Id of the patient to return addresses for.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Address object fields to return.",
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
        return $this::handleRequest($request, new Address);
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Address"},
    *     path="/address/{id}",
    *     summary="Returns a single address in the system identified by {id}.",
    *     description="",
    *     operationId="api.address.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the address to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Address object fields to return.",
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
    public function getAddress(request $request)
    {
        return $this::handleRequest($request, new Address);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Address"},
     *     path="/address/_search",
     *     summary="Returns a list of addresses in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.address.searchAddress",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="address object",
     *        in="body",
     *        description="An address object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Address"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Address object fields to return.",
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
     *     tags={"Address"},
     *     path="/patient/{patient_id}/address/_search",
     *     summary="Returns a list of addresses belonging to an patient that match the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.address.searchPatientAddress",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
    *        name="patient_id",
    *        in="path",
    *        description="Id of the patient to return addresses for.",
    *        required=true,
    *        type="integer",
    *     ),
     *     @SWG\Parameter(
     *        name="address object",
     *        in="body",
     *        description="An address object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Address"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Address object fields to return.",
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
    public function searchAddress(request $request)
    {
        return $this::handleRequest($request, new Address);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"Address"},
    *     path="/address",
    *     summary="Create a new address.",
    *     description="",
    *     operationId="api.address.createAddress",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="Address object to be created in the system. (The address_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Address"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Address object fields to return",
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
    public function createAddress(Request $request)
    {
        return $this::handleRequest($request, new Address);
    }

    /**
    * Delete an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Delete(
    *     tags={"Address"},
    *     path="/address/{id}",
    *     summary="Mark a address as deleted.",
    *     description="",
    *     operationId="api.address.deleteAddress",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the address to mark deleted.",
    *        required=true,
    *        type="integer",
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Address object fields to return.",
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
    public function deleteAddress(request $request)
    {
        return $this::handleRequest($request, new Address);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"Address"},
    *     path="/address/{id}",
    *     summary="Update a address object.",
    *     description="",
    *     operationId="api.address.updateAddress",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the address to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="address object",
    *        in="body",
    *        description="Address object containing only the fields that need to be updated. (The address_id property cannot be updated and will be ignored)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Address"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Address object fields to return.",
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
    public function updateAddress(Request $request)
    {
        return $this::handleRequest($request, new Address);
    }

    protected function queryWith($Query)
    {
        return $Query->with([
            'patientPrimary' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['patient_id', DB::raw("CONCAT(firstname,' ', lastname) AS name"),'phone','address_id']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->where('patient_id', $this->RequestOptions->patient_id);
                }
            },
            'patientBilling' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['patient_id',DB::raw("CONCAT(firstname,' ', lastname) AS name"),'phone','bill_to']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->where('patient_id', $this->RequestOptions->patient_id);
                }
            },
            'patientShipping' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['patient_id',DB::raw("CONCAT(firstname,' ', lastname) AS name"),'phone','ship_to']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->where('patient_id', $this->RequestOptions->patient_id);
                }
            },
            'providerPrimary' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['provider_id',DB::raw("CONCAT(first,' ', last) AS name"),'phone','address_id']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->whereHas('patients', function ($innerQuery2) {
                        $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                    });
                }
            },
            'accountPrimary' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['account_id','name','phone','address_id']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->whereHas('patients', function ($innerQuery2) {
                        $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                    });
                }
            },
            'accountBilling' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['account_id','name','phone','bill_to']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->whereHas('patients', function ($innerQuery2) {
                        $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                    });
                }
            },
            'accountShipping' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['account_id','name','phone','ship_to']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->whereHas('patients', function ($innerQuery2) {
                        $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                    });
                }
            },
            'clinicPrimary' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['clinic_id','name','phone','address_id']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->whereHas('prescriptions', function ($innerQuery2) {
                        $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                    });
                }
            },
            'purchaseOrderBilling' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['purchase_order_id','bill_to_name as name','bill_to_phone as phone','bill_to']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->whereHas('treatmentSets', function ($innerQuery2) {
                        $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                    });
                }
            },
            'purchaseOrderShipping' => function ($innerQuery) {
                $innerQuery = $innerQuery->select(['purchase_order_id','ship_to_name as name','ship_to_phone as phone','ship_to']);
                if ($this->RequestOptions->patient_id) {
                    $innerQuery->whereHas('treatmentSets', function ($innerQuery2) {
                        $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                    });
                }
            },
        ]);
    }

    protected function queryWhere($Query)
    {
        $Query = $Query->where('archived', 'F');
        if (isset($this->RequestOptions->patient_id)) {
            $Query = $Query->whereHas('patientBilling', function ($innerQuery) {
                $innerQuery->where('patient_id', $this->RequestOptions->patient_id);
            })->orWhereHas('patientShipping', function ($innerQuery) {
                $innerQuery->where('patient_id', $this->RequestOptions->patient_id);
            })->orWhereHas('patientPrimary', function ($innerQuery) {
                $innerQuery->where('patient_id', $this->RequestOptions->patient_id);
            })->orWhereHas('purchaseOrderBilling', function ($innerQuery) {
                $innerQuery->whereHas('treatmentSets', function ($innerQuery2) {
                    $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                });
            })->orWhereHas('purchaseOrderShipping', function ($innerQuery) {
                $innerQuery->whereHas('treatmentSets', function ($innerQuery2) {
                    $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                });
            })->orWhereHas('providerPrimary', function ($innerQuery) {
                $innerQuery->whereHas('prescriptions', function ($innerQuery2) {
                    $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                });
            })->orWhereHas('clinicPrimary', function ($innerQuery) {
                $innerQuery->whereHas('prescriptions', function ($innerQuery2) {
                    $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                });
            })->orWhereHas('accountPrimary', function ($innerQuery) {
                $innerQuery->whereHas('patients', function ($innerQuery2) {
                    $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                });
            })->orWhereHas('accountBilling', function ($innerQuery) {
                $innerQuery->whereHas('patients', function ($innerQuery2) {
                    $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                });
            })->orWhereHas('accountShipping', function ($innerQuery) {
                $innerQuery->whereHas('patients', function ($innerQuery2) {
                    $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
                });
            });
        }
        return $Query;
    }

    protected function validateAndSave(request $request, $Object = null)
    {
        if ($Object->validate($request->all(), $this->RequestOptions->id)) {
            // stage 2 validation
            if ($this->RequestOptions->isCreate) {
                $config = app()->make('config');
                $SmartyStreets = $config->get('services.smartyStreents');
                if (!is_null($SmartyStreets['id']) && !is_null($SmartyStreets['token']) && $SmartyStreets['enabled']) {
                    \Log::info('doing smartyStreents');
                    $url = "https://us-street.api.smartystreets.com/street-address?auth-id=".urlencode($SmartyStreets['id'])."&auth-token=".urlencode($SmartyStreets['token'])."&street=".urlencode($request->input('address_line_1'))."&city=".urlencode($request->input('city'))."&state=".urlencode($request->Input('state'))."&candidates=10";
                    $Client = new Client();
                    $res = $Client->request('GET', $url);
                    \Log::info($url);
                    \Log::info($res->getStatusCode());
                    // "200"
                    // echo $res->getHeader('content-type');
                    // 'application/json; charset=utf8'
                    // echo $res->getBody();
                    \Log::info($res->getBody());
                }
                // search for a dup
                $Address = Address::where('address_line_1', $request->input('address_line_1'))
                    ->where('address_line_2', $request->input('address_line_2'))
                    ->where('city', $request->input('city'))
                    ->where('state', $request->input('state'))
                    ->where('zip', $request->input('zip'))
                    ->where('province', $request->input('province'))
                    ->where('country', $request->input('country'))
                    ->orderBy('archived', 'desc')
                    ->first();
                if (!is_null($Address) && $Address->archived='F') {
                    return response()->json(['exists' => 'This address exists in the database as address_id='.$Address->address_id]);
                } elseif (!is_null($Address)) {
                    // it exists but it has been archived... since they are trying to
                    // recreate it, just unarchive instead
                    $Address->archived='F';
                    $Address->save();
                    return response()->json($Address);
                }
            }
            return $Object = $this->saveAndQuery($request, $Object);
        } else {
            return response()->json($Object->errors(), 400);
        }
    }

    protected function finalize($Object)
    {
        $Types = [];
        $AddressTypes = [];
        $PatientContacts = [];
        // patientAddresses are returned as arrays due to the address hasMany relationship
        // but because we are returning them for only one patient id each array
        // should have no more than 1 address
        if (isset($Object->patientBilling[0])) {
            unset($Object->patientBilling[0]->bill_to);
            unset($Object->patientBilling[0]->patient_id);
            array_push($Types, 'billing');
            array_push($AddressTypes, 'patient');
            array_push($PatientContacts, $Object->patientBilling[0]);
        }
        unset($Object->patientBilling);
        if (isset($Object->patientShipping[0])) {
            unset($Object->patientShipping[0]->ship_to);
            unset($Object->patientShipping[0]->patient_id);
            array_push($Types, 'shipping');
            array_push($AddressTypes, 'patient');
            array_push($PatientContacts, $Object->patientShipping[0]);
        }
        unset($Object->patientShipping);
        if (isset($Object->patientPrimary[0])) {
            unset($Object->patientPrimary[0]->address_id);
            unset($Object->patientPrimary[0]->patient_id);
            array_push($Types, 'primary');
            array_push($AddressTypes, 'patient');
            array_push($PatientContacts, $Object->patientPrimary[0]);
        }
        unset($Object->patientPrimary);
        $orderHistoryContacts = [];
        // unlike the other types, orders may have multiple elements in the arrays
        foreach ($Object->purchaseOrderBilling as $PO) {
            unset($PO->bill_to);
            unset($PO->purchase_order_id);
            array_push($Types, 'billing');
            array_push($AddressTypes, 'order');
            if (isset($PO->name) || isset($PO->phone)) {
                array_push($orderHistoryContacts, $PO);
            }
        }
        unset($Object->purchaseOrderBilling);
        foreach ($Object->purchaseOrderShipping as $PO) {
            unset($PO->ship_to);
            unset($PO->purchase_order_id);
            array_push($Types, 'shipping');
            array_push($AddressTypes, 'order');
            if (isset($PO->name) || isset($PO->phone)) {
                array_push($orderHistoryContacts, $PO);
            }
        }
        unset($Object->purchaseOrderShipping);
        $ProviderContacts = [];
        if (isset($Object->providerPrimary[0])) {
            unset($Object->providerPrimary[0]->address_id);
            unset($Object->providerPrimary[0]->provider_id);
            array_push($Types, 'primary');
            array_push($AddressTypes, 'provider');
            array_push($ProviderContacts, $Object->providerPrimary[0]);
        }
        unset($Object->providerPrimary);
        $ClinicContacts = [];
        if (isset($Object->clinicPrimary[0])) {
            unset($Object->clinicPrimary[0]->address_id);
            unset($Object->clinicPrimary[0]->clinic_id);
            array_push($Types, 'primary');
            array_push($AddressTypes, 'clinic');
            array_push($ClinicContacts, $Object->clinicPrimary[0]);
        }
        unset($Object->clinicPrimary);
        $AccountContacts = [];
        if (isset($Object->accountPrimary[0])) {
            unset($Object->accountPrimary[0]->account_id);
            unset($Object->accountPrimary[0]->address_id);
            array_push($Types, 'primary');
            array_push($AddressTypes, 'account');
            array_push($AccountContacts, $Object->accountPrimary[0]);
        }
        unset($Object->accountPrimary);
        if (isset($Object->accountBilling[0])) {
            unset($Object->accountBilling[0]->account_id);
            unset($Object->accountBilling[0]->bill_to);
            array_push($Types, 'billing');
            array_push($AddressTypes, 'account');
            array_push($AccountContacts, $Object->accountBilling[0]);
        }
        unset($Object->accountBilling);
        if (isset($Object->accountShipping[0])) {
            unset($Object->accountShipping[0]->account_id);
            unset($Object->accountShipping[0]->ship_to);
            array_push($Types, 'shipping');
            array_push($AddressTypes, 'account');
            array_push($AccountContacts, $Object->accountShipping[0]);
        }
        unset($Object->accountShipping);
        $Object->types = array_values(array_unique($Types));
        $Object->address_types = array_values(array_unique($AddressTypes));
        $Object->contacts = array_values(array_unique(array_merge($PatientContacts, $ClinicContacts, $ProviderContacts, $AccountContacts, $orderHistoryContacts)));
        return $Object;
    }
}

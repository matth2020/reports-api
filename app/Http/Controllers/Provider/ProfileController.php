<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Extract\ExtractController;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\ProviderDef;
use App\Models\Extract;
use DB;

class ProfileController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Provider"},
    *     path="/profile",
    *     summary="Returns a list of all provider configs in the system.",
    *     description="",
    *     operationId="api.providerConfigAll.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Profile object fields to return.",
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
    *     tags={"Provider"},
    *     path="/provider/{provider_id}/profile",
    *     summary="Returns a list of all provider configs in the system.",
    *     description="",
    *     operationId="api.providerConfig.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
     *        name="provider_id",
     *        in="path",
     *        description="Id of the provider whos configs should be read.",
     *        required=true,
     *        type="integer",
     *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Profile object fields to return.",
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
        return $this::handleRequest($request, new Profile);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Provider"},
     *     path="/profile/{id}",
     *     summary="Returns a single provider config in the system.",
     *     description="",
     *     operationId="api.providerConfig.all.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the provider config to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Provider config object fields to return.",
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
     *     tags={"Provider"},
     *     path="/provider/{provider_id}/profile/{id}",
     *     summary="Returns a single provider config in the system.",
     *     description="",
     *     operationId="api.providerConfig.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="provider_id",
     *        in="path",
     *        description="Id of the provider to whos config should be read.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the provider config to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Provider config object fields to return.",
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
    public function getProfile(request $request)
    {
        return $this::handleRequest($request, new Profile);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Provider"},
     *     path="/profile/_search",
     *     summary="Returns a list providers configs in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.providerConfig.searchAllProfile",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="provider config object",
     *        in="body",
     *        description="Provider config object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Profile"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Provider object fields to return.",
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
     *     tags={"Provider"},
     *     path="/provider/{provider_id}/profile/_search",
     *     summary="Returns a list providers configs in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.providerConfig.searchProfile",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="provider_id",
     *        in="path",
     *        description="Id of the provider whos config should be searched.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="provider config object",
     *        in="body",
     *        description="Provider config object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Profile"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Provider object fields to return.",
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
    public function searchProfile(Request $request)
    {
        return $this::handleRequest($request, new Profile);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Provider"},
     *     path="/provider/{provider_id}/profile",
     *     summary="Create a new provider config.",
     *     description="",
     *     operationId="api.providerConfig.createProfile",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="provider_id",
     *        in="path",
     *        description="Id of the provider to create a config for.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="provider config object",
     *        in="body",
     *        description="Provider config object to be created in the system. (The provider_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Profile"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Provider config object fields to return.",
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
    public function createProfile(Request $request)
    {
        return $this::handleRequest($request, new Profile);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Provider"},
     *     path="/provider/{provider_id}/profile/{id}",
     *     summary="Mark a provider config as deleted.",
     *     description="",
     *     operationId="api.providerConfig.deleteProfile",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="provider_id",
     *        in="path",
     *        description="Id of the provider whos config will be deleted.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the provider config to mark deleted.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Provider config object fields to return.",
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
    public function deleteProfile(Request $request)
    {
        return $this::handleRequest($request, new Profile);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Provider"},
     *     path="/provider/{provider_id}/profile/{id}",
     *     summary="Update a provider object.",
     *     description="",
     *     operationId="api.providerConfig.updateProfile",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="provider_id",
     *        in="path",
     *        description="Id of the provider whos config should be updated.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the provider config to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="provider config object",
     *        in="body",
     *        description="Provider object containing only the fields that need to be updated. (The patient_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Profile"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Provider config object fields to return.",
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
    public function updateProfile(Request $request)
    {
        return $this::handleRequest($request, new Profile);
    }

    private function exponentSolver($base = 10, $value)
    {
        $value = (int) $value;
        $base = (int) $base;
        if ($base === -1) {
            return $value;
        } elseif ($value < $base) {
            // the .5 here is just an arbitrary decimal that will signal an invalid base
            return $value === 1 ? 0 : .5;
        }
        $exponent = log($value, $base);
        return $exponent;
    }

    private function profileSolver($profile)
    {
        $Rate = null;
        foreach ($profile as $step) {
            $isExponent10 = fmod($this::exponentSolver(10, $step['dilution']), 1) < .001;
            $isExponent5 = fmod($this::exponentSolver(5, $step['dilution']), 1) < .001;

            if ($Rate === -1) {
                // we previously found a dilution that couldn't be handled by a
                // 5 or 10 rate so the profile must stay as custom
                $Rate = -1;
                break;
            } elseif ($isExponent10) {
                // might be 10
                if ($isExponent5) {
                    // might be 5 or 10... we can't tell so return null for now
                    continue;
                } elseif ($Rate !== 5) {
                    // 10 returned int but 5 didn't so the profile is 10 (as long as we didnt
                    // previously calculate it to be 5)
                    $Rate = 10;
                    continue;
                } else {
                    // this iteration of the profile looked like 10 but the last iteration
                    // looked like 5 so it must be custom dilutions some of which happen
                    // to match both 5 and 10 just by chance
                    $Rate = -1;
                    break;
                }
            } elseif ($isExponent5 && $Rate !== 10) {
                // 5 returned an int but 10 didn't so its probably 5 (as long as the last
                // iteration didn't find 10)
                $Rate = 5;
                continue;
            } else {
                // it was neither 5 or 10 (or different iterations have calculated both 5 and 10)
                // so it must be -1
                $Rate = -1;
                break;
            }
        }
        return $Rate === null ? 10 : $Rate;
    }

    private function rgb ($r, $g, $b)  {
        return ((($r << 8) + $g) << 8) + $b;
      }

    private function colorValue ($name) {
        $finalColor = $name;
        switch (strtoupper($name)) {
            case 'RED':
                $finalColor = $this::rgb(255, 0, 0);
                break;
            case 'BLUE':
                $finalColor = $this::rgb(0, 0, 255);
                break;
            case 'GRN':
                $finalColor = $this::rgb(0, 128, 0);
                break;
            case 'YLW':
                $finalColor = $this::rgb(255, 255, 0);
                break;
            case 'ORNG':
                $finalColor = $this::rgb(255, 165, 0);
                break;
            case 'WHT':
                $finalColor = $this::rgb(255, 255, 255);
                break;
            case 'LTGR':
                $finalColor = $this::rgb(144, 238, 144);
                break;
            case 'LTBL':
                $finalColor = $this::rgb(173, 216, 230);
                break;
            case 'SLVR':
                $finalColor = $this::rgb(192, 192, 192);
                break;
            case 'PRPL':
                $finalColor = $this::rgb(128, 0, 128);
                break;
            case 'PINK':
                $finalColor = $this::rgb(255, 192, 203);
                break;
            case 'GOLD':
                $finalColor = $this::rgb(192, 96, 0);
                break;
            case 'UNDEFINED':
                $finalColor = $this::rgb(0, 0, 0);
                break;
        }

        return $finalColor;
    }

    protected function fixWriteRequest(Request $request)
    {
        $profile = $request->input('dilution_steps');
        if (!is_null($profile)) {
            $profileRate = $this::profileSolver($profile);
            $colors = [];
            $colorNames = [];
            $billrates = [];
            $dilutions = [];
            $expirations = [];
            for ($i=0; $i < 8; $i++) {
                if (isset($profile[$i])) {
                    $colorName = isset($profile[$i]['color_name']) ? $profile[$i]['color_name'] : '';
                    array_push($colorNames, $colorName);
                    $color = $this::colorValue($colorName);
                    array_push($colors, $color);
                    $billrate = isset($profile[$i]['bill_rate']) ? $profile[$i]['bill_rate'] : ' ';
                    array_push($billrates, $billrate);
                    $dilution = isset($profile[$i]['dilution']) ? $this::exponentSolver($profileRate, $profile[$i]['dilution']) : '8';
                    array_push($dilutions, $dilution);
                    $expiration = isset($profile[$i]['expiration']) ? $profile[$i]['expiration'] : '0';
                    array_push($expirations, $expiration);
                } else {
                    array_push($colors, '0');
                    array_push($colorNames, '');
                    array_push($billrates, ' ');
                    array_push($dilutions, '8');
                    array_push($expirations, '0');
                }
            }
            $colors = implode(',', $colors);
            $colorNames = implode(',', $colorNames);
            $billrates10 = $profileRate !== 5 ? implode(',', $billrates) : ' , , , , , , , ';
            $billrates5 = $profileRate === 5 ? implode(',', $billrates) : ' , , , , , , , ';
            $dilutions10 = $profileRate !== 5 ? implode(',', $dilutions) : '8,8,8,8,8,8,8,8';
            $dilutions5 = $profileRate === 5 ? implode(',', $dilutions) : '8,8,8,8,8,8,8,8';
            $expirations10 = $profileRate !== 5 ? implode(',', $expirations) : '0,0,0,0,0,0,0,0';
            $expirations5 = $profileRate === 5 ? implode(',', $expirations) : '0,0,0,0,0,0,0,0';
            $request->merge([
                'color' => $colors,
                'colorNames' => (string)$colorNames,
                'billrate10' => (string)$billrates10,
                'billrate5' => (string)$billrates5,
                'dilutions10' => (string)$dilutions10,
                'dilutions5' => (string)$dilutions5,
                'expirations10' => (string)$expirations10,
                'expirations5' => (string)$expirations5,
                'dilution_rate' => $profileRate
            ]);
        }
        if (isset($this->RequestOptions->provider_id)) {
            // force provider_id to match url
            $request->merge([
                'provider_id' => $this->RequestOptions->provider_id
            ]);
        }
    }

    protected function queryWith($Query)
    {
        return $Query->with(['providerDef']);
    }

    protected function queryModifier($Query)
    {
        return $Query->where('deleted', 'F');
    }

    protected function queryWhere($Query)
    {
        if (isset($this->RequestOptions->provider_id)) {
            $Query = $Query->where('provider_id', $this->RequestOptions->provider_id);
        }
        return $Query;
    }

    /**
     * Create provider defaults for a newly created profile
     * @param  profile $Profile Profile that defaults are being created for
     * @return null
     */
    private function createProvDefaults(profile $Profile)
    {
        //get list of extracts and create provider defaults for each
        $ExtractList = Extract::where('deleted', 'F')->select('extract_id')->get();

        foreach ($ExtractList as $Extract) {
            $ProvDef = new providerDef();
            $ProvDef->provider_config_id = $Profile->provider_config_id;
            $ProvDef->provider_id = $Profile->provider_id;
            $ProvDef->extract_id = $Extract['extract_id'];
            $ProvDef->dose = '0.00';
            $ProvDef->deleted = 'F';
            $ProvDef->save();
        }

        return null;
    }
    /**
     * Update provider defaults for a provider
     * @param  array $ProviderDefaults Array of provider_defaults that are to be saved
     * @return null
     */
    private function updateProvDefaults (array $ProviderDefaults)
    {
        //update provider defaults for each mentioned extract

        foreach ($ProviderDefaults as $providerDefault) {
            $ProvDefs = ProviderDef::where('deleted', 'F')->where('provider_def_id', $providerDefault['provider_def_id'])->get();
            if (count ($ProvDefs) > 0) {
                $ProvDef = $ProvDefs[0];
                if (isset ($providerDefault['dose'])) {
                    $ProvDef->dose = $providerDefault['dose'];
                }
                if (isset ($providerDefault['inseasonstart'])) {
                    $ProvDef->inseasonstart = $providerDefault['inseasonstart'];
                }
                if (isset ($providerDefault['inseasonend'])) {
                    $ProvDef->inseasonend = $providerDefault['inseasonend'];
                }
                if (isset ($providerDefault['outdates10'])) {
                    $ProvDef->outdates10 = $providerDefault['outdates10'];
                }
                if (isset ($providerDefault['outdates5'])) {
                    $ProvDef->outdates5 = $providerDefault['outdates5'];
                }
                $ProvDef->save();
            }
        }

        return null;
    }


    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);


        //If we are saving something with a null id it must have been a create
        //so use the object's primary key to find the created id.
        $isCreate = is_null($this->RequestOptions->id);

        //Do the following steps so the profile and its provider_defaults get saved in a transaction
        DB::transaction(function () use ($Object, $request, $isCreate) {
            $Object->save();

            if ($isCreate) {
                //create provider defaults
                $this->createProvDefaults($Object);
            }
            else {
                //update provider defaults
                if (isset ($request->provider_def)) {
                    $this->updateProvDefaults($request->provider_def);
                }
            }
        });

        $primaryId = $isCreate ? $Object[$Object->getKeyName()] : $this->RequestOptions->id;

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($primaryId);
        return $this->finishAndFilter($newObject);
    }

    protected function finalize($Object)
    {
        $Profile = [];
        $Rate = (int)$Object->profileRate;

        $Dilutions = $Rate === 5 ? $Object->dilutions5 : $Object->dilutions10;
        $Expirations = $Rate === 5 ? $Object->expirations5 : $Object->expirations10;
        $Billrates = $Rate === 5 ? $Object->billrate5 : $Object->billrate10;
        $ColorNames = $Object->colorNames;
        $Colors = $Object->color;
        $SortAsc = $Object->numorder === 'ascending_dilution';

        unset($Object->dilutions10);
        unset($Object->dilutions5);
        unset($Object->expirations10);
        unset($Object->expirations5);
        unset($Object->billrate10);
        unset($Object->billrate5);
        unset($Object->colorNames);
        unset($Object->color);
        unset($Object->profileRate);

        $Dilutions = !is_null($Dilutions) ? explode(',', $Dilutions) : [8,8,8,8,8,8,8,8];
        $Expirations = !is_null($Expirations) ? explode(',', $Expirations) : [0,0,0,0,0,0,0,0];
        $Billrates = !is_null($Billrates) ? explode(',', $Billrates) : [' ',' ',' ',' ',' ',' ',' ',' '];
        $ColorNames = !is_null($ColorNames) ? explode(',', $ColorNames) : ['','','','','','','',''];
        $Colors = !is_null($Colors) ? explode(',', $Colors) : [' ',' ',' ',' ',' ',' ',' ',' '];

        // because dilutions are padded with 8's we will trim all of the 8s from the end
        // of the dilution array and use that to determine the profile length
        while (sizeOf($ColorNames) > 0 && $ColorNames[sizeOf($ColorNames) - 1] === "") {
            array_pop($ColorNames);
        }

        for ($i=0; $i < sizeOf($ColorNames); $i++) {
            $Profile[$i] = app()->make('stdClass');
            $Profile[$i]->dilution = $Rate === -1 ? (int) $Dilutions[$i] : pow($Rate, (int) $Dilutions[$i]);
            if ($Expirations[$i] !== 0) {
                $Profile[$i]->expiration = $Expirations[$i];
            }
            if ($Billrates[$i] !== ' ') {
                $Profile[$i]->bill_rate = $Billrates[$i];
            }
            if ($ColorNames[$i] !== '') {
                $Profile[$i]->color = $Colors[$i];
            }
            // no need for for if on color_names since it is used to limit the array size
            $Profile[$i]->color_name = $ColorNames[$i];
        }
        // now sort by dilution based on numOrder (SortAsc)
        usort($Profile, function ($a, $b) use ($SortAsc) {
            $A = (int) $a->dilution;
            $B = (int) $b->dilution;
            if ($A === $B) {
                return 0;
            } elseif ($SortAsc) {
                return $A < $B ? -1 : 1;
            } else {
                return $A > $B ? -1 : 1;
            }
        });

        $Object->dilution_steps = $Profile;

        return $Object;
    }
}

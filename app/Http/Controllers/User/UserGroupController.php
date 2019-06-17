<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserGroup;
use DB;

class UserGroupController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"UserGroup"},
    *     path="/user_group",
    *     summary="Returns a list of all user_groups in the system.",
    *     description="",
    *     operationId="api.user_group.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="UserGroup object fields to return.",
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
        return $this::handleRequest($request, new UserGroup);
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"UserGroup"},
    *     path="/user_group/{id}",
    *     summary="Returns a single user_group in the system identified by {id}.",
    *     description="",
    *     operationId="api.user_group.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the user_group to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="UserGroup object fields to return.",
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
    public function getUserGroup(request $request)
    {
        return $this::handleRequest($request, new UserGroup);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"UserGroup"},
     *     path="/user_group/_search",
     *     summary="Returns a list of user_groups in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.user_group.searchUserGroup",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="user_group object",
     *        in="body",
     *        description="UserGroup object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/UserGroup"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="UserGroup object fields to return.",
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
    public function searchUserGroup(request $request)
    {
        return $this::handleRequest($request, new UserGroup);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"UserGroup"},
    *     path="/user_group",
    *     summary="Create a new user_group.",
    *     description="",
    *     operationId="api.user_group.createUserGroup",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="UserGroup object to be created in the system. (The user_group_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/UserGroup"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="UserGroup object fields to return",
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
    public function createUserGroup(Request $request)
    {
        return $this::handleRequest($request, new UserGroup);
    }

    /**
    * Delete an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Delete(
    *     tags={"UserGroup"},
    *     path="/user_group/{id}",
    *     summary="Mark a user_group as deleted.",
    *     description="",
    *     operationId="api.user_group.deleteUserGroup",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the user_group to mark deleted.",
    *        required=true,
    *        type="integer",
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="UserGroup object fields to return.",
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
    public function deleteUserGroup(request $request)
    {
        return $this::handleRequest($request, new UserGroup);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"UserGroup"},
    *     path="/user_group/{id}",
    *     summary="Update a user_group object.",
    *     description="",
    *     operationId="api.user_group.updateUserGroup",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the user_group to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="user_group object",
    *        in="body",
    *        description="UserGroup object containing only the fields that need to be updated. (The user_group_id property cannot be updated and will be ignored)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/UserGroup"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="UserGroup object fields to return.",
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
    public function updateUserGroup(Request $request)
    {
        return $this::handleRequest($request, new UserGroup);
    }

    protected function validateAndSave(request $request, $Object = null)
    {
        if ($this->getLock()) {
            $request = $this->fixCreatedUpdatedInfo($request);

            $PrivilegeErrors = [];
            if (!is_null($request->input('privileges'))) {
                $Privileges = $request->input('privileges');
                $UserGroupPrivilege = new UserGroupPrivilege;
                foreach ($Privileges as $PrivilegeIdx => $Privilege) {
                    // validate each user group assignment and add the errors
                    // to the array if present
                    // validation will expect a user_id but the user_id should
                    // be assumed based on the user route called so manually
                    // apply it before validation.
                    $Privilege['user_group_id'] = $this->RequestOptions->user_id;
                    if (!$UserGroupPrivilege->validate($Privilege, null)) {
                        $PrivilegeErrors['privilege_'.$PrivilegeIdx] = $UserGroupPrivilege->errors();
                    }
                }
            }

            if ($Object->validate($request->all(), $this->RequestOptions->id) && sizeOf($PrivilegeErrors) == 0) {
                // all validation checked out so save and query
                return $Object = $this->saveAndQuery($request, $Object);
            } else {
                $Errors = $Object->errors();
                $Errors['privileges'] = $PrivilegeErrors;
                return response()->json($Errors, 400);
            }
        } else {
            return response()->json('Another user currently owns one or more locks required to perform this action. Please try again later.', 401);
        }
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);
        // since this effects multiple rows between the user and userGroups
        // we wrap it in a transaction to ensure every thing completes or
        // nothing does
        DB::transaction(function () use ($Object, $request) {
            $query = $Object->save();

            // now that the user has been saved, we need to save and update
            // all of the groups. They were previously validated so we just
            // need to manually add the user_id again as before. Then we need
            // to delete any existing group assignments and update them to the
            // new settings. We should only do this if groups is present in the
            // request though.
            if (!is_null($request->input('privilges'))) {
                $Object->userGroups()->delete();
                foreach ($request->input('privilges') as $Privilege) {
                    $UserGroupPrivilege = new UserGroupPrivilege;
                    $UserGroupPrivilege->user_id = $this->RequestOptions->user_id;
                    $UserGroupPrivilege->user_group_id = $Privilege['privilege_id'];
                    $UserGroupPrivilege->save();
                }
            }
        });

        //If we are saving something with a null id it must have been a create
        //so use the objects primary key to find the created id.
        $primaryId = is_null($this->RequestOptions->id) ? $Object[$Object->getKeyName()] : $this->RequestOptions->id;

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($primaryId);
        return $this->finishAndFilter($newObject);
    }

    protected function queryWith($Query)
    {
        return $Query->with(['privileges']);
    }
}

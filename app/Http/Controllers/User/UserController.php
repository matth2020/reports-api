<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserGroupUser;
use App\Models\User;
use DB;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"User"},
    *     path="/user",
    *     summary="Returns a list of all users in the system.",
    *     description="",
    *     operationId="api.user.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="User object fields to return.",
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
        return $this::handleRequest($request, new User);
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"User"},
    *     path="/user/{id}",
    *     summary="Returns a single user in the system identified by {id}.",
    *     description="",
    *     operationId="api.user.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the user to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="User object fields to return.",
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
    public function getUser(request $request)
    {
        if ($this->allowedToEdit($request, 'read')) {
            return $this::handleRequest($request, new User);
        } else {
            return response()->json('privilege_error: User does not have the required all_users_read privilege.', 401);
        }
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"User"},
     *     path="/user/_search",
     *     summary="Returns a list of users in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.user.searchUser",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="user object",
     *        in="body",
     *        description="User object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/swaggerUser"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="User object fields to return.",
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
    public function searchUser(request $request)
    {
        return $this::handleRequest($request, new User);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"User"},
    *     path="/user",
    *     summary="Create a new user.",
    *     description="",
    *     operationId="api.user.createUser",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="User object to be created in the system. (The user_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/swaggerUser"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="User object fields to return",
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
    public function createUser(Request $request)
    {
        $this->getRequestOptions($request);
        if ($request->input('password')) {
            $request->merge([
                'password' => crypt($request->input('password'), '$2a$10$Okp.dWAMf9fWjTGlW77MxOYDbbK81wA8YPSHjTTiohAFSiCAiJVF2'),
                // need to update to more secure "password_hash" below once labview
                // login is no longer required
                //'password' => password_hash($request->input('password'), PASSWORD_BCRYPT)
                // also update in ActiveDirectoryController.php
            ]);
        }
        return $this::handleRequest($request, new User);
    }

    /**
    * Delete an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Delete(
    *     tags={"User"},
    *     path="/user/{id}",
    *     summary="Mark a user as deleted.",
    *     description="",
    *     operationId="api.user.deleteUser",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the user to mark deleted.",
    *        required=true,
    *        type="integer",
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="User object fields to return.",
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
    public function deleteUser(request $request)
    {
        return $this::handleRequest($request, new User);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"User"},
    *     path="/user/{id}",
    *     summary="Update a user object.",
    *     description="",
    *     operationId="api.user.updateUser",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the user to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="user object",
    *        in="body",
    *        description="User object containing only the fields that need to be updated. (The user_id property cannot be updated and will be ignored)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/swaggerUser"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="User object fields to return.",
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
    public function updateUser(Request $request)
    {
        if ($this->allowedToEdit($request, 'read')) {
            $this->getRequestOptions($request);
            if ($request->input('password')) {
                $request->merge([
                'password' => crypt($request->input('password'), '$2a$10$Okp.dWAMf9fWjTGlW77MxOYDbbK81wA8YPSHjTTiohAFSiCAiJVF2'),
                // need to update to more secure "password_hash" below once labview
                // login is no longer required
                //'password' => password_hash($request->input('password'), PASSWORD_BCRYPT)
            ]);
            }
            return $this::handleRequest($request, new User);
        } else {
            return response()->json('privilege_error: User does not have the required all_users_update privilege.', 401);
        }
    }

    protected function validateAndSave(request $request, $Object = null)
    {
        if ($this->getLock()) {
            $request = $this->fixCreatedUpdatedInfo($request);

            $GroupErrors = [];
            if (!is_null($request->input('user_groups'))) {
                $Groups = $request->input('user_groups');
                $UserGroupUser = new UserGroupUser;
                foreach ($Groups as $GroupIdx => $Group) {
                    // validate each user group assignment and add the errors
                    // to the array if present
                    // validation will expect a user_id but the user_id should
                    // be assumed based on the user route called so manually
                    // apply it before validation.
                    $Group['user_id'] = $this->RequestOptions->user_id;
                    if (!$UserGroupUser->validate($Group, null)) {
                        $GroupErrors['group_'.$GroupIdx] = $UserGroupUser->errors();
                    }
                }
            }

            if ($Object->validate($request->all(), $this->RequestOptions->id) && sizeOf($GroupErrors) == 0) {
                // all validation checked out so save and query
                return $Object = $this->saveAndQuery($request, $Object);
            } else {
                $Errors = $Object->errors();
                $Errors->toArray();
                $Errors['user_groups'] = $GroupErrors;
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
            if (!is_null($request->input('user_groups'))) {
                $Object->userGroupsAssignments()->delete();
                foreach ($request->input('user_groups') as $Group) {
                    $UserGroupUser = new UserGroupUser;
                    $UserGroupUser->user_id = $this->RequestOptions->user_id;
                    $UserGroupUser->user_group_id = $Group['user_group_id'];
                    $UserGroupUser->save();
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

    protected function allowedToEdit(request $request, $type)
    {
        $user = $request->user();
        $this->getRequestOptions($request);

        if ($user->user_id == $this->RequestOptions->user_id) {
            return true;
        } else {
            return $user->hasPrivilege('all_users_'.$type);
        }
    }

    protected function queryWith($Query)
    {
        return $Query->with(['configs','userGroups']);
    }

    protected function queryModifier($Query)
    {
        return $Query->where('deleted', 'F');
    }
}

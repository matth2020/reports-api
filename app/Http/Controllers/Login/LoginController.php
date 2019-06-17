<?php

namespace App\Http\Controllers\Login;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Login;

class LoginController extends Controller
{
    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Login"},
     *     path="/login",
     *     summary="Create a login object.",
     *     description="Create a new login entry.",
     *     operationId="api.login.createLogin",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="login object",
     *        in="body",
     *        description="Login object containing only the fields that need to be updated. (The login_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/WaitList"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Login object fields to return.",
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
    public function createLogin(Request $request)
    {
        $this->getRequestOptions($request);
        try {
            $Patient = Patient::findOrFail($request->input('patient_id'));
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located.', 404);
        }
        if (!is_null($Patient->displayname) && $Patient->displayname !== '') {
            $DisplayName = $Patient->displayname;
        } else {
            $DisplayName = strtoupper(substr($Patient->firstname, 0, 1).'. '.substr($Patient->lastname, 0, 1).'.');
        }
        $State = isset($request->state) ? $request->state : 'waiting_for_injection';
        $Now = \DB::select('select now()')[0]->{'now()'};
        $request->merge([
            "name" => $DisplayName,
            "login_time" => $Now,
            "state" => $State
        ]);
        return $this->handleRequest($request, new Login);
    }
    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Login"},
     *     path="/logout",
     *     summary="Close a login object.",
     *     description="Find and close a login object",
     *     operationId="api.login.createLogout",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="login object",
     *        in="body",
     *        description="Login object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card) (The login_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/WaitList"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Login object fields to return.",
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
    public function createLogout(Request $request)
    {
        $this->getRequestOptions($request);
        $PatientId = $request->input('patient_id');
        try {
            $Login = Login::where('patient_id', $PatientId)->where('state', '<>', '0')->orderBy('loginTime', 'desc')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error'=>['You are not currently logged into the system.']], 400);
        }
        $Now = \DB::select('select now()')[0]->{'now()'};
        $request->merge([
            'state' => 'logged_out',
            'last_departure_attempt' => $Now
        ]);
        if ($Login->validate($request->all(), $this->RequestOptions->id)) {
            return $this->saveAndQuery($request, $Login);
        } else {
            // updated the row to reflect the attempted logout
            $Login->timeLeft = $Now;
            $Login->save();
            return response()->json($Login->errors(), 400);
        }
    }
    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Login"},
     *     path="/login/new_patient",
     *     summary="Create a new patient login object.",
     *     description="Create a new login patient entry.",
     *     operationId="api.login.createNewPatientLogin",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="login object",
     *        in="body",
     *        description="Login object containing only the fields that need to be updated. (The login_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/WaitList"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Login object fields to return.",
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
    public function createNewPatientLogin(Request $request)
    {
        $this->getRequestOptions($request);
        try {
            $Patient = Patient::where('displayname', 'manualIn')->where('archived', 'T')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located.', 404);
        }
        $Now = \DB::select('select now()')[0]->{'now()'};
        $request->merge([
            "patient_id" => $Patient->patient_id,
            "login_time" => $Now,
            "manual_login" => true
        ]);
        return $this->handleRequest($request, new Login);
    }
}

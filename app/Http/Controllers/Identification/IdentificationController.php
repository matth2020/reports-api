<?php

namespace App\Http\Controllers\Identification;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Models\Identification;
use App\Models\Patient;
use Config;
use Log;

class IdentificationObject
{
    public $dbUser;
    public $dbPass;
    public $dbHost;
    public $dbName;

    public function __construct()
    {
        $this->dbUser = Config::get('tenancy.username');
        $this->dbPass = Config::get('tenancy.password');
        $this->dbHost = Config::get('tenancy.host');
        $this->dbName = (Config::get('tenancy.multiTenant') && env('APP_ENV') != 'testing') ? explode(".", $_SERVER['HTTP_HOST'])[0] : Config::get('tenancy.primary_db');
    }
}

class IdentificationController extends Controller
{
    private static function apiMessages($index)
    {
        switch ($index) {
            case 'cantIdentify':
                return ['identity'=>['Unable to identify. Please try again or choose another identification method.']];
            case 'multipleResults':
                return ['identity'=>['Multiple patients returned']];
            case 'serverNotConfigured':
                return ['identity'=>['Finger print identification server not configured.']];
            default:
                return ['identity'=>['An unknown error occurred']];
        }
    }
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Post(
     *     tags={"Identification"},
     *     path="/identification/idcode",
     *     summary="Returns id of identified patient.",
     *     description="",
     *     operationId="api.identification.identifyPatientId",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="identification object",
     *        in="body",
     *        description="Object containing idcode and last 4 of phone used to identify patient",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/IdentificationIdSwagObj"),
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
    *     )
    * )
    */

 
    public function identifyPatientID(request $request)
    {
        $Errors = $this->validateIdentificationID($request);
        if ($Errors) {
            return response()->json($Errors, 400);
        }

        $Patient = Patient::whereRaw('right(phone,4) = ' . $request['phone'])
                            ->where('phone', '<>', '')
                            ->where('idcode', $request['idcode'])
                            ->get();

        if ($Patient->count() == 1) {
            $IdentObj = app()->make('stdClass');
            $IdentObj->patient_id = $Patient[0]->patient_id;
            $IdentObj->displayname = $Patient[0]->displayname;
            $IdentObj->lock_on_box3 = $Patient[0]->getLockOnBox3() ? 'T': 'F';
            return response()->json($IdentObj);
        } elseif ($Patient->count() == 0) {
            $error = $this->apiMessages('cantIdentify');
            return response()->json($error, 400);
        } else {
            $error = $this->apiMessages('multipleResults');
            return response()->json($error, 400);
        }
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Post(
     *     tags={"Identification"},
     *     path="/identification/fingerprint",
     *     summary="Returns id of identified patient.",
     *     description="",
     *     operationId="api.identification.identifyPatientFinger",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fmd",
     *        in="body",
     *        description="Identification FMD string.",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/FMDSwagObj"),
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
    *     )
    * )
    */
    public function identifyPatientFinger(request $request)
    {
        $Errors = $this->validateIdentification($request);
        if ($Errors) {
            return response()->json($Errors);
        }

        $SendObj = new IdentificationObject();
        $SendObj->fmd = $request['fmd'];
        $SendObj->action = 'identify';

        $response = $this::scanRTE($SendObj)->getData();
        try {
            $Patient = Patient::findOrFail($response->patient_id);
        } catch (ModelNotFoundException $e) {
            $error = $this->apiMessages('cantIdentify');
            return response()->json($error, 400);
        }
        $response->displayname = $Patient->displayname;
        $response->lock_on_box3 = $Patient->getLockOnBox3() ? 'T' : 'F';

        return response()->json($response);
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Post(
     *     tags={"Identification"},
     *     path="/patient/{id}/identification/verify",
     *     summary="Returns a list of all identifications in the system.",
     *     description="",
     *     operationId="api.identification.verifyPatient",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fmd",
     *        in="body",
     *        description="Identification FMD string.",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/FMDSwagObj"),
     *     ),
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose fingerprint is being validated.",
     *         required=true,
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

 
    public function verifyPatient(request $request)
    {
        $Errors = $this->validateIdentification($request);
        if ($Errors) {
            return response()->json($Errors);
        }

        $SendObj = new IdentificationObject();
        $SendObj->fmd = $request['fmd'];
        $SendObj->action = 'verify';
        $SendObj->patient_id = $this->RequestOptions->patient_id;

        $response = $this::scanRTE($SendObj);

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Post(
     *     tags={"Identification"},
     *     path="/patient/{patient_id}/identification/enroll",
     *     summary="Create an enrollment.",
     *     description="",
     *     operationId="api.identification.enrollPatient",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fmd",
     *        in="body",
     *        description="One or more concatenated fmds to build an enrollment fmd. If more than one fmd is provided, they should be concatenated into a single string and seperated by \r\n",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/FMDSwagObj"),
     *     ),
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose fingerprint is being validated.",
     *         required=true,
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
    public function enrollPatient(request $request, $patient_id)
    {
        $Errors = $this->validateIdentification($request);
        if ($Errors) {
            return response()->json($Errors);
        }

        $SendObj = new IdentificationObject();
        $SendObj->fmd = $request['fmd'];
        $SendObj->action = 'enroll';
        $SendObj->patient_id = $patient_id;
        $SendObj->finger = $request['finger'];

        $response = $this::scanRTE($SendObj);

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse,
     * @SWG\Get(
     *     tags={"Identification"},
     *     path="/patient/{id}/identification/enroll",
     *     summary="Returns a list of enrolled fingers for the patient.",
     *     description="",
     *     operationId="api.identification.getEnrolledFingers",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Clinic object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of the patient whose fingerprint is being validated.",
     *         required=true,
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
    public function getEnrolledFingers(request $request)
    {
        $this::getRequestOptions($request, new Identification);

        $Enrollments = Identification::where('patient_id', $this->RequestOptions->patient_id)
            ->get();

        return $this->finishAndFilter($Enrollments);
    }


    /**
     * Save or update a identification object
     * @param  request $request the API request
     * @param  identification  $Identification  Identification object to be saved or updated
     * @param      $id      identification_id to update (null if creation)
     * @return identification           saved identification object
     */
    // private function saveIdentification(request $request, identification $Identification = null)
    // {
    //     $Identification = is_null($Identification) ? new Identification() : $Identification;
    //     //Update the provided identification object with all of the appropriate
    //     //values from the request
    //     $Identification = $this->APItoDB($request, $Identification);

    //     $Identification->save();

    //     return $Identification;
    // }

    /**
     * Validate properties of identification request before saving
     * @param  request $request API request
     * @param      $id      id of the identification (null if create)
     * @return null
     */
    private function validateIdentification(request $request)
    {
        //rules for basic validation
        $Rules = [
            'fmd' => array('validFmd','required')
        ];

        //custom messages for validation errors
        $Messages = [
            'valid_fmd' => 'The :attribute field is not a valid FMD xml string. Saw'.$request['fmd'],
            'required' => 'The :attribute field is required.'
        ];

        //do validation
        $Validation = \Validator::make($request->all(), $Rules, $Messages);

        $Errors = $Validation->errors();

        //if validation errors were detected, return them
        if (count($Errors) > 0) {
            return $Errors;
        }

        return false;
    }

    /**
     * Validate properties of identification idcode request
     * @param  request $request API request
     * @return null
     */
    private function validateIdentificationID(request $request)
    {
        //rules for basic validation
        $Rules = [
            'idcode' => array('digits:4','required'),
            'phone' => array('digits:4','required')
        ];

        //custom messages for validation errors
        $Messages = [
            'idcode' => 'The :attribute field must be 4 digits.',
            'required' => 'The :attribute field must be 4 digits'
        ];

        //do validation
        $Validation = \Validator::make($request->all(), $Rules, $Messages);

        $Errors = $Validation->errors();

        //if validation errors were detected, return them
        if (count($Errors) > 0) {
            return $Errors;
        }

        return false;
    }

    private static function scanRTE($SendObj)
    {
        $Command = 'XtractScanServer.exe';
        $Path = base_path('/resources/assets/');
        $DescriptorSpec = array(
           0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
           1 => array("pipe", "w")  // stdout is a pipe that the child will write to
        );

        if (file_exists($Path . $Command)) {
            $process = proc_open($Command, $DescriptorSpec, $pipes, $Path, null);
            //write to stdout
            fwrite($pipes[0], json_encode($SendObj));
            fclose($pipes[0]);

            //read from stin
            $response = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            proc_close($process);

            $result = json_decode($response);
            if (isset($result->error) && $result->error === "display_messages") {
                return response()->json([$result->messages], 400);
            }

            return response()->json($result);
        } else {
            $error = self::apiMessages('serverNotConfigured');
            return response()->json($error, 500);
        }
    }
}

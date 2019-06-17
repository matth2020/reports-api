<?php

namespace App\Http\Controllers\Technallergy;

use App\Http\Controllers\Injection\InjectionPlanController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Models\Technallergy;
use App\Models\Patient;

class TechnallergyController extends Controller
{
    public function __construct()
    {
        $this->config = app()->make('config');
        $this->token = $this->doAuth();
    }
    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Technallergy"},
     *     path="/technallergy/config",
     *     summary="Returns the providers technallergy configuration",
     *     description="",
     *     operationId="api.technallergy.config.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
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
    public function getConfig(request $request)
    {
        $config = $this->config;
        $Url = '/provider/'.$config['services.technallergy.technallergyProviderId'];
        $response = $this->technallergyGet($Url);
        return response()->json($response);
    }
    /**
     * Initialize a new user link
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Technallergy"},
     *     path="/patient/{patient_id}/technallergy/link",
     *     summary="Returns a technallergy link code",
     *     description="",
     *     operationId="api.Technallergy.newLink",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="Patient to link with",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
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
    public function link(request $request, $patient_id)
    {
        try {
            $Patient = Patient::findOrFail($patient_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }

        // $config = app()->make('config');
        $config = $this->config;
        $ProviderId = $config['services.technallergy.technallergyProviderId'];
        if (is_null($Patient->technallergy_id)) {
            //new link
            $Url = '/provider/' . $ProviderId . '/link';
        } else {
            //relink
            $Url = '/provider/' . $ProviderId . '/user/' . $Patient->technallergy_id . '/link';
        }
        $response = $this->technallergyPost($Url, []);
        $status = isset($response->status) ? $response->status : '';

        if ($status !== 'success') {
            if ($response->message === "Link not found") {
                //we tried relinking but there was no link so try
                //creating one
                $Url = '/provider/' . $ProviderId . '/link';
                $response = $this->technallergyPost($Url, []);
                if ($response->status !== 'success') {
                    return response()->json('Unable retrieve code.', 500);
                }
            } else {
                return response()->json('Unable retrieve code.', 500);
            }
        }

        $data = isset($response->data) ? $response->data : [];
        $link = isset($data->link) ? $data->link : [];
        $linkCode = isset($link->link_code) ? $link->link_code : null;
        $userId = isset($link->user_id) ? $link->user_id : null;

        if (is_null($linkCode) || is_null($userId)) {
            return response()->json('Unable retrieve code.', 500);
        }
        if (is_null($Patient->technallergy_id)) {
            $Patient->technallergy_id = $userId;
            try {
                $Patient->save();
            } catch (QueryException $e) {
                //must not have that column
            }
        }

        return response()->json(['link_code' => $linkCode]);
    }

    /**
     * Sync injection history
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Technallergy"},
     *     path="/patient/{patient_id}/technallergy/sync",
     *     summary="returns success or failure",
     *     description="",
     *     operationId="api.Technallergy.sync",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="Patient to sync history for",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
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
    public function sync(request $request, $patient_id)
    {
        // Find the patient so we have the technallergy link
        try {
            $Patient = Patient::findOrFail($patient_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }

        // Fetch the injection plans
        $InjectionPlanController = new InjectionPlanController();
        $Url = '/patient/' . $patient_id .'/injectionplan';
        $fakeRequest = Request::create($Url, 'GET');
        $result = $InjectionPlanController->index($fakeRequest);
        $prescriptions = $result->getData();

        if (sizeOf($prescriptions) === 0) {
            return response()->json('Nothing to sync', 200);
        }
        
        // Create clean copy of injection plan in Technallergy format
        $cleanInjs = $this->cleanData($prescriptions);
        
        // Submit injections to technallergy
        $config = $this->config;
        $ProviderId = $config['services.technallergy.technallergyProviderId'];
        $Url = '/provider/' . $ProviderId . '/user/' . $Patient->technallergy_id . '/sync';

        $response = $this->technallergyPost($Url, ['data' => $cleanInjs]);
 
        $status = isset($response->status) ? $response->status : '';

        if ($status === 'success') {
            return response()->json('Sync successfull', 200);
        }

        return response()->json('Unable sync history.', 500);
    }

    private function cleanData($RxArray)
    {
        $CleanRxArray = [];
        foreach ($RxArray as $Rx) {
            $name = $Rx->name;
            $CleanInjArray = [];
            foreach ($Rx->dilution as $dilutionObj) {
                $dilution = strtolower($dilutionObj->dilution);
                $color = strtoupper($dilutionObj->color);
                foreach ($dilutionObj->data as $injection) {
                    $timestamp = $injection->date;
                    $dose = strtolower($injection->dose);
                    $predicted = strtolower($injection->type) === 'predicted';
                    $TechnallergyInj = app()->make('stdClass');
                    $TechnallergyInj->timestamp = $timestamp;
                    $TechnallergyInj->dose = $dose === 'ask' ? null : $dose;
                    $TechnallergyInj->dilution = $dilution === 'ask' || $dilution == 0 ? null : $dilution;
                    $TechnallergyInj->name = $name;
                    $TechnallergyInj->color = $color === 'BLK' ? null : $color;
                    $TechnallergyInj->predicted = $predicted;
                    array_push($CleanInjArray, $TechnallergyInj);
                }
            }
            $CleanRxArray[$name] = $CleanInjArray;
        }
        return $CleanRxArray;
    }

    private function technallergyPost($path, $payload, $auth = true)
    {
        $config = $this->config;
        $TechnallergyUrl = $config['services.technallergy.technallergyUrl'];
        $Url = $TechnallergyUrl . $path;
        $curlReq = curl_init();
        $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        ];
        if ($auth) {
            array_push($headers, 'Authorization: Bearer '.$this->token);
        }
        curl_setopt($curlReq, CURLOPT_URL, $Url);
        curl_setopt($curlReq, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlReq, CURLOPT_POST, 1);
        curl_setopt($curlReq, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curlReq, CURLOPT_RETURNTRANSFER, true);
        // Timeout in seconds
        curl_setopt($curlReq, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($curlReq);

        return json_decode($response);
    }

    private function technallergyGet($path, $auth = true)
    {
        $config = $this->config;
        $TechnallergyUrl = $config['services.technallergy.technallergyUrl'];
        $Url = $TechnallergyUrl . $path;
        $curlReq = curl_init();
        $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        ];
        if ($auth) {
            array_push($headers, 'Authorization: Bearer '.$this->token);
        }
        curl_setopt($curlReq, CURLOPT_URL, $Url);
        curl_setopt($curlReq, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlReq, CURLOPT_RETURNTRANSFER, true);
        // Timeout in seconds
        curl_setopt($curlReq, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($curlReq);

        return json_decode($response);
    }

    private function doAuth()
    {
        $config = $this->config;
        $ClientId = $config['services.technallergy.technallergyClientId'];
        $ClientSecret = $config['technallergy.technallergyClientSecret'];
        $payload = [
            'client_id' => $ClientId,
            'client_secret' => $ClientSecret,
            'grant_type' => 'client_credentials',
            'scopes' => '*'
        ];
        $response = $this->technallergyPost('/oauth/token', $payload, false);

        if (isset($response->access_token)) {
            return $response->access_token;
        }
        return -1;
    }
}

<?php

namespace App\Http\Controllers\Tracking;

use App\Http\Controllers\Config\ConfigController;
use App\Http\Controllers\LockableController;
use App\Models\TrackingConfig;
use Illuminate\Http\Request;
use App\Models\Config;
use Log;

class TrackingConfigController extends LockableController
{
    public static $requiredLocks = ['trackingLock'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Tracking/Config"},
     *     path="/patient/{patient_id}/tracking_config",
     *     summary="Returns a list of all trackingconfigs in the system.",
     *     description="",
     *     operationId="api.trackingconfig.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose tracking config to view.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="TrackingConfig object fields to return.",
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
    public function index(request $request)
    {
        $SearchConfig = new Config();
        $this::getRequestOptions($request, $SearchConfig);

        $this->RequestOptions->config_id = $this->RequestOptions->id;
        $this->RequestOptions->id = null;

        //This endpoint is an alias for the config resource but specific to the trackingNames section so just make a call to that controller.
        $ConfigController = new ConfigController();

        $SearchConfig->section = 'trackingNames';

        $fakeRequest = Request::create('/v1/config/_search', 'POST', $SearchConfig->toArray());
        $data = new \Symfony\Component\HttpFoundation\ParameterBag;
        $data->add($SearchConfig->toArray());
        $fakeRequest->setJson($data);

        $result = $ConfigController->searchConfig($fakeRequest);
        
        $TrackingConfigs = $result->getData();

        foreach ($TrackingConfigs as $key => $TrackingConfig) {
            //for each system default config, see if there is a patient specific one and if so replace.
            $Config = TrackingConfig::where('trackingName', $TrackingConfig->name)
            ->where('patient_id', $this->RequestOptions->patient_id)
            ->first();

            if (!is_null($Config)) {
                $Config->trackingConfig_id = $TrackingConfig->config_id;
                $TrackingConfigs[$key] = $Config;
            }
        }
        return $this->finishAndFilter($TrackingConfigs);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Tracking/Config"},
     *     path="/patient/{patient_id}/tracking_config/{tracking_config_id}",
     *     summary="Returns a specific trackingconfig.",
     *     description="",
     *     operationId="api.trackingconfig.index",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose tracking config to view.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="tracking_config_id",
     *         in="path",
     *         description="The id of the tracking_config to view.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="TrackingConfig object fields to return.",
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
    public function getTrackingConfig(request $request)
    {
        $this::getRequestOptions($request, new TrackingConfig);

        $this->RequestOptions->config_id = $this->RequestOptions->id;
        $this->RequestOptions->id = null;

        $Config = $this->getPatientTrackingConfig();

        return $this->finishAndFilter($Config);
    }
    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Tracking/Config"},
     *     path="/patient/{patient_id}/tracking_config/{tracking_config_id}",
     *     summary="Mark a trackingconfig as deleted.",
     *     description="",
     *     operationId="api.trackingconfig.deleteTrackingConfig",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose tracking config to view.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="tracking_config_id",
     *         in="path",
     *         description="The id of the tradcking config row to delete.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="TrackingConfig object fields to return.",
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
    public function deleteTrackingConfig(request $request)
    {
        $this::getRequestOptions($request);

        $this->RequestOptions->config_id = $this->RequestOptions->id;
        $this->RequestOptions->id = null;

        $ConfigController = new ConfigController();
        
        $fakeRequest = Request::create('/v1/config/'.$this->RequestOptions->tracking_config_id, 'GET');
        $result = $ConfigController->getConfig($fakeRequest);
        
        $DefaultConfig = $result->getData();

        $Config = TrackingConfig::where('trackingName', $DefaultConfig->name)
            ->where('patient_id', $this->RequestOptions->patient_id)
            ->first();

        if (!is_null($Config)) {
            $Config->delete();
        }

        return $this->finishAndFilter($DefaultConfig);
    }
    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Tracking/Config"},
     *     path="/patient/{patient_id}/tracking_config/{tracking_config_id}",
     *     summary="Update a trackingconfig object.",
     *     description="",
     *     operationId="api.trackingconfig.updateTrackingConfig",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose tracking config to view.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="tracking_config_id",
     *         in="path",
     *         description="The id of the tracking config to update.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="trackingconfig object",
     *        in="body",
     *        description="Since there is only one tracking config for the entire system. This is the same as create. It will simply delete the existing config and create a new one.",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/TrackingConfig"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="TrackingConfig object fields to return.",
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
    public function updateTrackingConfig(Request $request)
    {
        $this::getRequestOptions($request);

        $this->RequestOptions->config_id = $this->RequestOptions->id;
        $this->RequestOptions->id = null;

        //override patient_id if set
        $request->merge(
            array(
                'patient_id' => $this->RequestOptions->patient_id,
                'config_id' => $this->RequestOptions->config_id //add only to be used in object validator
            )
        );


        $Config = $this->getPatientTrackingConfig();
        unset($Config->trackingConfig_id); //Ensure the fake tracking_config_id (config_id) doesn't get saved.

        return $this->validateAndSave($request, $Config);
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);
        $Object->save();

        $newObject = TrackingConfig::where('patient_id', $this->RequestOptions->patient_id)
            ->where('trackingName', $Object->trackingName)
            ->first();
        return $this->finishAndFilter($newObject);
    }

    private function getPatientTrackingConfig()
    {
        //This endpoint is an alias for the config resource but specific to the trackingNames section so just make a call to that controller.
        $ConfigController = new ConfigController();

        $fakeRequest = Request::create('/v1/config/'.$this->RequestOptions->tracking_config_id, 'GET');
        $result = $ConfigController->getConfig($fakeRequest);
        
        $DefaultConfig = $result->getData();
        $DefaultTrackingConfig = $this->buildConfig($DefaultConfig);

        $Config = TrackingConfig::where('trackingName', $DefaultConfig->name)
            ->where('patient_id', $this->RequestOptions->patient_id)
            ->first();

        $Config = is_null($Config) ? $DefaultTrackingConfig : $Config;

        return $Config;
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  TrackingConfig $TrackingConfig object returned from the database
     * @param  request $request API request used to build filter.
     * @return TrackingConfig object
     */
    protected function finalize($TrackingConfig)
    {
        if (!$TrackingConfig instanceof TrackingConfig) {
            //already a tracking config so just return it
            $TrackingConfig = $this->buildConfig($TrackingConfig);
        } else {
            if ($TrackingConfig->minimum === -1.0) {
                unset($TrackingConfig->minimum);
            }
            if ($TrackingConfig->maximum === -1.0) {
                unset($TrackingConfig->maximum);
            }
        }
        if (isset($this->RequestOptions->patient_id)) {
            $TrackingConfig = $this->patientSpecificCheck($TrackingConfig, $this->RequestOptions->patient_id);
        }
        $TrackingConfig->trackingConfig_id = isset($this->RequestOptions->config_id) ? $this->RequestOptions->config_id : $TrackingConfig->trackingConfig_id;

        $TrackingConfig->patient_id = $this->RequestOptions->patient_id;

        return $TrackingConfig;
    }

    private function buildConfig($TrackingConfig)
    {
        $Config = new TrackingConfig;
        $Config->trackingConfig_id = $TrackingConfig->config_id;
        $Config->trackingName = $TrackingConfig->name;
        $MinStart = strrpos($TrackingConfig->value, 'min=') + 4;
        $MaxStart = strrpos($TrackingConfig->value, 'max=') + 4;
        $Config->min = (float) substr($TrackingConfig->value, $MinStart, $MaxStart - $MinStart - 4);
        $Config->max = (float) substr($TrackingConfig->value, $MaxStart);

        //-1 indicates no min or max so just remove the values in this case.
        //its slow to do this in a completely separate loop but this will only
        //ever be preformed on a single object so its not a concern. Keeping it
        //separate make it more clear (since what we are doing doesn't make
        //much sense on its own.)
        if ($Config->minimum === -1.0) {
            unset($Config->minimum);
        }
        if ($Config->maximum === -1.0) {
            unset($Config->maximum);
        }
        return $Config;
    }

    /**
     * Check to see if this is a general trackingConfig or patient specific one
     * and modify the object accordingly
     * @param  TrackingConfig $TrackingConfig TrackingConfig to be checked
     * @param  int            $patient_id     Patient_id associated with the config
     * @return Resulting TrackingConfig object.
     */
    private function patientSpecificCheck($TrackingConfig, $patient_id = null)
    {
        //if this is a patient specific tracking config...
        if (!is_null($patient_id)) {
            $PatientConfig = TrackingConfig::where('patient_id', $patient_id)
                ->where('trackingName', $TrackingConfig->name)
                ->first();
            if (!is_null($PatientConfig)) {
                $TrackingConfig->patient_id = $patient_id;
                $TrackingConfig->minimum = (float) $PatientConfig->min;
                $TrackingConfig->maximum = (float) $PatientConfig->max;
            }
        }
        return $TrackingConfig;
    }
}

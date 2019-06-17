<?php

namespace App\Http\Controllers\Config;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Xpsprefs;
use App\Models\Xisprefs;
use App\Models\Version;
use App\Models\Config;
use App\Models\Units;

class ConfigController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Config"},
    *     path="/config",
    *     summary="Returns a list of all config entries in the system.",
    *     description="",
    *     operationId="api.config.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Config object fields to return.",
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
    *     )
    * )
    */
    public function index(request $request)
    {
        $response = $this::handleRequest($request, new Config);
        return $this::appendNonConfigs($response);
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Config"},
    *     path="/config/{id}",
    *     summary="Returns a single config in the system identified by {id}.",
    *     description="",
    *     operationId="api.config.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the config to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Config object fields to return.",
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
    public function getConfig(request $request)
    {
        return $this::handleRequest($request, new Config);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Config"},
     *     path="/config/_search",
     *     summary="Returns a list configs in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.config.searchConfig",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="config object",
     *        in="body",
     *        description="Config object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Config"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Config object fields to return.",
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
     *     )
     * )
     */
    public function searchConfig(request $request)
    {
        $response = $this::handleRequest($request, new Config);

        return $this::appendNonConfigs($response, $request->json('section'), $request->json('name'));
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Config"},
     *     path="/config",
     *     summary="Create a new config.",
     *     description="Since duplicate config entries are not allowed, this endpoint will fail if there is already one with the same name, section, and app.",
     *     operationId="api.config.createConfig",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="config object",
     *        in="body",
     *        description="Config object to be created in the system. (The config_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Config"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Config object fields to return.",
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
    public function createConfig(Request $request)
    {
        if ($request->has('image')) {
            return $this->imageConfig($request);
        }
        return $this::handleRequest($request, new Config);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Config"},
     *     path="/config/{id}",
     *     summary="Delete a config row.",
     *     description="Unlike most Xtract API calls, this will truly delete the config row (not just mark it as deleted='T').",
     *     operationId="api.config.deleteConfig",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the config row to mark deleted.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Config object fields to return.",
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
    public function deleteConfig(Request $request)
    {
        $this->getRequestOptions($request, new Config);
        // see if this is an actual image file that can be deleted
        try {
            $row = Config::findOrFail($this->RequestOptions->id);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located.', 404);
        }
        if ($row->section === 'image') {
            // delete the old image
            $config = app()->make('config');
            $url = $config->get('app.url');
            $oldFile = str_replace($url.'/storage/app/', '', $row->value);
            \Storage::delete($oldFile);
        }
        return $this::handleRequest($request, new Config);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"Config"},
    *     path="/config/{id}",
    *     summary="Update an existing config entry.",
    *     description="Since duplicate config entries are not allowed, this endpoint will fail if there is already one with the same name, section, and app.",
    *     operationId="api.config.updateConfig",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the config to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="config object",
    *        in="body",
    *        description="Config object containing only the fields that need to be updated. (The config_id property cannot be updated and will be ignored)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Config"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Config object fields to return.",
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
    public function updateConfig(Request $request)
    {
        if ($request->has('image')) {
            return $this->imageConfig($request);
        }
        return $this::handleRequest($request, new Config);
    }

    // special handling of image config items
    public function imageConfig(Request $request)
    {
        $this->getRequestOptions($request);
        // save the file to the apps directory
        $fileName = $request->image->store($request->input('app'));
        $config = app()->make('config');
        $url = $config->get('app.url');
        // set value field to be the url the image is available at
        $request->merge([
            'value' => $url.'/storage/app/'.$fileName
        ]);
        // see if there is an existing row for the image
        $row = Config::where('config_id', $this->RequestOptions->id)
            ->orWhere(function ($Query) use ($request) {
                $app = $request->input('app');
                $name = $request->input('name');
                $section = $request->input('section');
                if (!is_null($app)) {
                    $Query = $Query->where('app', $app);
                }
                if (!is_null($name)) {
                    $Query = $Query->where('name', $name);
                }
                if (!is_null($section)) {
                    $Query = $Query->where('section', $section);
                }
                return $Query;
            })->get();
        $Object = new Config;
        if ($row->count() > 1) { //user didn't provide enough info to identify a unique row
            return response()->json('The requested resource could not be located.', 404);
        } elseif ($row->count() < 1) { //row doesnt exist so create it
            return $this->createFromRequest($request, $Object);
        } else { // we found an existing row so update it
            $this->RequestOptions->id = $row[0]->config_id;
            // delete the old image
            $oldFile = str_replace($url.'/storage/app/', '', $row[0]->value);
            \Storage::delete($oldFile);
            return $this->updateFromRequest($request, $Object);
        }
    }

    /**
     * Someday once all config items are in the config table, everything below
     * this point can go away.
     */

    private static function appendNonConfigs($response, $section = null, $name = null)
    {
        if ($response->status() == 200) {
            //pull out the configs already generated
            $Configs = json_decode($response->content());
            //search for config items not in the config table
            $nonConfigs = self::nonConfigCheck($section, $name);
            //append nonConfigs
            $Configs = array_merge($Configs, $nonConfigs);
            return response()->json($Configs);
        } else {
            return $response;
        }
    }
    /**
     * Some config items should be stored in the config table of the DB but aren't
     * (yet) so until then, we need to handle searches for those separately to make
     * the complication invisible to the API user. This could be further optimized
     * but the real optimization is to store the data where it belongs so this can
     * go away all together.
     * @param  request $request API request
     * @return mixed            Config object or null
     */
    private static function nonConfigCheck($section, $name)
    {
        switch ($name) {
            case 'units':
                return self::getUnits();
            case 'ent':
            case 'ENT':
                return self::getEnt();
            case 'priority_names':
                return self::getPriorities();
            case 'reaction_names':
                return self::getReactionNames();
            case 'enforceLogout':
                return self::getEnforceLogout();
            case 'allowLogout':
                return self::allowLogout();
            case 'doQuestions':
                return self::doQuestions();
            case 'enforceQuestionnaire':
                return self::enforceQuestionnaire();
            case 'doDailyInjectionReport':
                return self::doDailyInjectionReport();
            case 'questionnaireIdConfirm':
                return self::questionnaireIdConfirm();
            case 'allowCreatePatient':
                return self::allowCreatePatient();
            case 'loginAppPIN':
                return self::getLoginAppPIN();
            default:
                switch ($section) {
                    case null:
                    case 'read_only':
                        if (!is_null($name)) {
                            return [];
                        }
                        $nonConfigs = array();
                        $nonConfigs = array_merge(
                            $nonConfigs,
                            self::getUnits(),
                            self::getEnt(),
                            self::getPriorities(),
                            self::getReactionNames(),
                            self::getEnforceLogout(),
                            self::allowLogout(),
                            self::doQuestions(),
                            self::enforceQuestionnaire(),
                            self::doDailyInjectionReport(),
                            self::questionnaireIdConfirm(),
                            self::allowCreatePatient(),
                            self::getLoginAppPIN()
                        );
                        return $nonConfigs;
                    default:
                        return [];
                }
        }
    }
    /**
     * Helpers to get the various config items from the db that aren't stored
     * in the config table. Someday these can all go away.
     */
    private static function allowCreatePatient()
    {
        $CreatePatient = explode(',', xpsprefs::first()->prefset1)[0];
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'allowCreatePatient';
        $nonConfig->value = $CreatePatient;
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function getPriorities()
    {
        $PriorityNames = Xpsprefs::firstOrFail();
        $PriorityNames = explode(',', $PriorityNames->priority_names);
        for ($i=0; $i < sizeOf($PriorityNames); $i++) {
            $name = $PriorityNames[$i];
            $PriorityNames[$i] = app()->make('stdClass');
            $PriorityNames[$i]->name = $name;
            $PriorityNames[$i]->id = $i;
        }
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'priority_names';
        $nonConfig->value = $PriorityNames;
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function getUnits()
    {
        $Units = Units::get();
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'units';
        $nonConfig->value = $Units;
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function getEnt()
    {
        $ENT = Version::firstOrFail();
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'ENT';
        $nonConfig->value = $ENT->ENT;
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function getReactionNames()
    {
        $ReactionStrings = Xisprefs::firstOrFail();
        $LocalNames = explode(',', $ReactionStrings->reactNamesL);
        $SystemicNames = explode(',', $ReactionStrings->reactNamesS);
        $Reactions = app()->make('stdClass');
        $Reactions->systemic = $SystemicNames;
        $Reactions->local = $LocalNames;
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'reaction_names';
        $nonConfig->value = $Reactions;
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function getEnforceLogout()
    {
        $XisPrefs = Xisprefs::firstOrFail();
        $PrefSet3 = explode(',', $XisPrefs->prefSet3);
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'enforceLogout';
        $nonConfig->value = strtoupper($PrefSet3[2]);
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function allowLogout()
    {
        $XisPrefs = Xisprefs::firstOrFail();
        $PrefSet3 = explode(',', $XisPrefs->prefSet3);
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'allowLogout';
        $nonConfig->value = strtoupper($PrefSet3[0]);
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function doQuestions()
    {
        $XisPrefs = Xisprefs::firstOrFail();
        $PrefSet3 = explode(',', $XisPrefs->prefSet3);
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'doQuestions';
        $nonConfig->value = strtoupper($PrefSet3[1]);
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function enforceQuestionnaire()
    {
        $XisPrefs = Xisprefs::firstOrFail();
        $PrefSet3 = explode(',', $XisPrefs->prefSet3);
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'enforceQuestionnaire';
        $nonConfig->value = strtoupper($PrefSet3[3]);
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function getLoginAppPIN()
    {
        $Prefs = Xisprefs::firstOrFail();
        $PIN = $Prefs->LoginPIN;

        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'loginAppPIN';
        $nonConfig->value = $PIN;
        $nonConfig->app = 'lobbyLogin';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function doDailyInjectionReport()
    {
        $XisPrefs = Xisprefs::firstOrFail();
        $PrefSet2 = explode(',', $XisPrefs->prefSet2);
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'doDailyInjectionReport';
        $nonConfig->value = strtoupper($PrefSet2[0]);
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
    private static function questionnaireIdConfirm()
    {
        $XisPrefs = Xisprefs::firstOrFail();
        $PrefSet2 = explode(',', $XisPrefs->prefSet2);
        $nonConfig = new Config();
        $nonConfig->section = 'read_only';
        $nonConfig->name = 'questionnaireIdConfirm';
        $nonConfig->value = strtoupper($PrefSet2[4]);
        $nonConfig->app = 'all';
        $nonConfig->config_id = '0';
        return [$nonConfig];
    }
}

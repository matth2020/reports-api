<?php

namespace App\Http\Controllers\Version;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Version;
use App\Models\Xisversion;
use App\Models\Xstversion;
use App\Models\XtractSchema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;

class VersionController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Version"},
    *     path="/version",
    *     summary="Returns a list of all versions in the system.",
    *     description="",
    *     operationId="api.version.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Version object fields to return.",
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
        $Version = app()->make('stdClass');
        $CreateTable = "Create Table";
        try {
            $Version->xps = Version::firstOrFail()->version;
        } catch (ModelNotFoundException $e) {
        }
        try {
            $Version->xis = XisVersion::firstOrFail()->version;
        } catch (ModelNotFoundException $e) {
        }

        // get database connection info api is using
        $config = app()->make('config');
        $database = app()->make('stdClass');
        $database->database = $config->get('database.connections.mysql.database');
        $database->host = $config->get('database.connections.mysql.host');
        $database->port = $config->get('database.connections.mysql.port');
        $Version->database = $database;
        $Version->api = $config->get('app.version');
            
        try {
            $Version->database->schema_check = app()->make('stdClass');
            $Version->database_schema = XtractSchema::firstOrFail()->version;
        } catch (ModelNotFoundException $e) {
        }

        // this query is like show tables but wont print views the way show tables would
        $Tables = array_reduce(DB::select("show full tables where Table_Type = 'BASE TABLE'"), function ($Arr, $Item) use (&$Version) {
            foreach ((array)$Item as $value) {
                array_push($Arr, $value);
                return $Arr;
            }
        }, []);

        $ValidSchema = base_path('app/Http/Controllers/Version/valid_schema.sql');
        $file_contents = file_get_contents($ValidSchema);
        $LinesArray = explode(';', $file_contents);
        $GoodCreates = array_filter($LinesArray, function ($value) {
            $regex = "/^\n?CREATE TABLE/";
            return preg_match($regex, $value);
        });

        // find the schema version
        $SchemaVersion = 'unknown';
        $VersionDetails = array_filter($LinesArray, function ($value) use (&$SchemaVersion) {
            $regex = "/^\n?INSERT INTO `xtract_schema` VALUES \(\'(.*)\',\'.*\'\)/";
            $result = preg_match_all($regex, $value, $schema);
            if (isset($schema[1][0])) {
                $SchemaVersion = $schema[1][0];
            }
            return $result;
        });
        $Version->database->required_schema = $SchemaVersion;
        foreach ($Tables as $Table) {
            // find the create statement for this table in the schema file
            $regex = "/^\n?CREATE TABLE `".$Table."`/";
            $PossibleSchemas = preg_grep($regex, $GoodCreates);
            $tableObj = app()->make('stdClass');
            $tableObj->version = 'unknown';
            $tableObj->ok = 'invalid';
            if (sizeof($PossibleSchemas) === 0) {
                //this table isn't in the schema
                $tableObj->ok = 'valid';
                $tableObj->note = 'Table not in base schema';
            } else {
                try {
                    // get test schema
                    $Test = DB::select('show create table '.$Table)[0]->$CreateTable;
                } catch (ModelNotFoundException $e) {
                    $tableObj->ok = 'invalid';
                    $tableObj->note = 'Table missing';
                }
                $Test = preg_replace("/ AUTO_INCREMENT=[0-9]*/", "", $Test);
                $PossibleSchema = array_pop($PossibleSchemas);
                $PossibleSchema = preg_replace("/ AUTO_INCREMENT=[0-9]*/", "", $PossibleSchema);
                $Result = crc32($PossibleSchema) === crc32("\n".$Test);
                if ($Result) {
                    $tableObj->version = $SchemaVersion;
                    $tableObj->ok = 'valid';
                } elseif (strlen($PossibleSchema) === strlen("\n".$Test)) {
                    $tableObj->version = $SchemaVersion;
                    $tableObj->ok = 'probable';
                    $tableObj->required = $PossibleSchema;
                    $tableObj->actual = "\n".$Test;
                    $tableObj->note = 'Correct length. Likely columns out of order.';
                } else {
                    $tableObj->required = $PossibleSchema;
                    $tableObj->actual = "\n".$Test;
                }
            }
            $Version->database->schema_check->$Table = $tableObj;
        }
        // demo of per table schema check via crc
        $Correct = "CREATE TABLE `units` (\n  `units_id` int(11) NOT NULL,\n  `name` varchar(32) NOT NULL,\n  PRIMARY KEY (`units_id`)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        return response()->json($Version);
    }
}

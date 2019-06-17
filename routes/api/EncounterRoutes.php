<?php

/*
|--------------------------------------------------------------------------
| Encounter Routes
|--------------------------------------------------------------------------
|
*/

$middleware = array();
$Config = app()->make('config');
if ($Config->get('app.api_log_enabled') != false) {
    array_push($middleware, 'api-log');
}

$middlewareWithAuth = $middleware;
if ($Config->get('app.oauth_enabled') != false) {
    array_push($middlewareWithAuth, 'auth:api');
}

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middleware, //No auth required
    'namespace' => 'App\Http\Controllers\Encounter'
    ],
    function () use ($router) {
        $router->post('patient/{patient_id}/encounter', 'EncounterController@createEncounter');
    }
);

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\Encounter'
    ],
    function () use ($router) {
        $router->get('patient/{patient_id}/encounter', 'EncounterController@index')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/encounter/{encounter_id}', 'EncounterController@getEncounter')->middleware('privilege:patient_read');
        $router->put('patient/{patient_id}/encounter/{encounter_id}', 'EncounterController@updateEncounter')->middleware('privilege:patient_read');
        $router->delete('patient/{patient_id}/encounter/{encounter_id}', 'EncounterController@deleteEncounter')->middleware('privilege:patient_read');
    }
);

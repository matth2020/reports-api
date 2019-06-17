<?php

/*
|--------------------------------------------------------------------------
| MixQueue Routes
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
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\MixQueue'
    ],
    function () use ($router) {
        $router->get('mix_queue', 'MixQueueController@index')->middleware('privilege:prescription_read');
        $router->get('mix_queue/{id}', 'MixQueueController@getMixQueue')->middleware('privilege:prescription_read');
        $router->get('patient/{patient_id}/mix_queue', 'MixQueueController@getPatientMixQueue')->middleware('privilege:prescription_read');
        $router->post('mix_queue/_search', 'MixQueueController@searchMixQueue')->middleware('privilege:prescription_read');
        $router->delete('mix_queue/{id}', 'MixQueueController@deleteMixQueue')->middleware('privilege:prescription_delete');
    }
);

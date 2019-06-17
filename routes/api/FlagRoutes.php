<?php

/*
|--------------------------------------------------------------------------
| Flag Routes
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
    'namespace' => 'App\Http\Controllers\Flag'
    ],
    function () use ($router) {
        $router->get('patient/{patient_id}/flag', 'FlagController@index')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/flag/{id}', 'FlagController@getFlag')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/flag', 'FlagController@createFlag')->middleware('privilege:patient_update');
        $router->post('patient/{patient_id}/flag/_search', 'FlagController@searchFlag')->middleware('privilege:patient_read');
        $router->put('patient/{patient_id}/flag/{id}', 'FlagController@updateFlag')->middleware('privilege:patient_update');
        $router->delete('patient/{patient_id}/flag/{id}', 'FlagController@deleteFlag')->middleware('privilege:patient_update');

        $router->get('patient/{patient_id}/flagdue', 'FlagDueController@index')->middleware('privilege:patient_read');
    }
);

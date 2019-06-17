<?php

/*
|--------------------------------------------------------------------------
| Vial Routes
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
    'namespace' => 'App\Http\Controllers\Vial'
    ],
    function () use ($router) {
        $router->get('patient/{patient_id}/vial', 'VialController@getAllPatientVials')->middleware('privilege:prescription_read,injection_read');
        $router->get('patient/{patient_id}/prescription/{prescription_id}/vial', 'VialController@index')->middleware('privilege:prescription_read,injection_read');
        $router->get('patient/{patient_id}/prescription/{prescription_id}/vial/{id}', 'VialController@getVial')->middleware('privilege:prescription_read,injection_read');
        $router->post('patient/{patient_id}/prescription/{prescription_id}/vial/_search', 'VialController@searchVial')->middleware('privilege:prescription_read,injection_read');
        $router->put('patient/{patient_id}/prescription/{prescription_id}/vial/{id}', 'VialController@updateVial')->middleware('privilege:activate_vials_update');
        $router->put('patient/{patient_id}/prescription/{prescription_id}/multivial', 'VialController@updateMultivial')->middleware('privilege:activate_vials_update');
    }
);

<?php

/*
|--------------------------------------------------------------------------
| Prescription Routes
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
    'namespace' => 'App\Http\Controllers\Prescription'
    ],
    function () use ($router) {
        $router->get('patient/{patient_id}/prescription', 'PrescriptionController@index')->middleware('privilege:prescription_read');
        $router->get('patient/{patient_id}/prescription/{id}', 'PrescriptionController@getPrescription')->middleware('privilege:prescription_read');
        $router->post('patient/{patient_id}/prescription/_search', 'PrescriptionController@searchPrescription')->middleware('privilege:prescription_update');
        $router->post('patient/{patient_id}/prescription', 'PrescriptionController@createPrescription')->middleware('privilege:prescription_create');
        $router->put('patient/{patient_id}/prescription/{id}', 'PrescriptionController@updatePrescription')->middleware('privilege:prescription_delete');
    }
);

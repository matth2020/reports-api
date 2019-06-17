<?php

/*
|--------------------------------------------------------------------------
| Identification Routes
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
    'namespace' => 'App\Http\Controllers\Identification'
    ],
    function () use ($router) {
        $router->post('identification/idcode', 'IdentificationController@identifyPatientID');
        $router->post('identification/fingerprint', 'IdentificationController@identifyPatientFinger');
    }
);

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\Identification'
    ],
    function () use ($router) {
        $router->get('patient/{patient_id}/identification/enroll', 'IdentificationController@getEnrolledFingers')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/identification/enroll', 'IdentificationController@enrollPatient')->middleware('privilege:patient_update');
        $router->post('patient/{patient_id}/identification/verify', 'IdentificationController@verifyPatient')->middleware('privilege:patient_read');
    }
);

<?php

/*
|--------------------------------------------------------------------------
| Patient Routes
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
    'namespace' => 'App\Http\Controllers\Patient'
    ],
    function () use ($router) {
        $router->get('patient/{id}', 'PatientController@getPatient')->middleware('privilege:patient_read');
        $router->post('patient/_search', 'PatientController@searchPatient')->middleware('privilege:patient_read');
        $router->post('patient', 'PatientController@createPatient')->middleware('privilege:patient_create');
        $router->post('patient/waitlist/{waitlist_id}/link', 'PatientController@createPatient')->middleware('privilege:patient_create');
        $router->post('patient/{id}/waitlist/{waitlist_id}/link', 'PatientController@updatePatientLink')->middleware('privilege:patient_update');
        $router->put('patient/{id}', 'PatientController@updatePatient')->middleware('privilege:patient_update');
        $router->delete('patient/{id}', 'PatientController@deletePatient')->middleware('privilege:patient_create');
        $router->post('/patient/{patient_id}/image', 'PatientController@patientImage')->middleware('privilege:patient_update');
    }
);

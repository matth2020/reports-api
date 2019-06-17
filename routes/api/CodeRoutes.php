<?php

/*
|--------------------------------------------------------------------------
| Clinic Routes
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
    'namespace' => 'App\Http\Controllers\Code'
    ],
    function () use ($router) {
        $router->get('code', 'CodeController@index');
        $router->get('code/{id}', 'CodeController@getCode');
        $router->post('code/_search', 'CodeController@searchCode');
        $router->post('code', 'CodeController@createCode')->middleware('privilege:config_create');
        $router->put('code/{id}', 'CodeController@updateCode')->middleware('privilege:config_update');
        $router->delete('code/{id}', 'CodeController@deleteCode')->middleware('privilege:config_delete');
        
        //Patient
        $router->get('patient/{patient_id}/code', 'PatientCodeController@index')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/code/{id}', 'PatientCodeController@getCode')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/code/_search', 'PatientCodeController@searchCode')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/code/{code_id}/assignment', 'PatientCodeController@createCode')->middleware('privilege:patient_update');
        $router->delete('patient/{patient_id}/code/{code_id}/assignment', 'PatientCodeController@deleteCode')->middleware('privilege:patient_update');
    }
);

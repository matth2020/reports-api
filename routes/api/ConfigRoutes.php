<?php

/*
|--------------------------------------------------------------------------
| Config Routes
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
    'namespace' => 'App\Http\Controllers\Config'
    ],
    function () use ($router) {
        $router->get('config', 'ConfigController@index');
        $router->get('config/{id}', 'ConfigController@getConfig');
        $router->post('config/_search', 'ConfigController@searchConfig');
    }
);

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\Config'
    ],
    function () use ($router) {
        // user config routes
        // Need special auth in controller since users should be able to preform
        // all actions on themselves but only specific actions on others.
        $router->get('user/config/{id}', 'UserConfigController@getUserConfig');
        $router->post('user/config/_search', 'UserConfigController@searchUserConfig');
        $router->post('user/config', 'UserConfigController@createUserConfig');
        $router->put('user/config/{id}', 'UserConfigController@updateUserConfig');
        $router->delete('user/config/{id}', 'UserConfigController@deleteUserConfig');

        // config routes
        $router->post('config', 'ConfigController@createConfig')->middleware('privilege:config_create');
        $router->put('config/{id}', 'ConfigController@updateConfig')->middleware('privilege:config_update');
        $router->delete('config/{id}', 'ConfigController@deleteConfig')->middleware('privilege:config_delete');

        // patient config
        $router->get('patient/{patient_id}/config', 'PatientConfigController@index')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/config/{id}', 'PatientConfigController@getPatientConfig')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/config/_search', 'PatientConfigController@searchPatientConfig')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/config', 'PatientConfigController@createPatientConfig')->middleware('privilege:patient_update');
        $router->put('patient/{patient_id}/config/{id}', 'PatientConfigController@updatePatientConfig')->middleware('privilege:patient_update');
        $router->delete('patient/{patient_id}/config/{id}', 'PatientConfigController@deletePatientConfig')->middleware('privilege:patient_update');
    }
);

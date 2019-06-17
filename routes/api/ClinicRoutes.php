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
    'namespace' => 'App\Http\Controllers\Clinic'
    ],
    function () use ($router) {
        $router->get('clinic', 'ClinicController@index');
        $router->get('clinic/{id}', 'ClinicController@getClinic');
        $router->post('clinic/_search', 'ClinicController@searchClinic');
        $router->post('clinic', 'ClinicController@createClinic')->middleware('privilege:config_create');
        $router->put('clinic/{id}', 'ClinicController@updateClinic')->middleware('privilege:config_update');
        $router->delete('clinic/{id}', 'ClinicController@deleteClinic')->middleware('privilege:config_delete');
    }
);

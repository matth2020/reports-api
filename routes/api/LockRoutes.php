<?php

/*
|--------------------------------------------------------------------------
| Lock Routes
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
    'namespace' => 'App\Http\Controllers\Lock'
    ],
    function () use ($router) {
        $router->delete('patient/{patient_id}/lock/{id}', 'LockController@deleteLock');
        $router->delete('patient/{patient_id}/lock', 'LockController@deleteAllUserLocks');
        $router->post('patient/{patient_id}/lock', 'LockController@createLock');
        $router->put('patient/{patient_id}/lock/{id}', 'LockController@updateLock');
        $router->get('patient/{patient_id}/lock', 'LockController@index');
        $router->get('patient/{patient_id}/lock/_stream', 'LockController@streamPatientLocks');
    }
);

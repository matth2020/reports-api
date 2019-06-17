<?php

/*
|--------------------------------------------------------------------------
| WaitList Routes
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
    'namespace' => 'App\Http\Controllers\Login'
    ],
    function () use ($router) {
        $router->post('login', 'LoginController@createLogin');
        $router->post('logout', 'LoginController@createLogout');
        $router->post('login/new_patient', 'LoginController@createNewPatientLogin');
    }
);

<?php

/*
|--------------------------------------------------------------------------
| Clinic Routes
|--------------------------------------------------------------------------
|
*/

$middleware = array();
$Config = app()->make('config');

$middlewareWithAuth = $middleware;
if ($Config->get('app.oauth_enabled') != false) {
    array_push($middlewareWithAuth, 'auth:api');
}

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\ClickLog'
    ],
    function () use ($router) {
        $router->post('click_log', 'ClickLogController@createClickLogs');
    }
);

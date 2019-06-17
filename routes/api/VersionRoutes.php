<?php

/*
|--------------------------------------------------------------------------
| Version Routes
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
    'namespace' => 'App\Http\Controllers\Version'
    ],
    // keeping this route auth required since knowing detailed app info can
    // help attackers
    function () use ($router) {
        $router->get('version', 'VersionController@index');
    }
);

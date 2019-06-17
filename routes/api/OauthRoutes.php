<?php

use App\Auth\Proxy;

/*
|-----------------------------
| Oauth routes
|-----------------------------
*/

$middleware = array();
$Config = app()->make('config');
if ($Config->get('app.api_log_enabled') != false) {
    array_push($middleware, 'api-log');
}

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middleware, //No auth required
    ],
    function () use ($router) {
        $router->post('app-login', function () {
            $Proxy = new Proxy;
            return $Proxy->attemptLogin();
        });

        $router->post('refresh-token', function () {
            $Proxy = new Proxy;
            return $Proxy->attemptRefresh();
        });
    }
);

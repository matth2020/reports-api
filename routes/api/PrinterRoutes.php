<?php

/*
|--------------------------------------------------------------------------
| Printer Routes
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
    'namespace' => 'App\Http\Controllers\Printer'
    ],
    function () use ($router) {
        $router->get('printer', 'PrinterController@index');
        $router->get('printer/{id}', 'PrinterController@getPrinter');
        $router->post('printer/_search', 'PrinterController@searchPrinter');
        $router->delete('printer/{id}', 'PrinterController@deletePrinter')->middleware('privilege:config_delete');
    }
);

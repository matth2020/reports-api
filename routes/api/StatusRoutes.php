<?php

/*
|--------------------------------------------------------------------------
| Status Routes
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
    'namespace' => 'App\Http\Controllers\Status'
    ],
    function () use ($router) {
        $router->get('status', 'StatusController@indexAll');
        $router->post('status/_search', 'StatusController@searchAllStatus');

        $router->get('{type}/status', 'StatusController@index');
        $router->post('{type}/status/_search', 'StatusController@searchStatus');
        $router->post('{type}/status', 'StatusController@createStatus')->middleware('privilege:config_create');
        $router->get('{type}/status/{id}', 'StatusController@getStatus');
        $router->put('{type}/status/{id}', 'StatusController@updateStatus')->middleware('privilege:config_update');
        $router->delete('{type}/status/{id}', 'StatusController@deleteStatus')->middleware('privilege:config_delete');
    }
);

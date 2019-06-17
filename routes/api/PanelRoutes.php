<?php

/*
|--------------------------------------------------------------------------
| Panel Routes
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
    'namespace' => 'App\Http\Controllers\Panel'
    ],
    function () use ($router) {
        $router->get('panel', 'PanelController@index');
        $router->get('panel/{id}', 'PanelController@getPanel');
        $router->post('panel/_search', 'PanelController@searchPanel');
        $router->post('panel', 'PanelController@createPanel')->middleware('privilege:config_create');
        $router->put('panel/{id}', 'PanelController@updatePanel')->middleware('privilege:config_update');
        $router->delete('panel/{id}', 'PanelController@deletePanel')->middleware('privilege:config_delete');
    }
);

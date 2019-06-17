<?php

/*
|--------------------------------------------------------------------------
| CompatibilityClass Routes
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
    'namespace' => 'App\Http\Controllers\CompatibilityClass'
    ],
    function () use ($router) {
        $router->get('compatibility_class', 'CompatibilityClassController@index');
        $router->get('compatibility_class/{id}', 'CompatibilityClassController@getCompatibilityClass');
        $router->post('compatibility_class/_search', 'CompatibilityClassController@searchCompatibilityClass');
        $router->post('compatibility_class', 'CompatibilityClassController@createCompatibilityClass')->middleware('privilege:inventory_create');
        $router->put('compatibility_class/{id}', 'CompatibilityClassController@updateCompatibilityClass')->middleware('privilege:inventory_create');
        $router->delete('compatibility_class/{id}', 'CompatibilityClassController@deleteCompatibilityClass')->middleware('privilege:inventory_delete');
    }
);

<?php

/*
|--------------------------------------------------------------------------
| Template Routes
|--------------------------------------------------------------------------
|
*/

$middleware = array();
$Template = app()->make('config');
if ($Template->get('app.api_log_enabled') != false) {
    array_push($middleware, 'api-log');
}

$middlewareWithAuth = $middleware;
if ($Template->get('app.oauth_enabled') != false) {
    array_push($middlewareWithAuth, 'auth:api');
}


$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middleware, //No auth required
    'namespace' => 'App\Http\Controllers\Template'
    ],
    function () use ($router) {
        $router->get('template', 'TemplateController@index');
        $router->get('template/{id}', 'TemplateController@getTemplate');
        $router->post('template/_search', 'TemplateController@searchTemplate');
    }
);

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\Template'
    ],
    function () use ($router) {
        // template routes
        $router->get('template/{id}', 'TemplateController@getTemplate')->middleware('privilege:config_read');
        $router->post('template/_search', 'TemplateController@searchTemplate')->middleware('privilege:config_read');
    }
);

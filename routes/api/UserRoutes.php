<?php

/*
|--------------------------------------------------------------------------
| User Routes
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
    'namespace' => 'App\Http\Controllers\User'
    ],
    function () use ($router) {
        $router->get('user', 'UserController@index')->middleware('privilege:all_users_read');
        $router->get('user/{id}', 'UserController@getUser');//checked in controller since users can read themselves
        $router->post('user/_search', 'UserController@searchUser')->middleware('privilege:all_users_read');
        $router->post('user', 'UserController@createUser')->middleware('privilege:all_users_create');
        $router->put('user/{id}', 'UserController@updateUser');// checked in controller since users can update themselves
        $router->delete('user/{id}', 'UserController@deleteUser')->middleware('privilege:all_users_delete');
    }
);

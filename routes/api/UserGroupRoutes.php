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
        //user groups
        $router->get('user_group', 'UserGroupController@index')->middleware('privilege:all_users_read');
        $router->get('user_group/{id}', 'UserGroupController@getUserGroup')->middleware('privilege:all_users_read');
        $router->post('user_group/_search', 'UserGroupController@searchUserGroup')->middleware('privilege:all_users_read');
        $router->post('user_group', 'UserGroupController@createUserGroup')->middleware('privilege:all_users_create');
        $router->put('user_group/{id}', 'UserGroupController@updateUserGroup')->middleware('privilege:all_users_update');
        $router->delete('user_group/{id}', 'UserGroupController@deleteUserGroup')->middleware('privilege:all_users_delete');
    }
);

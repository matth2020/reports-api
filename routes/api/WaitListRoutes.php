<?php

/*
|--------------------------------------------------------------------------
| WaitList Routes
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
    'middleware' => $middleware, //No auth required
    'namespace' => 'App\Http\Controllers\WaitList'
    ],
    function () use ($router) {
        $router->get('waitlist', 'WaitListController@index');
        $router->get('waitlist/_stream', 'WaitListController@streamWaitList');
        $router->get('clinic/{clinic_id}/waitlist/_stream', 'WaitListController@streamWaitList');
        $router->get('waitlist/{id}', 'WaitListController@getWaitList');
        $router->post('waitlist/_search', 'WaitListController@searchWaitList');
        $router->put('waitlist/{id}', 'WaitListController@updateWaitList');
        $router->post('waitlist', 'WaitListController@createWaitList');
    }
);

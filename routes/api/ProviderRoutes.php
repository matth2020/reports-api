<?php

/*
|--------------------------------------------------------------------------
| Provider Routes
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
        'middleware' => $middleware,
        'namespace' => 'App\Http\Controllers\Provider'
    ],
    function () use ($router) {
        // Provider
        $router->get('provider', 'ProviderController@index');
        $router->get('provider/{id}', 'ProviderController@getProvider');
        $router->post('provider/_search', 'ProviderController@searchProvider');
        $router->post('provider', 'ProviderController@createProvider')->middleware('privilege:provider_create');
        $router->put('provider/{id}', 'ProviderController@updateProvider')->middleware('privilege:provider_update');
        $router->delete('provider/{id}', 'ProviderController@deleteProvider')->middleware('privilege:provider_delete');

        // Profile
        $router->get('profile', 'ProfileController@index')->middleware('privilege:provider_create');
        $router->get('profile/{id}', 'ProfileController@getProfile');
        $router->post('profile/_search', 'ProfileController@searchProfile');
        $router->get('provider/{provider_id}/profile', 'ProfileController@index');
        $router->get('provider/{provider_id}/profile/{id}', 'ProfileController@getProfile');
        $router->post('provider/{provider_id}/profile/_search', 'ProfileController@searchProfile');
        $router->post('provider/{provider_id}/profile', 'ProfileController@createProfile')->middleware('privilege:provider_create');
        $router->put('provider/{provider_id}/profile/{id}', 'ProfileController@updateProfile')->middleware('privilege:provider_update');
        $router->delete('provider/{provider_id}/profile/{id}', 'ProfileController@deleteProfile')->middleware('privilege:provider_delete');
    }
);

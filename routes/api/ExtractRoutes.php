<?php

/*
|--------------------------------------------------------------------------
| Extract Routes
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
    'namespace' => 'App\Http\Controllers\Extract'
    ],
    function () use ($router) {
        $router->get('profile/{profile_id}/extract', 'ExtractController@getAllProfileExtracts');
        $router->get('profile/{profile_id}/extract/{id}', 'ExtractController@getProfileExtract');
        $router->get('extract', 'ExtractController@index');
        $router->get('extract/{id}', 'ExtractController@getExtract');
        $router->post('extract/_search', 'ExtractController@searchExtract');
        $router->post('extract', 'ExtractController@createExtract')->middleware('privilege:extract_create');
        $router->put('extract/{id}', 'ExtractController@updateExtract')->middleware('privilege:extract_update');
        $router->delete('extract/{id}', 'ExtractController@deleteExtract')->middleware('privilege:extract_delete');
    }
);

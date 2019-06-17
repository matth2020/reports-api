<?php

/*
|--------------------------------------------------------------------------
| Technallergy Routes
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
    'namespace' => 'App\Http\Controllers\Technallergy'
    ],
    function () use ($router) {
        $router->get('/technallergy/config', 'TechnallergyController@getConfig');
        $router->post('/patient/{patient_id}/technallergy/link', 'TechnallergyController@link');
        $router->post('/patient/{patient_id}/technallergy/sync', 'TechnallergyController@sync');
    }
);

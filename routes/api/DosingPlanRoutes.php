<?php

/*
|--------------------------------------------------------------------------
| DosingPlan Routes
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
    'namespace' => 'App\Http\Controllers\DosingPlan'
    ],
    function () use ($router) {
        $router->get('dosing_plan', 'DosingPlanController@index');
        $router->get('dosing_plan/{id}', 'DosingPlanController@getDosingPlan');
        $router->post('dosing_plan/_search', 'DosingPlanController@searchDosingPlan');
        $router->post('dosing_plan', 'DosingPlanController@createDosingPlan')->middleware('privilege:dosing_plan_create');
        $router->put('dosing_plan/{id}', 'DosingPlanController@updateDosingPlan')->middleware('privilege:dosing_plan_update');
        $router->delete('dosing_plan/{id}', 'DosingPlanController@deleteDosingPlan')->middleware('privilege:dosing_plan_delete');
    }
);

<?php

/*
|--------------------------------------------------------------------------
| TreatmentPlan Routes
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
    'namespace' => 'App\Http\Controllers\TreatmentPlan'
    ],
    function () use ($router) {
        $router->get('treatment_plan', 'TreatmentPlanController@index');
        $router->get('treatment_plan/{id}', 'TreatmentPlanController@getTreatmentPlan');
        $router->post('treatment_plan/_search', 'TreatmentPlanController@searchTreatmentPlan');
        $router->post('treatment_plan', 'TreatmentPlanController@createTreatmentPlan')->middleware('privilege:treatment_plan_create');
        $router->put('treatment_plan/{id}', 'TreatmentPlanController@updateTreatmentPlan')->middleware('privilege:treatment_plan_update');
        $router->delete('treatment_plan/{id}', 'TreatmentPlanController@deleteTreatmentPlan')->middleware('privilege:treatment_plan_delete');
    }
);

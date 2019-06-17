<?php

/*
|--------------------------------------------------------------------------
| Injection Routes
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
    'namespace' => 'App\Http\Controllers\Injection'
    ],
    function () use ($router) {
        // validation
        $router->post('patient/{patient_id}/injection/validate', 'InjectionValidationController@validateInjection')->middleware('privilege:injection_read');
        $router->post('patient/{patient_id}/multiinjection/validate', 'InjectionValidationController@validateMultiInjection')->middleware('privilege:injection_read');
        
        // Injection
        $router->get('patient/{patient_id}/injection', 'InjectionController@index')->middleware('privilege:injection_read');
        $router->get('patient/{patient_id}/injection/{id}', 'InjectionController@getInjection')->middleware('privilege:injection_read');
        $router->put('patient/{patient_id}/injection/{id}', 'InjectionController@updateInjection')->middleware('privilege:injection_update');
        $router->delete('patient/{patient_id}/injection/{id}', 'InjectionController@deleteInjection')->middleware('privilege:injection_delete');
        $router->post('patient/{patient_id}/injection', 'InjectionController@createInjection')->middleware('privilege:injection_create');
        $router->post('patient/{patient_id}/multiinjection', 'InjectionController@createMultiInjection')->middleware('privilege:injection_create');
        $router->post('patient/{patient_id}/injection/_search', 'InjectionController@searchInjection')->middleware('privilege:injection_read');

        // Injection Adjust
        $router->get('patient/{patient_id}/injection_adjust', 'InjectionAdjustController@index')->middleware('privilege:injection_read');
        $router->get('patient/{patient_id}/injection_adjust/{id}', 'InjectionAdjustController@getInjectionAdjust')->middleware('privilege:injection_read');
        $router->get('patient/{patient_id}/prescription/{prescription_id}/injection_adjust', 'InjectionAdjustController@getInjectionAdjust')->middleware('privilege:injection_read');
        $router->get('patient/{patient_id}/prescription/{prescription_id}/injection_adjust/{id}', 'InjectionAdjustController@getInjectionAdjust')->middleware('privilege:injection_read');
        $router->put('patient/{patient_id}/injection_adjust/{id}', 'InjectionAdjustController@updateInjectionAdjust')->middleware('privilege:injection_adjust_update');
        $router->put('patient/{patient_id}/prescription/{prescription_id}/injection_adjust/{id}', 'InjectionAdjustController@updateInjectionAdjust')->middleware('privilege:injection_adjust_update');
        $router->delete('patient/{patient_id}/injection_adjust/{id}', 'InjectionAdjustController@deleteInjectionAdjust')->middleware('privilege:injection_adjust_delete');
        $router->delete('patient/{patient_id}/prescription/{prescription_id}/injection_adjust/{id}', 'InjectionAdjustController@deleteInjectionAdjust')->middleware('privilege:injection_adjust_delete');
        $router->post('patient/{patient_id}/prescription/{prescription_id}/injection_adjust', 'InjectionAdjustController@createInjectionAdjust')->middleware('privilege:injection_adjust_create');
        $router->post('patient/{patient_id}/injection_adjust/_search', 'InjectionAdjustController@searchInjectionAdjust')->middleware('privilege:injection_read');
        $router->post('patient/{patient_id}/prescription/{prescription_id}/injection_adjust/_search', 'InjectionAdjustController@searchInjectionAdjust')->middleware('privilege:injection_read');

        // Injection Due
        $router->get('patient/{patient_id}/injection_due', 'InjectionDueController@index')->middleware('privilege:injection_read');
        $router->get('patient/{patient_id}/prescription/{prescription_id}/injection_due', 'InjectionDueController@getInjectionDue')->middleware('privilege:injection_read');

        // Injection Plan
        $router->get('patient/{patient_id}/injection_plan', 'InjectionPlanController@index')->middleware('privilege:injection_read');
        $router->get('patient/{patient_id}/prescription/{prescription_id}/injection_plan', 'InjectionPlanController@getRxInjectionPlan')->middleware('privilege:injection_read');
    }
);

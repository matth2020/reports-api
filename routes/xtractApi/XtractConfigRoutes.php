<?php

/*
|--------------------------------------------------------------------------
| Xtract internal config routes - not public facing
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
    'middleware' => $middleware, //no auth required
    'namespace' => 'App\Http\Controllers\Xtract'
    ],
    function () use ($router) {
        $router->get('xtract/globalAppConfig', 'xtractConfigController@globalAppConfig');
    }
);

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\Xtract'
    ],
    function () use ($router) {
        $router->get('xtract/globalAppConfigAuth', 'xtractConfigController@globalAppConfigAuth');
        $router->get('xtract/treatmentPlanAppConfig', 'xtractConfigController@treatmentPlanAppConfig');
        $router->get('patient/{patient_id}/xtract/nonXisInjectionsWindow', 'xtractConfigController@nonXisInjectionsWindow');
        $router->get('xtract/nonXpsPrescriptionsWindow', 'xtractConfigController@nonXpsPrescriptionsWindow');
        $router->get('patient/{patient_id}/xtract/loadPatientDisplay', 'xtractConfigController@loadPatientDisplay')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/xtract/orderEntryWindow', 'xtractConfigController@orderEntryWindow')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/xtract/loadInjectionData', 'xtractConfigController@loadInjectionData')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/xtract/patientDetailsWindow', 'xtractConfigController@patientDetailsWindow')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/xtract/injAdjustWindow', 'xtractConfigController@injAdjustWindow')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/xtract/trackingWindow', 'xtractConfigController@trackingWindow')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/xtract/immunotherapySummaryWindow', 'xtractConfigController@immunotherapySummaryWindow')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/xtract/standardInjectionWindow', 'xtractConfigController@standardInjectionWindow')->middleware('privilege:patient_read');
    }
);

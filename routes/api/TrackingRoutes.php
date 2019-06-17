<?php

/*
|--------------------------------------------------------------------------
| TrackingConfig Routes
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
    'namespace' => 'App\Http\Controllers\Tracking'
    ],
    function () use ($router) {
        // tracking config
        $router->get('patient/{patient_id}/tracking_config', 'TrackingConfigController@index')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/tracking_config/{id}', 'TrackingConfigController@getTrackingConfig')->middleware('privilege:patient_read');
        $router->put('patient/{patient_id}/tracking_config/{id}', 'TrackingConfigController@updateTrackingConfig')->middleware('privilege:patient_update');
        $router->delete('patient/{patient_id}/tracking_config/{id}', 'TrackingConfigController@deleteTrackingConfig')->middleware('privilege:patient_update');

        // tracking values
        $router->delete('patient/{patient_id}/tracking_value/{id}', 'TrackingValueController@deleteTrackingValue')->middleware('privilege:patient_update');
        $router->get('patient/{patient_id}/tracking_value', 'TrackingValueController@index')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/tracking_value/{id}', 'TrackingValueController@getTrackingValue')->middleware('privilege:patient_read');
        $router->put('patient/{patient_id}/tracking_value/{id}', 'TrackingValueController@updateTrackingValue')->middleware('privilege:patient_update');
        $router->post('patient/{patient_id}/tracking_value', 'TrackingValueController@createTrackingValue')->middleware('privilege:patient_update');
        $router->post('patient/{patient_id}/tracking_value/_search', 'TrackingValueController@searchTrackingValue')->middleware('privilege:patient_read');
    }
);

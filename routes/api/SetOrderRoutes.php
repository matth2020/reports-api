<?php

/*
|--------------------------------------------------------------------------
| SetOrder Routes
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
    'namespace' => 'App\Http\Controllers\Order'
    ],
    function () use ($router) {
        $router->get('patient/{patient_id}/set_order', 'SetOrderController@getAllPatientSetOrders')->middleware('privilege:prescription_read');
        $router->get('patient/{patient_id}/prescription/{prescription_id}/set_order', 'SetOrderController@index')->middleware('privilege:prescription_read');
        $router->get('patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}', 'SetOrderController@getSetOrder')->middleware('privilege:prescription_read');
        $router->put('patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}', 'SetOrderController@updateSetOrder')->middleware('privilege:prescription_update');
        $router->delete('patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}', 'SetOrderController@deleteSetOrder')->middleware('privilege:prescription_delete');
        $router->post('patient/{patient_id}/prescription/{prescription_id}/set_order/{set_order_id}/mix', 'SetOrderController@mixSetOrder')->middleware('privilege:prescription_mix_update');
        $router->post('patient/{patient_id}/set_order/{set_order_id}/mix', 'SetOrderController@mixSetOrder')->middleware('privilege:prescription_update');
    }
);

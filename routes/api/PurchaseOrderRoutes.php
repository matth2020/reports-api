<?php

/*
|--------------------------------------------------------------------------
| PurchaseOrder Routes
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
        $router->get('purchase_order', 'PurchaseOrderController@getAllPurchaseOrders');
        $router->get('patient/{patient_id}/purchase_order', 'PurchaseOrderController@getAllPatientPurchaseOrders');
        $router->post('patient/{patient_id}/purchase_order', 'PurchaseOrderController@createPurchaseOrder');
        $router->get('patient/{patient_id}/purchase_order/{purchase_order_id}', 'PurchaseOrderController@getPurchaseOrder');
        $router->put('patient/{patient_id}/purchase_order/{purchase_order_id}', 'PurchaseOrderController@updatePurchaseOrder');
        $router->delete('patient/{patient_id}/purchase_order/{purchase_order_id}', 'PurchaseOrderController@deletePurchaseOrder');
        $router->post('purchase_order/_search', 'PurchaseOrderController@searchPurchaseOrder');
    }
);

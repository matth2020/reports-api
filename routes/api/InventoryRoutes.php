<?php

/*
|--------------------------------------------------------------------------
| Inventory Routes
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
    'namespace' => 'App\Http\Controllers\Inventory'
    ],
    function () use ($router) {
        $router->get('inventory', 'InventoryController@index');
        $router->get('inventory/{id}', 'InventoryController@getInventory');
        $router->post('inventory/_search', 'InventoryController@searchInventory');
        $router->post('inventory', 'InventoryController@createInventory')->middleware('privilege:inventory_create');
        $router->put('inventory/{id}', 'InventoryController@updateInventory')->middleware('privilege:inventory_update');
        $router->delete('inventory/{id}', 'InventoryController@deleteInventory')->middleware('privilege:inventory_delete');
    }
);

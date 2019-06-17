<?php

/*
|--------------------------------------------------------------------------
| Address Routes
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
    'namespace' => 'App\Http\Controllers\Address'
    ],
    function () use ($router) {
        // anyone can read... why do we have an all address endpoint it would
        // be a huge list
        $router->get('address', 'AddressController@index');
        // who should have access to address/id... anyone?
        $router->get('address/{id}', 'AddressController@getAddress');
        // who should have address search? anyone
        $router->post('address/_search', 'AddressController@searchAddress');
        //address create/update/delete access? Anyone? Its shared across account
        //patient, provider, etc?
        $router->post('address', 'AddressController@createAddress');
        $router->put('address/{id}', 'AddressController@updateAddress');
        $router->delete('address/{id}', 'AddressController@deleteAddress');

        $router->get('patient/{patient_id}/address', 'AddressController@index')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/address/_search', 'AddressController@searchAddress')->middleware('privilege:patient_read');
    }
);

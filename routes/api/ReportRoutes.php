<?php

/*
|--------------------------------------------------------------------------
| Report Routes
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
    'middleware' => $middleware, //No auth
    'namespace' => 'App\Http\Controllers\Report'
    ],
    function () use ($router) {
        // this route is specificially for printNode to request pdfs. It does
        // not require auth but requires a valid and uniue key and id from
        // printnode and the link expires after a predetermined time.
        $router->get('print_queue/{print_queue_id}/print_node', 'reportsController@printNodeDownload');
    }
);

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\Report'
    ],
    function () use ($router) {
        $router->get('patient/{patient_id}/report/{report_id}/download', 'reportsController@downloadReport')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/report/{report_id}', 'reportsController@getReport')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/print_queue/{print_queue_id}', 'reportsController@printStatus')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/report', 'reportsController@createReport')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/report/{reports_id}', 'reportsController@createReport')->middleware('privilege:patient_read');
    }
);

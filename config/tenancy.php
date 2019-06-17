<?php

return [
    'multiTenant' => env('MULTI_TENANT', false),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'primary_db' => env('DB_DATABASE')
];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false,
    'allowedOrigins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'https://localhost,http://localhost,http://localhost:5000')),
    'allowedHeaders' => ['Content-Type','Authorization','Accept'],
    'allowedMethods' => ['GET','PUT','POST','DELETE','OPTIONS'],
    'exposedHeaders' => [],
    'maxAge' => 0,
];

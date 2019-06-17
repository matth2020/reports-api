<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */
    'technallergy' => [
        'technallergyUrl' => env('TECHNALLERGY_URL'),
        'technallergyProviderId' => env('TECHNALLERGY_PROVIDER_ID'),
        'technallergyClientId' => env('TECHNALLERGY_CLIENT_ID'),
        'technallergyClientSecret' => env('TECHNALLERGY_CLIENT_SECRET'),
        'technallergyAutoSync' => strtolower(env('TECHNALLERGY_AUTO_SYNC')) === 'true'
    ],

    'smartyStreents' => [
        'id' => 'a0f39b71-95d4-24ca-9730-03994cddbaf0',
        'token' => 'LSEPqgXxriRzyl3n7vap',
        'enabled' => env('SMARTY_STREETS_ENABLED', false)
    ],

    'printNode' => [
        'key' => env('PRINT_NODE_KEY')
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];

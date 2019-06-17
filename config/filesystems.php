<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],
        'login' => [
            'driver' => 'local',
            'root' => storage_path('app/login'),
            'url' => env('APP_SERVER_URL').env('APP_PATH').'/storage/login',
            'visibility' => 'public',
        ],
        'lobbyDashboard' => [
            'driver' => 'local',
            'root' => storage_path('app/lobbyDashboard'),
            'url' => env('APP_SERVER_URL').env('APP_PATH').'/storage/lobbyDashboard',
            'visibility' => 'public',
        ],
        'reports' => [
            'driver' => 'local',
            'root' => storage_path('app/reports'),
            'url' => env('APP_SERVER_URL').env('APP_PATH').'/storage/reports'
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_SERVER_URL').env('APP_PATH').'/storage/public',
            'visibility' => 'public',
        ],
        'tmp' => [
            'driver' => 'local',
            'root' => storage_path('app/tmp'),
            'url' => env('APP_SERVER_URL').env('APP_PATH').'/storage/tmp',
            'visibility' => 'private',
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

    ],

];
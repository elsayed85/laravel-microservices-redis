<?php

return [
    'service' => \Elsayed85\LmsRedis\LmsRedis::class,

    'redis' => [
        'client' => env('LMS_REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('LMS_REDIS_CLUSTER', 'redis'),
            'prefix' => env('LMS_REDIS_PREFIX', 'database_'),
        ],

        'default' => [
            'url' => env('LMS_REDIS_URL'),
            'host' => env('LMS_REDIS_HOST', '127.0.0.1'),
            'username' => env('LMS_REDIS_USERNAME'),
            'password' => env('LMS_REDIS_PASSWORD'),
            'port' => env('LMS_REDIS_PORT', '6379'),
            'database' => env('LMS_REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('LMS_REDIS_URL'),
            'host' => env('LMS_REDIS_HOST', '127.0.0.1'),
            'username' => env('LMS_REDIS_USERNAME'),
            'password' => env('LMS_REDIS_PASSWORD'),
            'port' => env('LMS_REDIS_PORT', '6379'),
            'database' => env('LMS_REDIS_CACHE_DB', '1'),
        ],
    ],
];

<?php

use \Illuminate\Support\Env;

return [
    'db' => [
        'driver' => Env::get('DB_DRIVER', 'pgsql'),
        'host' => Env::get('DB_HOST', 'db'),
        'database' => Env::get('DB_DATABASE', 'demo'),
        'username' => Env::get('DB_USERNAME', 'demo'),
        'password' => Env::get('DB_PASSWORD', 'password'),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'schema' => 'public',
        'sslmode' => 'prefer',
        'port' => Env::get('DB_PORT', 5432),
    ]
];

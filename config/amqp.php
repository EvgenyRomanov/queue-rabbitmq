<?php

use \Illuminate\Support\Env;

return [
    'amqp' => [
        'host' => Env::get('API_AMQP_HOST'),
        'port' => Env::get('API_AMQP_PORT'),
        'username' => Env::get('API_AMQP_USERNAME'),
        'password' => Env::get('API_AMQP_PASSWORD'),
        'vhost' => Env::get('API_AMQP_VHOST'),
    ]
];

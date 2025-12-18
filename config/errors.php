<?php

use \Illuminate\Support\Env;

// Настройки Error Handling Middleware
return [
    'errors' => [
        'display_error_details' => Env::get('APP_DEBUG', true),
        'log_errors' => true,
        'log_error_details' => true,
    ]
];

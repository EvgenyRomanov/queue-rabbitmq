<?php

return [
    'event_listener_mapping' => [
        \Illuminate\Queue\Events\JobFailed::class => [
            \App\Infrastructure\Listener\JobFailedListener::class
        ],

        \Illuminate\Queue\Events\JobProcessed::class => [
            \App\Infrastructure\Listener\JobProcessedListener::class
        ],

        \Illuminate\Queue\Events\JobProcessing::class => [
            \App\Infrastructure\Listener\JobProcessingListener::class
        ],
    ]
];

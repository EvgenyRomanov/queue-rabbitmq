<?php

return [
    'event_listener_mapping' => [
        \App\Service\Event\CreateTtEvent::class => [
            \App\Infrastructure\Listener\CreateTtListener::class
        ],
    ]
];

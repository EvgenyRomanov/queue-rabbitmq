<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DatabaseManager;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Queue\Capsule\Manager as QueueManager;
use Illuminate\Queue\Connectors\DatabaseConnector;

return function (Container $container): QueueManager {
    /** @var DatabaseManager $dbManager */
    $dbManager = $container[DatabaseManager::class];
    $connection = $dbManager->schema()->getConnection();

    $queue = new QueueManager($container);
    $queue->addConnection([
        'driver' => 'database',
        'table' => 'illuminate_jobs',
        'connection' => 'default',
        'queue' => 'default',
    ]);

    $queueManager = $queue->getQueueManager();
    $resolver = new ConnectionResolver(['default' => $connection]);
    $queueManager->addConnector('database', function () use ($resolver) {
        return new DatabaseConnector($resolver);
    });

    $queue->setAsGlobal();

    return $queue;
};

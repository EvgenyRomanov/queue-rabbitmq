<?php

use Illuminate\Database\Capsule\Manager as DatabaseManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Laminas\ConfigAggregator\ConfigAggregator;

return static function (Container $container): DatabaseManager {
    /** @var ConfigAggregator $configAggregator */
    $configAggregator = $container[ConfigAggregator::class];
    /** @var array{
     *     migrations: array<string, mixed>,
     *     db: array{database: string, username: string, password: string, host: string, driver: string, port: int}
     *     } $config
     */
    $config = $configAggregator->getMergedConfig();

    $dbManager = new DatabaseManager();
    $primaryConfig = [
        'driver' => $config['db']['driver'],
        'host' => $config['db']['host'],
        'database' => $config['db']['database'],
        'username' => $config['db']['username'],
        'password' => $config['db']['password'],
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'schema' => 'public',
        'sslmode' => 'prefer',
        'port' => $config['db']['port'],
    ];

    $dbManager->addConnection($primaryConfig);
    /** @var EventDispatcher $eventDispatcher */
    $eventDispatcher = $container[EventDispatcher::class];
    $dbManager->setEventDispatcher($eventDispatcher);
    $dbManager->setAsGlobal();
    $dbManager->bootEloquent();

    return $dbManager;
};

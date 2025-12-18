<?php

use Illuminate\Container\Container;
use Laminas\ConfigAggregator\ConfigAggregator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Illuminate\Database\Capsule\Manager as DatabaseManager;
use Illuminate\Queue\Capsule\Manager as QueueManager;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Queue\Worker as QueueWorker;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;

return static function (Container $container) {

    (require __DIR__ . '/di_bindings/app.php')($container);

    (require __DIR__ . '/di_bindings/migrations.php')($container);

    $container->singleton(LoggerInterface::class, require __DIR__ . '/logger.php');

    $container->singleton(
        EventDispatcher::class,
        function (Container $container) {
            /** @var ConfigAggregator $configAggregator */
            $configAggregator = $container[ConfigAggregator::class];
            /** @var array{event_listener_mapping: array<string, array<class-string>>} $config */
            $config = $configAggregator->getMergedConfig();

            $dispatcher = new \Illuminate\Events\Dispatcher($container);
            $eventListenerMappings = $config['event_listener_mapping'];

            foreach ($eventListenerMappings as $event => $listeners) {
                foreach ($listeners as $listener) {
                    $dispatcher->listen($event, $listener);
                }
            }

            return $dispatcher;
        }
    );

    $container->singleton(DatabaseManager::class, require __DIR__ . '/eloquent_loaders.php');
    // Чтобы сразу создать объект и загрузить в память
    $container->make(DatabaseManager::class);

    $container->singleton(QueueManager::class, require __DIR__ . '/queues/queue.php');
    // Чтобы сразу создать объект и загрузить в память
    $container->make(QueueManager::class);

    $container->singleton(QueueWorker::class, require __DIR__ . '/queues/queue_worker.php');

    $container->singleton(BusDispatcher::class, function (Container $container) {
        return new \Illuminate\Bus\Dispatcher($container, function (?string $connection = null) use ($container) {
            /** @var QueueManager $queueManager */
            $queueManager = $container[QueueManager::class];
            return $queueManager->connection($connection);
        });
    });

    $container->singleton(\Illuminate\Queue\CallQueuedHandler::class, function (Container $container) {
        /** @var BusDispatcher $dispatcher */
        $dispatcher = $container[BusDispatcher::class];
        return new \Illuminate\Queue\CallQueuedHandler($dispatcher, $container);
    });

    $container->singleton(ValidatorInterface::class, function () {
        return Validation::createValidator();
    });

    $container->singleton(PdoAdapter::class, function (Container $container) {
        /** @var DatabaseManager $dbManager */
        $dbManager = $container[DatabaseManager::class];
        $pdo = $dbManager->getConnection()->getPdo();

        return new PdoAdapter($pdo);
    });

    (require __DIR__ . '/di_bindings/repositories.php')($container);
    (require __DIR__ . '/di_bindings/amqp.php')($container);
};

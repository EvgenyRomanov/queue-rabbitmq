<?php

use Illuminate\Container\Container;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Laminas\ConfigAggregator\ConfigAggregator;

return static function (Container $container): void {
    $container->singleton(AMQPStreamConnection::class, function ($container) {
        /** @var ConfigAggregator $configAggregator */
        $configAggregator = $container[ConfigAggregator::class];
        /** @var array{
         *     migrations: array<string, mixed>,
         *     db: array{database: string, username: string, password: string, host: string, driver: string, port: int}
         *     } $config
         */
        $config = $configAggregator->getMergedConfig();

        return new AMQPStreamConnection(
            $config['amqp']['host'],
            $config['amqp']['port'],
            $config['amqp']['username'],
            $config['amqp']['password'],
            $config['amqp']['vhost']
        );
    });
};

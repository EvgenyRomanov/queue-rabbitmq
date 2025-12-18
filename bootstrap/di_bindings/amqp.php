<?php

use Illuminate\Container\Container;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Laminas\ConfigAggregator\ConfigAggregator;

return static function (Container $container): void {
    $container->singleton(AMQPStreamConnection::class, function (Container $container) {
        /** @var ConfigAggregator $configAggregator */
        $configAggregator = $container[ConfigAggregator::class];
        /** @var array{
         *     amqp: array{host: string, port: int, username: string, password: string, vhost: string}
         *     } $config
         */
        $config = $configAggregator->getMergedConfig();
        $configAmqp = $config['amqp'];

        return new AMQPStreamConnection(
            $configAmqp['host'],
            $configAmqp['port'],
            $configAmqp['username'],
            $configAmqp['password'],
            $configAmqp['vhost']
        );
    });
};

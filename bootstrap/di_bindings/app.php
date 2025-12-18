<?php

use EvgenyRomanov\SlimCallableResolver;
use Illuminate\Container\Container;
use Laminas\ConfigAggregator\PhpFileProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\{App, Factory\AppFactory, Interfaces\CallableResolverInterface, Psr7\Factory\ResponseFactory};
use Psr\Http\Message\ServerRequestFactoryInterface;
use Laminas\ConfigAggregator\ConfigAggregator;

return static function (Container $container): void {

    $container->singleton(ConfigAggregator::class, function () {
        return new ConfigAggregator([
            // Загружаем PHP-файлы из config/ и поддиректорий (до 3 уровней)
            new PhpFileProvider(__DIR__ . '/../../config/{{,*,*/*,*/*/*}}.php'),
        ]);
    });

    $container->singleton(App::class, function (Container $container) {
        AppFactory::setContainer($container);
        return AppFactory::create();
    });

    $container->singleton(CallableResolverInterface::class, function (Container $container) {
        return new SlimCallableResolver($container);
    });

    $container->singleton(ServerRequestFactoryInterface::class, function () {
        return new ResponseFactory();
    });

    $container->singleton(ResponseFactoryInterface::class, function () {
        return new ResponseFactory();
    });

    $container->singleton(ResponseFactoryInterface::class, function (Container $container) {
        /** @var App $app */
        $app = $container[App::class];
        return $app->getResponseFactory();
    });
};

<?php

use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Views\TwigMiddleware;
use Slim\Views\Twig;
use Illuminate\Container\Container;
use Laminas\ConfigAggregator\ConfigAggregator;

return static function (App $app, Container $container) {
    /** @var ConfigAggregator $configAggregator */
    $configAggregator = $container[ConfigAggregator::class];
    /** @var array{
     *     errors: array{display_error_details: bool, log_errors: bool, log_error_details: bool}
     *     } $config
     */
    $config = $configAggregator->getMergedConfig();
    /** @var LoggerInterface $logger */
    $logger = $container[LoggerInterface::class];

    $twig = Twig::create(__DIR__ . '/../resources/views', ['cache' => false]);

    $app->add(TwigMiddleware::create($app, $twig));
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(
        $config['errors']['display_error_details'],
        $config['errors']['log_errors'],
        $config['errors']['log_error_details'],
        $logger
    );
};

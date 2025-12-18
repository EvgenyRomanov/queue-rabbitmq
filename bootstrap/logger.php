<?php

use Illuminate\Container\Container;

use Monolog\{
  Formatter\JsonFormatter,
  Handler\StreamHandler,
  Logger,
};

/** @psalm-suppress UnusedClosureParam */
return static function (Container $container) {
    // Создание экземпляра Logger
    $logger = new Logger('simple_logger');

    $formatter = new JsonFormatter();

    // Добавление обработчика для записи в файл
    $streamHandler = new StreamHandler("php://stdout");
    $streamHandler->setFormatter($formatter);

    $logger->pushHandler($streamHandler);

    return $logger;
};

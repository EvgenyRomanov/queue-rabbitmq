<?php

use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

// Загрузка переменных из .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$container = Container::getInstance();

// Настройка DI-контейнера
(require __DIR__ . '/di_container.php')($container);

// Создание web приложения
$app = AppFactory::createFromContainer($container);

/** @var Slim\App<Psr\Container\ContainerInterface|null> $app */
// Регистрация маршрутов
(require __DIR__ . '/routes.php')($app);

// Регистрация middleware
(require __DIR__ . '/middleware.php')($app, $container);

return $app;

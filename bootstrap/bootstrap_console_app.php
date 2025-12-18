<?php

use Dotenv\Dotenv;
use Symfony\Component\Console\Application;
use Illuminate\Container\Container;

require_once __DIR__ . '/../vendor/autoload.php';

// Загрузка переменных из .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$container = Container::getInstance();

// Настройка DI-контейнера
(require __DIR__ . '/di_container.php')($container);

// Создание консольного приложения
$app = new Application();

// Загрузка команд
(require __DIR__ . '/load_commands.php')($container, $app);

return $app;

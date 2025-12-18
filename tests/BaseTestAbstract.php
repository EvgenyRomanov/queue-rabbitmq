<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory as Faker;
use Illuminate\Database\Capsule\Manager as DatabaseManager;
use Override;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Uri;
use Throwable;

abstract class BaseTestAbstract extends TestCase
{
    protected ?DatabaseManager $capsule = null;
    /** @psalm-suppress PossiblyUnusedProperty  */
    protected ?\Faker\Generator $faker = null;

    protected ?App $app = null;

    /**
     * @throws Throwable
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MissingFile
     * @psalm-suppress MixedArrayAccess
     */
    #[Override]
    protected function setUp(): void
    {
        $this->faker = Faker::create();
        $this->app = require __DIR__ . '/../bootstrap/bootstrap_web_app.php';
        /** @var ContainerInterface $container */
        $container = $this->app->getContainer();
        /**
         * @var DatabaseManager $dbManager
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $dbManager = $container[DatabaseManager::class];
        $this->capsule = $dbManager;

        $this->capsule->getConnection()->beginTransaction();
    }

    /**
     * @throws Throwable
     */
    #[Override]
    protected function tearDown(): void
    {
        /** @psalm-suppress PossiblyNullReference */
        $this->capsule->getConnection()->rollback();
    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): \Psr\Http\Message\ServerRequestInterface {
        $uri = new Uri('', '', 80, $path);
        /** @var resource $handle */
        $handle = fopen('php://temp', 'w+');
        $stream = (new \Slim\Psr7\Factory\StreamFactory())->createStreamFromResource($handle);

        $h = new \Slim\Psr7\Headers();
        /** @psalm-suppress MixedAssignment */
        foreach ($headers as $name => $value) {
            /** @psalm-suppress MixedArgument */
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $h->addHeader($name, $value);
        }

        return new \Slim\Psr7\Request($method, $uri, $h, $cookies, $serverParams, $stream);
    }

    protected function parseResponse(ResponseInterface $response): mixed
    {
        return json_decode((string) $response->getBody(), true);
    }
}

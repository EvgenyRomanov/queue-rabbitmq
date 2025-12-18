<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Queue\Capsule\Manager;
use Illuminate\Queue\Worker;
use Psr\Log\LoggerInterface;

/** @see QueueServiceProvider */
return function (Container $container): Worker {
    /** @var Manager $manager */
    $manager = $container[Manager::class];
    /** @var Dispatcher $dispatcher */
    $dispatcher = $container[Dispatcher::class];
    /** @var LoggerInterface $logger */
    $logger = $container[LoggerInterface::class];
    $isDownForMaintenanceHandler = function (): void {};

    $handler = new class($logger) implements ExceptionHandler {
        private LoggerInterface $logger;

        public function __construct(LoggerInterface $logger)
        {
            $this->logger = $logger;
        }

        #[\Override]
        public function report(Throwable $e)
        {
            echo $e->getMessage() . PHP_EOL;
            $this->logger->error($e->getMessage(), ['Exception' => $e]);
        }

        #[\Override]
        public function shouldReport(Throwable $e)
        {
            return false;
        }

        /** @psalm-suppress InvalidReturnType */
        #[\Override]
        public function render($request, Throwable $e)
        {
        }

        #[\Override]
        public function renderForConsole($output, Throwable $e) {}
    };

    return new Worker(
        $manager->getQueueManager(),
        $dispatcher,
        $handler,
        $isDownForMaintenanceHandler
    );
};

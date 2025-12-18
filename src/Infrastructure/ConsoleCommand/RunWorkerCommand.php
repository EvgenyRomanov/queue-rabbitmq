<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand;

use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/** @psalm-suppress UnusedClass */
#[AsCommand(name: 'worker:run', description: "Запуск worker'а обработки jobs")]
final class RunWorkerCommand extends Command
{
    public function __construct(private readonly Worker $worker)
    {
        parent::__construct();
    }

    #[Override]
    protected function configure(): void
    {
        $this
            ->addArgument('connection_name', null, 'Имя соединения', 'default')
            ->addArgument('queue', null, 'Имя очереди', 'default')
            ->addOption(
                'worker_name',
                null,
                InputOption::VALUE_OPTIONAL,
                'The name of the worker',
                'default'
            )
            ->addOption(
                'backoff',
                null,
                InputOption::VALUE_OPTIONAL,
                'The number of seconds to wait before retrying a job that encountered an uncaught exception',
                0
            )
            ->addOption(
                'memory',
                null,
                InputOption::VALUE_OPTIONAL,
                'The maximum amount of RAM the worker may consume',
                128
            )
            ->addOption(
                'timeout',
                null,
                InputOption::VALUE_OPTIONAL,
                'The maximum number of seconds a child worker may run',
                60
            )
            ->addOption(
                'sleep',
                null,
                InputOption::VALUE_OPTIONAL,
                'The number of seconds to wait in between polling the queue',
                3
            )
            ->addOption(
                'max_tries',
                null,
                InputOption::VALUE_OPTIONAL,
                'The maximum amount of times a job may be attempted',
                1
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_OPTIONAL,
                'Indicates if the worker should run in maintenance mode',
                false
            )
            ->addOption(
                'stop_when_empty',
                null,
                InputOption::VALUE_OPTIONAL,
                'Indicates if the worker should stop when the queue is empty',
                false
            )
            ->addOption(
                'max_jobs',
                null,
                InputOption::VALUE_OPTIONAL,
                'The maximum number of jobs to run',
                0
            )
            ->addOption(
                'max_time',
                null,
                InputOption::VALUE_OPTIONAL,
                'The maximum number of seconds a worker may live',
                0
            )
            ->addOption(
                'rest',
                null,
                InputOption::VALUE_OPTIONAL,
                'The number of seconds to rest between jobs',
                0
            );
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return CommandHelperCommand::execute(function () use ($input): void {
            $connectionName = (string) $input->getArgument('connection_name');
            $queue = (string) $input->getArgument('queue');

            $workerName = (string) $input->getOption('worker_name');
            $backoff = (int) $input->getOption('backoff');
            $memory = (int) $input->getOption('memory');
            $timeout = (int) $input->getOption('timeout');
            $sleep = (int) $input->getOption('sleep');
            $maxTries = (int) $input->getOption('max_tries');
            $force = (bool) $input->getOption('force');
            $stopWhenEmpty = (bool) $input->getOption('stop_when_empty');
            $maxJobs = (int) $input->getOption('max_jobs');
            $maxTime = (int) $input->getOption('max_time');
            $rest = (int) $input->getOption('rest');

            $this->worker->daemon(
                $connectionName,
                $queue,
                new WorkerOptions(
                    $workerName,
                    $backoff,
                    $memory,
                    $timeout,
                    $sleep,
                    $maxTries,
                    $force,
                    $stopWhenEmpty,
                    $maxJobs,
                    $maxTime,
                    $rest
                )
            );
        }, $input, $output);
    }
}

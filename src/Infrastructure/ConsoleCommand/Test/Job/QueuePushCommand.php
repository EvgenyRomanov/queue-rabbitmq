<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand\Test\Job;

use App\Infrastructure\ConsoleCommand\CommandHelperCommand;
use App\Infrastructure\Job\ExampleJob;
use App\Infrastructure\Job\ExampleJob2;
use App\Infrastructure\Job\ExampleJob3;
use Illuminate\Contracts\Bus\Dispatcher;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/** @psalm-suppress UnusedClass */
#[AsCommand(name: 'queue:push', description: 'Тестирование queue')]
final class QueuePushCommand extends Command
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return CommandHelperCommand::execute(function (): void {
            $this->dispatcher->dispatch(new ExampleJob(['message' => 1]));
            \Illuminate\Queue\Capsule\Manager::pushOn('queue2', new ExampleJob(['message' => 2]));
            \Illuminate\Queue\Capsule\Manager::pushOn('default', new ExampleJob2(['message' => 3]));
            $this->dispatcher->dispatch(new ExampleJob3(['message' => 3]));
        }, $input, $output);
    }
}

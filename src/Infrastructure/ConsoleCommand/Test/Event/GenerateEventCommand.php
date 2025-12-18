<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand\Test\Event;

use App\Infrastructure\ConsoleCommand\CommandHelperCommand;
use App\Infrastructure\Event\CreateTt;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/** @psalm-suppress UnusedClass */
#[AsCommand(name: 'events:generate', description: 'Консольная команда для тестов')]
final class GenerateEventCommand extends Command
{
    public function __construct(
        private readonly \Illuminate\Contracts\Events\Dispatcher $dispatcher
    ) {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return CommandHelperCommand::execute(function (): void {
            $this->dispatcher->dispatch(new CreateTt('1q2w3e'));
        }, $input, $output);
    }
}

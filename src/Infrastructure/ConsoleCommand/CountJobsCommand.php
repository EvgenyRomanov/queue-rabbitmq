<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand;

use Illuminate\Database\Capsule\Manager as Capsule;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/** @psalm-suppress UnusedClass */
#[AsCommand(name: 'queue:count_jobs', description: 'Кол-во jobs на текущий момент')]
final class CountJobsCommand extends Command
{
    public function __construct(private readonly Capsule $capsule)
    {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return CommandHelperCommand::execute(function () use ($output): void {
            $count = $this->capsule::table('illuminate_failed_jobs')->count();
            $output->writeln("<error>failed_jobs</error>: {$count}");

            $count = $this->capsule::table('illuminate_jobs')->count();
            $output->writeln("<info>jobs:</info> {$count}");
        }, $input, $output);
    }
}

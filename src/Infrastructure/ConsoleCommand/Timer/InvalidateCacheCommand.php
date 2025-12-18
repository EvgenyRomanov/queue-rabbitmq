<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand\Timer;

use App\Infrastructure\ConsoleCommand\CommandHelperCommand;
use Override;
use Symfony\Component\Cache\Adapter\PdoAdapter as Cache;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/** @psalm-suppress UnusedClass */
#[AsCommand(name: 'cache:invalidate', description: 'Инвалидация кэша')]
final class InvalidateCacheCommand extends Command
{
    public function __construct(private readonly Cache $cache)
    {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return CommandHelperCommand::execute(function (): void {
            $this->cache->prune();
        }, $input, $output);
    }
}

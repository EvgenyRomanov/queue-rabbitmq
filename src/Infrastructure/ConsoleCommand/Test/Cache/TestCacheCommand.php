<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand\Test\Cache;

use App\Infrastructure\ConsoleCommand\CommandHelperCommand;
use Override;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\ItemInterface;

/** @psalm-suppress UnusedClass */
#[AsCommand(name: 'cache:test', description: 'Консольная команда для тестов')]
final class TestCacheCommand extends Command
{
    public function __construct(private readonly PdoAdapter $cache)
    {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return CommandHelperCommand::execute(function (): void {
            /** @var string $val */
            $val = $this->cache->get('my_cache_key', static function (ItemInterface $item): string {
                $item->expiresAfter(30);
                return 'foobar';
            });
            dump($val);

            $cacheItem = $this->cache->getItem('some-arbitrary-id');

            if (! $cacheItem->isHit()) {
                $cacheItem->set('some new value');
                $this->cache->save($cacheItem);
            }

            $this->cache->prune();
        }, $input, $output);
    }
}

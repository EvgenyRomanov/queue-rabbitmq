<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class CommandHelperCommand
{
    /**
     * @psalm-suppress UnusedParam
     */
    public static function execute(callable $function, InputInterface $input, OutputInterface $output): int
    {
        try {
            call_user_func($function);
        } catch (Throwable $th) {
            $output->writeln("<error>{$th->getMessage()}</error>");
            return Command::FAILURE;
        }

        $output->writeln('<info>Command is completed</info>');
        return Command::SUCCESS;
    }
}

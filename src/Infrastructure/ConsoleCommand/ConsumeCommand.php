<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand;

use App\Infrastructure\Queues\AMQPHelper;
use App\Service\TroubleTicketService;
use Override;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/** @psalm-suppress UnusedClass */
#[AsCommand(name: 'amqp:consume_notify', description: 'Читает нотификации о создании тикетов')]
final class ConsumeCommand extends Command
{
    public function __construct(
        private readonly AMQPStreamConnection $connection,
        private readonly TroubleTicketService $troubleTicketService,
    ) {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return CommandHelperCommand::execute(function () use ($output): void {

            $output->writeln('<comment>Consume messages</comment>');

            $connection = $this->connection;

            $channel = $connection->channel();

            AMQPHelper::initNotifications($channel);
            AMQPHelper::registerShutdown($connection, $channel);

            /** @var int $myPid */
            $myPid = getmypid();
            $consumerTag = 'consumer_' . $myPid;

            /** @psalm-suppress MissingClosureParamType */
            $channel->basic_consume(AMQPHelper::QUEUE_NOTIFICATIONS, $consumerTag, false, false, false, false, function ($message) use ($output): void {
                /**
                 * @var array{id_tt: int} $msg
                 * @var object{body: string, message: string, delivery_info: array{string, mixed}} $message
                 */

                $msg = json_decode($message->body, true);
                $output->writeln(print_r($msg, true));
                $this->troubleTicketService->updateStatus($msg['id_tt']);
                $output->writeln("Message processed");

                /** @var AMQPChannel $channel */
                /** @psalm-suppress MixedAssignment, InvalidArrayOffset */
                $channel = $message->delivery_info['channel'];
                /** @psalm-suppress MixedMethodCall, PossiblyInvalidMethodCall, PossiblyInvalidMethodCall, InvalidArrayOffset */
                $channel->basic_ack($message->delivery_info['delivery_tag']);
            });

            /** @psalm-suppress InternalProperty */
            while (\count($channel->callbacks)) {
                $channel->wait();
            }

            $output->writeln('<info>Done!</info>');

        }, $input, $output);
    }
}

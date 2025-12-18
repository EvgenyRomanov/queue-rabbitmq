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

            $consumerTag = 'consumer_' . getmypid();
            $channel->basic_consume(AMQPHelper::QUEUE_NOTIFICATIONS, $consumerTag, false, false, false, false, function ($message) use ($output): void {
                $msg = json_decode($message->body, true);
                $output->writeln(print_r($msg, true));
                $this->troubleTicketService->updateStatus($msg['id_tt']);
                $output->writeln("Message processed");

                /** @var AMQPChannel $channel */
                $channel = $message->delivery_info['channel'];
                $channel->basic_ack($message->delivery_info['delivery_tag']);
            });

            while (\count($channel->callbacks)) {
                $channel->wait();
            }

            $output->writeln('<info>Done!</info>');

        }, $input, $output);
    }
}

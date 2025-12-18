<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Infrastructure\Queues\AMQPHelper;
use App\Service\Event\CreateTtEvent;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final readonly class CreateTtListener
{
    public function __construct(private AMQPStreamConnection $connection) {}

    public function handle(CreateTtEvent $event): void
    {
        $connection = $this->connection;

        $channel = $this->connection->channel();

        AMQPHelper::initNotifications($channel);
        AMQPHelper::registerShutdown($connection, $channel);

        $data = [
            'id_tt' => $event->idTt,
        ];

        $message = new AMQPMessage(
            json_encode($data),
            ['content_type' => 'text/plain']
        );

        $channel->basic_publish($message, AMQPHelper::EXCHANGE_NOTIFICATIONS);
    }
}

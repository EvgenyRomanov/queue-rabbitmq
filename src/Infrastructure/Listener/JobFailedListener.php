<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use Illuminate\Database\Capsule\Manager as Capsule;

/** @psalm-suppress UnusedClass */
final class JobFailedListener
{
    private Capsule $capsule;

    public function __construct(Capsule $capsule)
    {
        $this->capsule = $capsule;
    }

    public function handle(\Illuminate\Queue\Events\JobFailed $event): void
    {
        $this->capsule->getDatabaseManager()
            ->table("illuminate_failed_jobs")
            ->insert([
                'connection' => $event->connectionName,
                'queue' => $event->job->getQueue(),
                'payload' => $event->job->getRawBody(),
                'exception' => $event->exception,
                'uuid' => $event->job->uuid(),
            ]);

        echo "Job Failed: id = {$event->job->getJobId()}, queue = {$event->job->getQueue()}, connection_name = {$event->job->getConnectionName()}\n";
    }
}

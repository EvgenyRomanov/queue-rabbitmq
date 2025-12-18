<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

/** @psalm-suppress UnusedClass */
final class JobProcessedListener
{
    public function handle(\Illuminate\Queue\Events\JobProcessed $event): void
    {
        echo "Job Processed: id = {$event->job->getJobId()}, queue = {$event->job->getQueue()}, connection_name = {$event->job->getConnectionName()}\n";
    }
}

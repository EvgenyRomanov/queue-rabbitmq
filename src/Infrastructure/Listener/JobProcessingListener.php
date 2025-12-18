<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

/** @psalm-suppress UnusedClass */
final class JobProcessingListener
{
    public function handle(\Illuminate\Queue\Events\JobProcessing $event): void
    {
        echo "Job Processing: id = {$event->job->getJobId()}, queue = {$event->job->getQueue()}, connection_name = {$event->job->getConnectionName()}\n";
    }
}

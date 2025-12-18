<?php

declare(strict_types=1);

namespace App\Service\Event;

final readonly class CreateTtEvent
{
    public function __construct(public int $idTt) {}
}

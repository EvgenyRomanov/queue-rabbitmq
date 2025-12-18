<?php

declare(strict_types=1);

namespace App\Domain\Model;

use Illuminate\Support\Collection;

interface TroubleTicketRepository
{
    public function findOneById(int $id): ?TroubleTicket;

    public function findAll(): Collection;

    public function save(TroubleTicket $tt): void;

    /** @psalm-suppress PossiblyUnusedMethod */
    public function delete(TroubleTicket $tt): void;
}

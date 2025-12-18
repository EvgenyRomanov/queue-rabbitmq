<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\TroubleTicket;
use App\Domain\Model\TroubleTicketRepository;
use Illuminate\Support\Collection;

class PersistenceTroubleTicketRepository implements TroubleTicketRepository
{
    public function findOneById(int $id): ?TroubleTicket
    {
        return TroubleTicket::query()->find($id);
    }

    public function findAll(): Collection
    {
        return TroubleTicket::all();
    }

    public function save(TroubleTicket $tt): void
    {
        if (! $tt->save()) {
            throw new \RuntimeException('TroubleTicket saving error.');
        }
    }

    public function delete(TroubleTicket $tt): void
    {
        if (! $tt->delete()) {
            throw new \RuntimeException('TroubleTicket deleting error.');
        }
    }
}

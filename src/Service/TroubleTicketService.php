<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Model\Status;
use App\Domain\Model\TroubleTicket;
use App\Domain\Model\TroubleTicketRepository;
use App\Service\Event\CreateTtEvent;
use DomainException;

final readonly class TroubleTicketService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private TroubleTicketRepository $troubleTicketRepository,
        private \Illuminate\Contracts\Events\Dispatcher $eventDispatcher
    ) {}

    public function create(CreateTtDto $dto): array
    {
        $tt = TroubleTicket::createNew($dto->title, $dto->message);
        $this->troubleTicketRepository->save($tt);
        $this->eventDispatcher->dispatch(new CreateTtEvent($tt->id));

        return $tt->toArray();
    }

    public function updateStatus(int $id): void
    {
        sleep(10);

        /** @var TroubleTicket $tt */
        $tt = $this->troubleTicketRepository->findOneById($id);

        /** @psalm-suppress DocblockTypeContradiction */
        if ($tt === null) {
            throw new DomainException('Trouble Ticket not found.');
        }

        $tt->status = Status::DONE;
        $this->troubleTicketRepository->save($tt);
    }
}

<?php

use App\Domain\Model\TroubleTicketRepository;
use App\Infrastructure\Repository\PersistenceTroubleTicketRepository;
use Illuminate\Container\Container;

return static function (Container $container): void {
    $container->singleton(TroubleTicketRepository::class, PersistenceTroubleTicketRepository::class);
};

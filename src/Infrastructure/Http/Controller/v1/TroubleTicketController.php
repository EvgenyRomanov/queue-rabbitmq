<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller\v1;

use App\Domain\Model\TroubleTicketRepository;
use App\Service\CreateTtDto;
use App\Service\TroubleTicketService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class TroubleTicketController
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private TroubleTicketService $createTroubleTicket,
        private TroubleTicketRepository $troubleTicketRepository,
    ) {}

    /** @psalm-suppress PossiblyUnusedMethod, UnusedParam */
    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        /** @var array{title: string, message: string} $params */
        $params = $request->getParsedBody();
        $tt = $this->createTroubleTicket->create(new CreateTtDto(
            $params['title'],
            $params['message'],
        ));
        $response->getBody()->write((string) json_encode($tt));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    /** @psalm-suppress PossiblyUnusedMethod, UnusedParam */
    public function showStatus(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int) $args['id'];
        $tt = $this->troubleTicketRepository->findOneById($id);

        if (is_null($tt)) {
            return $response->withStatus(404);
        }

        $response->getBody()->write((string) json_encode(['status' => $tt->status->value]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /** @psalm-suppress PossiblyUnusedMethod, UnusedParam */
    public function all(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $tts = $this->troubleTicketRepository->findAll();
        $response->getBody()->write((string) json_encode($tts));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}

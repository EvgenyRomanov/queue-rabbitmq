<?php

use App\Infrastructure\Http\Controller\v1\TroubleTicketController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/api/v1/', function (RouteCollectorProxy $group) {
        $group->post('tt', [TroubleTicketController::class, 'create']);
        $group->get('tt', [TroubleTicketController::class, 'all']);
        $group->get('tt/{id:[0-9]+}',  [TroubleTicketController::class, 'showStatus']);
    });
};

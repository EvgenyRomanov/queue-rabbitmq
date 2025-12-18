<?php

declare(strict_types=1);

namespace Tests\Functional;

use Tests\BaseTestAbstract;

/**
 * @internal
 */
final class ControllerAPIv2ActionTest extends BaseTestAbstract
{
    public function testGetEventById(): void
    {
        $request = $this->createRequest('GET', "/api/v2/test2");

        /** @psalm-suppress PossiblyNullReference */
        $response = $this->app->handle($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(['test' => 'index'], $this->parseResponse($response));
    }
}

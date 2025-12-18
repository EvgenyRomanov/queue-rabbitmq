<?php

declare(strict_types=1);

namespace Tests\Functional;

use Illuminate\Support\Str;
use Tests\BaseTestAbstract;

final class ControllerHome extends BaseTestAbstract
{
    public function testGetEventById(): void
    {
        $request = $this->createRequest('GET', "/", []);

        /** @psalm-suppress PossiblyNullReference */
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(Str::contains($response->getBody()->getContents(), "Hello, world!"));
    }
}

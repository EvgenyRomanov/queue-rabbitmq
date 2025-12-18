<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Infrastructure\TestService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TestServiceTest extends TestCase
{
    public function testHandle(): void
    {
        $testService = new TestService();

        self::assertEquals("Test Service", $testService->handle());
    }
}

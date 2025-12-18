<?php

declare(strict_types=1);

namespace Tests\Integration\EloquentModels;

use App\Domain\Model\User;
use Tests\BaseTestAbstract;

/**
 * @internal
 */
final class UserTest extends BaseTestAbstract
{
    public function testCreateUser(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        self::assertArrayHasKey('email', $user->toArray());
    }
}

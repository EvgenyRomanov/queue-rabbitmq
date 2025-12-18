<?php

declare(strict_types=1);

namespace App\Domain\Model;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Override;

/** @psalm-suppress MissingTemplateParam, UnusedClass */
final class StatusCast implements CastsAttributes
{
    #[Override]
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Status
    {
        /** @psalm-suppress MixedArgument */
        return Status::from($value);
    }

    #[Override]
    /**
     * Prepare the given value for storage.
     * @param Status $value
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        /**
         * @psalm-suppress MixedPropertyFetch
         * @psalm-suppress PossiblyNullPropertyFetch
         * @psalm-suppress MixedReturnStatement
         * @psalm-suppress NullableReturnStatement
         */
        return $value->value;
    }
}

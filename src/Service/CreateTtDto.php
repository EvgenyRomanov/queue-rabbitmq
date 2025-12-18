<?php

namespace App\Service;

final readonly class CreateTtDto
{
    public function __construct(public string $title, public string $message)
    {
    }
}

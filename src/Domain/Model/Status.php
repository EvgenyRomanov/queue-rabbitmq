<?php

declare(strict_types=1);

namespace App\Domain\Model;

enum Status: string
{
    case DONE = 'done';
    case IN_WORK = 'in_work';
}

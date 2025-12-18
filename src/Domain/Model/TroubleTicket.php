<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Factories\TroubleTicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property Status $status
 */
final class TroubleTicket extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $table = 'trouble_tickets';

    protected $casts = [
        'status' => StatusCast::class,
    ];

    protected static function newFactory()
    {
        return TroubleTicketFactory::new();
    }

    public static function createNew(string $title, string $message): self
    {
        return new self([
            'title' => $title,
            'message' => $message,
            'status' => Status::IN_WORK
        ]);
    }
}

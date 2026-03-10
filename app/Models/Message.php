<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property ?int $sender_id
 * @property ?int $receiver_id
 * @property ?string $text
 * @property bool $is_read
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read ?User $sender
 * @property-read ?User $receiver
 */
class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'text',
        'is_read',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}

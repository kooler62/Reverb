<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MessageStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public readonly int $messageId,
        public readonly int $senderId,
        public readonly bool $isRead,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('messages.' . $this->senderId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->messageId,
            'is_read' => $this->isRead,
        ];
    }
}
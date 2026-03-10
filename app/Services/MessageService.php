<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{
    public function getMessagesForUser(User $user): LengthAwarePaginator
    {
        return Message::query()
            ->where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->paginate();
    }

    public function getAvailableReceivers(User $excludeUser): Collection
    {
        return User::query()
            ->where('id', '!=', $excludeUser->id)
            ->orderBy('name')
            ->get();
    }

    public function markAsRead(Message $message, User $user): void
    {
        if ($message->receiver_id === $user->id && !$message->is_read) {
            $message->update(['is_read' => true]);
        }
    }

    public function sendMessage(User $sender, int $receiverId, string $text): Message
    {
        $message = Message::query()->create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'text' => $text,
        ]);

        $message->load(['sender', 'receiver']);
        MessageSent::dispatch($message);

        return $message;
    }
}
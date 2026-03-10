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

    public function markAsRead(Message $message): void
    {
        if ($message->receiver_id === auth()->user()->id && ! $message->is_read) {
            $message->update(['is_read' => true]);
        }
    }

    public function markAsUnread(Message $message): void
    {
        if ($message->receiver_id === auth()->user()->id && $message->is_read) {
            $message->update(['is_read' => false]);
        }
    }

    public function markAllAsRead(User $user): int
    {
        return Message::query()
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function unreadCount(User $user): int
    {
        return Message::query()
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
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

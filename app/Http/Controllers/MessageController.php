<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService,
    ) {}

    public function index(): View
    {
        $authUser = auth()->user();
        $messages = $this->messageService->getMessagesForUser($authUser);
        $users = $this->messageService->getAvailableReceivers($authUser);
        $unreadCount = $this->messageService->unreadCount($authUser);

        return view('messages.index', compact('messages', 'users', 'unreadCount'));
    }

    public function store(StoreMessageRequest $request): JsonResponse
    {
        $message = $this->messageService->sendMessage(
            auth()->user(),
            $request->validated('receiver_id'),
            $request->validated('text'),
        );

        return response()->json([
            'id' => $message->id,
            'sender_name' => $message->sender?->name,
            'receiver_name' => $message->receiver?->name,
            'text' => $message->text,
            'created_at' => $message->created_at->toISOString(),
        ]);
    }

    public function read(Message $message): JsonResponse
    {
        $this->messageService->markAsRead($message);

        return response()->json(['success' => true]);
    }

    public function unread(Message $message): JsonResponse
    {
        $this->messageService->markAsUnread($message);

        return response()->json(['success' => true]);
    }

    public function readAll(): JsonResponse
    {
        $this->messageService->markAllAsRead(auth()->user());

        return response()->json(['success' => true]);
    }
}

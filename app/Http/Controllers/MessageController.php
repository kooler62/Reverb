<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService,
    )
    {
    }

    public function index(): View
    {
        $authUser = auth()->user();
        $messages = $this->messageService->getMessagesForUser($authUser);
        $users = $this->messageService->getAvailableReceivers($authUser);

        return view('messages.index', compact('messages', 'users'));
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $this->messageService->sendMessage(
            auth()->user(),
            $request->validated('receiver_id'),
            $request->validated('text'),
        );

        return redirect()->route('messages.index');
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
}

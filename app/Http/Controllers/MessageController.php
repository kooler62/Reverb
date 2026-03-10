<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $authUser = auth()->user();
        $messages = Message::query()
            ->where('receiver_id', $authUser->id)
            ->orWhere('sender_id', $authUser->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->paginate();

        $users = User::query()
            ->where('id', '!=', $authUser->id)
            ->orderBy('name')
            ->get();

        return view('messages.index', compact('messages', 'users'));
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        Message::query()->create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->validated('receiver_id'),
            'text' => $request->validated('text'),
        ]);

        return redirect()->route('messages.index');
    }
}

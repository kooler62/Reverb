<?php

namespace App\Http\Controllers;

use App\Models\Message;
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

        return view('messages.index', compact('messages'));
    }
}

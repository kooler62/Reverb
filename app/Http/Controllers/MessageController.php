<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $messages = Message::query()
            ->with(['sender', 'receiver'])
            ->latest()
            ->paginate();

        return view('messages.index', compact('messages'));
    }
}
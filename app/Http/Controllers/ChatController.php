<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $chats = Chat::with(['user', 'messages'])->latest()->get();
        } else {
            $chats = Chat::where('user_id', $user->id)
                        ->with(['admin', 'messages'])
                        ->latest()
                        ->get();
        }
        $isAdmin = $user->role=='admin' ? true : false;
        return view('backend.chat', compact('chats', 'isAdmin'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            $chat = Chat::create([
                'user_id' => $user->id,
                'admin_id' => 1, // Assign to main admin, you might want to modify this
                'status' => 'active'
            ]);
        }

        return redirect()->route('chat.show', $chat->id);
    }

    public function show($id)
    {
        $chat = Chat::with(['messages.user', 'user', 'admin'])->findOrFail($id);
        $isAdmin = Auth::user()->role=='admin' ? true : false;
        return view('backend.chat-messages', compact('chat','isAdmin'));
    }

    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $chat = Chat::findOrFail($chatId);
        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message->load('user'));
    }

    public function markAsRead($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        $chat->messages()
            ->where('user_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
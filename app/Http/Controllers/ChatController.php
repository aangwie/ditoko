<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $buyers = User::where('role', 'buyer')
                ->where(function($q) {
                    $q->whereHas('messagesSent')->orWhereHas('messagesReceived');
                })->get();
            return view('chat.admin', compact('buyers'));
        }
        $admin = User::where('role', 'admin')->first();
        return view('chat.buyer', compact('admin'));
    }

    public function fetchMessages(User $user, Request $request)
    {
        $lastId = $request->query('last_id', 0);
        
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
        })->where('id', '>', $lastId)
          ->with(['sender', 'receiver'])
          ->orderBy('created_at')
          ->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,docx,doc|max:10240',
        ]);

        if (!$request->message && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message or attachment required'], 422);
        }

        $data = [
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message ?? '',
        ];

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chat-attachments', 'public');
            $data['attachment'] = $path;
        }

        $message = Message::create($data);

        return response()->json($message->load('sender'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $messages = Message::where('receiver_id', $user->id)->get();
        
        return inertia('Messages/Index', [
            'messages' => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|exists:users,login',
            'subject' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $receiver = User::where('login', $request->login)->first();
        
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'subject' => $request->subject,
            'content' => $request->content
        ]);

        return redirect()->route('messages.index')->with('success', 'Message envoyé avec succès.');
    }
}

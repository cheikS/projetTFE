<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Inertia\Inertia;

use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $messages = Message::where('receiver_id', $user->id)->get();
        $sentMessages = Message::where('sender_id', $user->id)->get();
    
        return Inertia::render('Messages/Index', [
            'messages' => $messages,
            'sentMessages' => $sentMessages,
        ]);
    }

    public function sentMessages()
    {
        $messages = auth()->user()->sentMessages()->with('receiver')->get(); // Charger les messages envoyés avec le récepteur
        return Inertia::render('Messages/SentMessages', [
            'messages' => $messages->toArray()
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

    public function show($id)
    {
        $message = Message::with('sender')->findOrFail($id);
    
        // Marquer le message comme lu
        if (Auth::id() === $message->receiver_id && !$message->is_read) {
            $message->update(['is_read' => true]);
        }
    
        return inertia('Messages/MessageDetails', ['message' => $message]);
    }
    

    
    public function reply(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'receiver_id' => 'required|exists:users,id'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
    }

  

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatController extends Controller
{
    //
    // Affiche la liste des utilisateurs
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('chat', [
        'users' => $users,
        'selectedConversation' => null
    ]);
    }

    // Affiche une conversation spécifique
    public function show(User $user)
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $currentUser = Auth::user();
        
        // Trouver ou créer la conversation
        $conversation = $currentUser->conversations()
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach([$currentUser->id, $user->id]);
        }
        
        $conversation->load('messages.user');

        return view('chat', [
            'users' => $users,
            'selectedConversation' => $conversation
        ]);
    }

    // Enregistre un nouveau message
    public function store(Request $request, Conversation $conversation)
    {
        $request->validate(['body' => 'required|string']);

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        // Ici, nous diffuserons l'événement plus tard
        // Diffuse l'événement aux autres utilisateurs
        broadcast(new MessageSent($message->load('user')))->toOthers();

        return back();
    }
}

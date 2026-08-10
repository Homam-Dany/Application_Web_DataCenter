<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Affiche l'interface de messagerie
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        
        $unreadCounts = Message::select('sender_id', \DB::raw('count(*) as count'))
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id')
            ->toArray();

        return view('messagerie.index', compact('users', 'unreadCounts'));
    }

    // Récupère l'historique des messages avec un utilisateur spécifique
    public function fetchMessages($userId)
    {
        $authId = Auth::id();
        
        $messages = Message::where(function($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)->where('receiver_id', $userId);
        })->orWhere(function($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $authId);
        })->orderBy('created_at', 'asc')->get();

        // Marquer comme lu
        Message::where('sender_id', $userId)
               ->where('receiver_id', $authId)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // Envoie un nouveau message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
            'is_read' => false
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }
}

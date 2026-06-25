<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getSessions()
    {
        $user = Auth::user();
        $sessions = ChatSession::with('pharmacy')
            ->where('user_id', $user->id)
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_read', false)
                      ->where('sender_type', 'pharmacy');
            }])
            ->orderByDesc(
                ChatMessage::select('created_at')
                    ->whereColumn('chat_session_id', 'chat_sessions.id')
                    ->latest()
                    ->take(1)
            )
            ->get();
        return response()->json(['sessions' => $sessions]);
    }

    public function markAsRead($sessionId)
    {
        $user = Auth::user();
        $session = ChatSession::where('user_id', $user->id)->findOrFail($sessionId);

        $session->messages()
                ->where('sender_type', 'pharmacy')
                ->where('is_read', false)
                ->update(['is_read' => true]);

        broadcast(new \App\Events\MessageRead($session->id, 'user'))->toOthers();

        return response()->json(['success' => true]);
    }

    public function startSession(Request $request)
    {
        $request->validate(['pharmacy_id' => 'required|exists:pharmacies,id']);
        $user = Auth::user();
        
        $session = ChatSession::firstOrCreate([
            'user_id' => $user->id,
            'pharmacy_id' => $request->pharmacy_id
        ]);

        return response()->json(['session' => $session->load('pharmacy')]);
    }

    public function getMessages($sessionId)
    {
        $user = Auth::user();
        $session = ChatSession::where('user_id', $user->id)->findOrFail($sessionId);

        $messages = $session->messages()->orderBy('created_at', 'asc')->get();
        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request, $sessionId)
    {
        $user = Auth::user();
        $session = ChatSession::where('user_id', $user->id)->findOrFail($sessionId);

        $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // 10MB
        ]);

        if (!$request->message && !$request->hasFile('file')) {
            return response()->json(['error' => 'Message or file is required'], 400);
        }

        $type = 'text';
        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            
            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mimeType, 'audio/') || $extension === 'webm' || $extension === 'm4a' || $extension === 'mp3' || $extension === 'wav' || $extension === 'ogg') {
                $type = 'voice';
            } else {
                $type = 'file';
            }
            $filePath = $file->store('chat_files', 'public');
        }

        $message = $session->messages()->create([
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'type' => $type,
            'body' => $request->message,
            'file_path' => $filePath,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        // Notify Pharmacy Owner
        $pharmacyUser = \App\Models\User::where('role', 'pharmacy')
            ->whereHas('pharmacy', function ($q) use ($session) {
                $q->where('id', $session->pharmacy_id);
            })->first();

        if ($pharmacyUser) {
            $pharmacyUser->notify(new \App\Notifications\SystemNotification(
                'رسالة جديدة 💬',
                "لديك رسالة جديدة من {$user->name}",
                'info',
                '/pharmacy/chats'
            ));
        }

        return response()->json(['message' => $message]);
    }
}

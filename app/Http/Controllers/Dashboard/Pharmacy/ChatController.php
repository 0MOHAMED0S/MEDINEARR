<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

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
        $pharmacy = $user->pharmacy;
        
        if (!$pharmacy) {
            return response()->json(['sessions' => []]);
        }

        $sessions = ChatSession::with('user')
            ->where('pharmacy_id', $pharmacy->id)
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_read', false)
                      ->where('sender_type', 'user');
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
        $pharmacy = $user->pharmacy;

        if (!$pharmacy) {
            return response()->json(['error' => 'Not a pharmacy'], 403);
        }

        $session = ChatSession::where('pharmacy_id', $pharmacy->id)->findOrFail($sessionId);

        $session->messages()
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->update(['is_read' => true]);

        broadcast(new \App\Events\MessageRead($session->id, 'pharmacy'))->toOthers();

        return response()->json(['success' => true]);
    }

    public function getMessages($sessionId)
    {
        $user = Auth::user();
        $pharmacy = $user->pharmacy;

        if (!$pharmacy) {
            return response()->json(['messages' => []], 403);
        }

        $session = ChatSession::where('pharmacy_id', $pharmacy->id)->findOrFail($sessionId);
        $messages = $session->messages()->orderBy('created_at', 'asc')->get();
        
        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request, $sessionId)
    {
        $user = Auth::user();
        $pharmacy = $user->pharmacy;

        if (!$pharmacy) {
            return response()->json(['error' => 'Not a pharmacy'], 403);
        }

        $session = ChatSession::where('pharmacy_id', $pharmacy->id)->findOrFail($sessionId);

        $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
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
            'sender_type' => 'pharmacy',
            'sender_id' => $pharmacy->id,
            'type' => $type,
            'body' => $request->message,
            'file_path' => $filePath,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        // Notify Mobile User
        if ($session->user) {
            $session->user->notify(new \App\Notifications\SystemNotification(
                'رسالة جديدة 💬',
                "لديك رسالة جديدة من صيدلية {$pharmacy->pharmacy_name}",
                'info',
                null // Handled by mobile app
            ));
        }

        return response()->json(['message' => $message]);
    }
}

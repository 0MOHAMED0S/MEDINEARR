<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\SendMessageRequest;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;

class ChatController extends Controller
{
    /**
     * 1. Create or open conversation
     */
    public function createConversation(Request $request)
    {
        try {
            $request->validate([
                'pharmacy_id' => 'required|exists:pharmacies,id',
            ]);

            $user = auth('sanctum')->user();

            $conversation = Conversation::whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereHas('participants', function ($q) use ($request) {
                $q->where('pharmacy_id', $request->pharmacy_id);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create();

                $conversation->participants()->createMany([
                    [
                        'user_id' => $user->id,
                        'type' => 'user'
                    ],
                    [
                        'pharmacy_id' => $request->pharmacy_id,
                        'type' => 'pharmacy'
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Conversation ready',
                'data' => [
                    'conversation_id' => $conversation->id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating conversation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
    * 2. Get user conversations with last message
    */
    public function getConversations(Request $request)
    {
        try {

            $user = auth('sanctum')->user();

            $conversations = Conversation::whereHas('participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with([
                    'participants',
                    'messages' => function ($q) {
                        $q->latest()->limit(1);
                    }
                ])
                ->latest()
                ->get()
                ->map(function ($conversation) {

                    $lastMessage = $conversation->messages->first();

                    return [
                        'conversation_id' => $conversation->id,

                        // 🟢 آخر رسالة
                        'last_message' => $lastMessage ? [
                            'id' => $lastMessage->id,
                            'type' => $lastMessage->type,
                            'message' => $lastMessage->is_deleted ? null : $lastMessage->message,
                            'created_at' => $lastMessage->created_at,
                        ] : null,

                        // 🟢 participants
                        'participants' => $conversation->participants,

                        // 🟢 وقت آخر تحديث
                        'updated_at' => $conversation->updated_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Conversations retrieved successfully',
                'data' => $conversations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving conversations',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 3. Send message
     */
    public function sendMessage(SendMessageRequest $request)
    {
        try {
            $user = auth('sanctum')->user();

            $filePath = null;

            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('chat', 'public');
            }

            $message = Message::create([
                'conversation_id' => $request->conversation_id,
                'sender_type' => 'user',
                'sender_id' => $user->id,
                'type' => $request->type,
                'message' => $request->message,
                'file_path' => $filePath,
            ]);

            broadcast(new \App\Events\MessageSent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => [
                    'id' => $message->id,
                    'conversation_id' => $message->conversation_id,
                    'type' => $message->type,
                    'message' => $message->message,
                    'file' => $filePath ? asset('storage/' . $filePath) : null,
                    'created_at' => $message->created_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending message',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 4. Get messages
     */
    public function getMessages(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'required|exists:conversations,id',
            ]);

            $user = auth('sanctum')->user();

            $conversation = Conversation::where('id', $request->conversation_id)
                ->whereHas('participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->first();

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $messages = Message::where('conversation_id', $conversation->id)
                ->latest()
                ->paginate(20);

            $messages->getCollection()->transform(function ($msg) {
                return [
                    'id' => $msg->id,
                    'conversation_id' => $msg->conversation_id,
                    'type' => $msg->type,
                    'message' => $msg->is_deleted ? null : $msg->message,
                    'file' => $msg->file_path ? asset('storage/' . $msg->file_path) : null,
                    'sender_type' => $msg->sender_type,
                    'created_at' => $msg->created_at,
                    'is_deleted' => $msg->is_deleted,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Messages retrieved successfully',
                'data' => $messages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving messages',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 5. Delete message (HARD DELETE + SECURE)
     */
    public function deleteMessage(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'required|exists:conversations,id',
                'message_id' => 'required|exists:messages,id',
            ]);

            $user = auth('sanctum')->user();

            // 🔐 check conversation access
            $isParticipant = Conversation::where('id', $request->conversation_id)
                ->whereHas('participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->exists();

            if (!$isParticipant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized conversation',
                ], 403);
            }

            // 🔐 message check
            $message = Message::where('id', $request->message_id)
                ->where('conversation_id', $request->conversation_id)
                ->where('sender_id', $user->id)
                ->first();

            if (!$message) {
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found or unauthorized',
                ], 404);
            }

            // 💣 save data before delete
            $messageData = $message->toArray();

            // 💥 HARD DELETE
            $message->delete();

            // 🔥 realtime event
            broadcast(new \App\Events\MessageDeleted($messageData))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted permanently',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting message',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
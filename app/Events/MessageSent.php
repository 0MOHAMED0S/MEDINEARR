<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'type' => $this->message->type,
            'message' => $this->message->message,
            'file' => $this->message->file_path ? asset('storage/' . $this->message->file_path) : null,
            'sender_id' => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'created_at' => $this->message->created_at,
        ];
    }
}
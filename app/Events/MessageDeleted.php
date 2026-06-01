<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageDeleted implements ShouldBroadcast
{
    public $message;

    public function __construct($message)
    {
        // $message هنا array مش model
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->message['conversation_id']);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message['id'],
            'conversation_id' => $this->message['conversation_id'],
            'is_deleted' => true,
            'deleted' => true,
        ];
    }
}
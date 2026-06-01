<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageStatus extends Model
{
    protected $table = 'message_status';

    protected $guarded = [];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
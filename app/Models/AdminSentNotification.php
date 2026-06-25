<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSentNotification extends Model
{
    protected $fillable = ['target', 'title', 'message', 'type', 'recipients_count'];
}

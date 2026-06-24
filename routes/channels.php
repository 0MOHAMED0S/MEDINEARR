<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{sessionId}', function ($user, $sessionId) {
    $session = \App\Models\ChatSession::find($sessionId);
    if (!$session) return false;

    // Mobile user or Pharmacy owner
    if ($user->id === $session->user_id) {
        return true;
    }
    
    if ($user->role === 'pharmacy' && $user->pharmacy && $user->pharmacy->id === $session->pharmacy_id) {
        return true;
    }

    return false;
});

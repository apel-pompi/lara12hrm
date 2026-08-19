<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'conversation.{conversation}',
    function ($user, $conversation) {

        return true;
    }
);

Broadcast::channel(
    'follow-up-notifications.{userId}',
    function ($user, $userId) {
        return (int) $user->id === (int) $userId;
    }
);

Broadcast::channel(
    'attendance-sync.{userId}',
    function ($user, $userId) {
        return (int) $user->id === (int) $userId;
    }
);

<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'conversation.{conversation}',
    function ($user, $conversation) {

        return true;
    }
);

<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat-room', function ($chat) {
    return true;
});

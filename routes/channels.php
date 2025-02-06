<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('update-chat-rooms', function () {
    return true;
});

Broadcast::channel('update-room-chats', function (Chat $chat) {
    return true;
});

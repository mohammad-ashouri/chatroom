<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\Member;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('update-chat-rooms.{room_id}', function ($user_id, $room_id) {
    return true;
});

Broadcast::channel('update-room-chats.{room_id}', function ($user_id, $room_id) {
    return true;
});

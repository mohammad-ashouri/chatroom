<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\Member;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('update-chat-rooms', function ($user_id, $room_id) {
    return Member::where('user_id', $user_id)->where('room_id', $room_id)->exists();
});

Broadcast::channel('update-room-chats.{room_id}', function ($user_id, $room_id) {
    return Member::where('user_id', $user_id)->where('room_id', $room_id)->exists();
});

<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\Member;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('update-chat-rooms', function ($user, $room_id) {
    return Member::where('user_id', $user->id)->where('room_id',$room_id)->exists();
}, ['guards' => ['web', 'admin']]);

Broadcast::channel('update-room-chats.{roomId}', function ($user, $room_id) {
    return Member::where('user_id', $user->id)->where('room_id',$room_id)->exists();
}, ['guards' => ['web', 'admin']]);

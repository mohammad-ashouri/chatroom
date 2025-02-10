<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\Member;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('update-room-chats', function ($user, $room_id) {
    return false;
}, ['guards' => ['web', 'admin']]);

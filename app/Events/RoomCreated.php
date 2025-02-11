<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Room $room;

    /**
     *
     * @param Room $room
     * @return void
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [new Channel('rooms')];
    }

    /**
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'room.created';
    }
}

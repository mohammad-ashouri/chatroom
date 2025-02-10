<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Events\MessageSent;
use App\Events\UpdateChatRooms;
use App\Models\Room;
use Livewire\Component;

class Delete extends Component
{
    /**
     * Room id variable
     */
    public ?int $room_id;

    /**
     * Listeners
     *
     * @var string[]
     */
    protected $listeners = [
        'room-selected' => 'roomSelected',
    ];

    /**
     * Select room id after user selecting room in sidebar
     */
    public function roomSelected($id): void
    {
        $this->room_id = $id;
    }

    /**
     * Set room id after refresh or ...
     * @return void
     */
    public function mount(): void
    {
        if (request()->query('roomId')) {
            $roomId = request()->query('roomId');
            $this->roomSelected((int)$roomId);
        }
    }

    /**
     * Delete room
     */
    public function deleteRoom(): void
    {
        if ($this->room_id) {
            $room = Room::where('id', $this->room_id)
                ->where('user_id', auth()->user()->id)
                ->first();

            if (!empty($room)) {
                $room->delete();
                event(new MessageSent(auth()->user()->id, $room->id));
                $this->dispatch('modal-message', 'Room deleted successfully.');
            } else {
                $this->dispatch('modal-message', 'Error on deleting room.');
            }
        }
    }
}

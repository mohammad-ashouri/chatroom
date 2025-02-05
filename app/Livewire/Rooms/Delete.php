<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

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
     * Delete room
     */
    public function deleteRoom(): void
    {
        if ($this->room_id) {
            $room = Room::where('id', $this->room_id)->where('user_id', auth()->user()->id)->first();
            if (! empty($room)) {
                $room->delete();
                $this->dispatch('modal-message','Room deleted successfully.');
            }
            $this->dispatch('modal-message','Error on deleting room.');
        }
    }
}

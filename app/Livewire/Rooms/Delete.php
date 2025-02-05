<?php

namespace App\Livewire\Rooms;

use App\Models\Room;
use Livewire\Attributes\On;
use Livewire\Component;

class Delete extends Component
{
    /**
     * Room id variable
     * @var int|null
     */
    public ?int $room_id;

    /**
     * Listeners
     * @var string[]
     */
    protected $listeners=[
        'room-selected'=>'roomSelected',
    ];

    /**
     * Select room id after user selecting room in sidebar
     * @param $id
     * @return void
     */
    public function roomSelected($id): void
    {
        $this->room_id = $id;
    }

    /**
     * Delete room
     * @return void
     */
    public function deleteRoom(): void
    {
        if ($this->room_id) {
            Room::findOrFail($this->room_id)->delete();
            session()->flash('message', 'Room deleted successfully.');
        }
    }
}

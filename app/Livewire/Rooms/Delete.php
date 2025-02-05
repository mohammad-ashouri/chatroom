<?php

namespace App\Livewire\Rooms;

use App\Models\Room;
use Livewire\Attributes\On;
use Livewire\Component;

class Delete extends Component
{
    public ?int $room_id;

    public function mount($room_id = null)
    {
        $this->room_id = $room_id;
    }

    public function deleteRoom()
    {
        Room::findOrFail($this->room_id)->delete();
        $this->dispatch('close-modal', 'delete-room');
        session()->flash('message', 'Room deleted successfully.');
    }
}

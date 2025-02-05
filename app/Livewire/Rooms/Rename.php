<?php

namespace App\Livewire\Rooms;

use App\Models\Room;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Rename extends Component
{
    /**
     * Room id variable
     */
    public ?int $room_id;

    #[Validate([
        "required",
    ])]
    public string $room_name;
    /**
     * Listeners
     *
     * @var string[]
     */
    protected $listeners = [
        'room-selected' => 'roomSelected',
    ];

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
     * Select room id after user selecting room in sidebar
     */
    public function roomSelected($id): void
    {
        $this->room_name = Room::find($id)->name;
    }

    /**
     * Rename room
     */
    public function renameRoom(): void
    {
        if ($this->room_id) {
            $room = Room::where('id', $this->room_id)
                ->where('user_id', auth()->user()->id)
                ->first();

            if ($room) {
                $room->name = $this->room_name;
                $room->save();
                $this->dispatch('close-modal', 'rename-room');
                $this->dispatch('modal-message', 'Room renamed successfully.');
            } else {
                $this->dispatch('modal-message', 'Error on renaming room.');
            }
        }

    }
}

<?php

namespace App\Livewire\Rooms;

use App\Events\RemovedFromRoom;
use App\Models\Room;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Member extends Component
{
    public int $room_id;
    public Room $room;

    #[Validate([
        'members' => ['array', 'min:1'],
        'members.*' => [
            'required',
            'exists:users,id',
        ],
    ])]
    public $members;
    public $allMembers;

    public function mount(): void
    {
        $this->room = Room::findOrFail($this->room_id);
        $this->allMembers = User::orderBy('name')->get();
    }

    /**
     * Change and sync members
     * @return void
     */
    public function changeMembers(): void
    {
        $this->validate();

        $this->members[] = auth()->user()->id;

        $currentMembers = $this->room->users()->pluck('users.id')->toArray();

        $this->room->users()->sync($this->members);

        $removedMembers = array_diff($currentMembers, $this->members);

        foreach ($removedMembers as $removedUserId) {
            event(new RemovedFromRoom($this->room_id, $removedUserId));
        }

        $this->dispatch('close-modal', 'room-members');
    }
}

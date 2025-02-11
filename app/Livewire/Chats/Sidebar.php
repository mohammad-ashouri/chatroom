<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Sidebar extends Component
{
    #[Url]
    public ?int $roomId = null;

    protected $listeners = [
        "echo:rooms,.room.created" => 'setRooms',
    ];

    public $rooms;

    public function getListeners(): array
    {
        $listeners = [];

        foreach ($this->rooms as $room) {
            $listeners[sprintf("echo-private:update-room-chats.%s,.MessageSent1", $room->id)] = 'setRooms';
        }

        return array_merge($this->listeners, $listeners);
    }

    public function setRooms(): void
    {
        $this->rooms = Room::query()
            ->whereRelation('users', 'users.id', auth()->id())
            ->with(['chats' => function ($query) {
                $query->whereNull('deleted_at')
                    ->where('is_visible', false)
                    ->latest('created_at');
            }])
            ->orderByRaw("COALESCE(
                        (SELECT MAX(chats.created_at)
                         FROM chats
                         WHERE chats.room_id = rooms.id ),
                        rooms.created_at
                    ) DESC")
            ->get();
    }

    public function mount(): void
    {
        $this->setRooms();
    }

    public function render(): View
    {
        return view('livewire.chats.sidebar', [
            'rooms' => $this->rooms,
        ]);
    }
}

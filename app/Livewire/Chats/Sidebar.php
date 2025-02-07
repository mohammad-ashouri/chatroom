<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

#[On('room-created')]
class Sidebar extends Component
{
    #[Locked]
    #[Url]
    public ?int $roomId = null;

    protected $listeners = [
        // Static listeners go here
    ];

    public function getListeners(): array
    {
        $listeners = [];

        if ($this->roomId !== null) {
            $listeners["echo:update-room-chats.$this->roomId,MessageSent"] = '$refresh';
            $listeners["echo:update-chat-rooms.$this->roomId,UpdateChatRooms"] = '$refresh';
        }

        return array_merge($this->listeners, $listeners);
    }
    public function render(): View
    {
        return view('livewire.chats.sidebar', [
            'rooms' => Room::query()
                        ->whereRelation('users', 'users.id', auth()->id())
                        ->with(['chats' => function ($query) {
                            $query->latest('created_at');
                        }])
                        ->orderByRaw("COALESCE(
                        (SELECT MAX(chats.created_at)
                         FROM chats
                         WHERE chats.room_id = rooms.id),
                        rooms.created_at
                    ) DESC")
            ->get(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Layout;

use App\Models\Room;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class Sidebar extends Component
{
    #[Locked]
    #[Url]
    public ?int $roomId = null;

    protected $listeners = [
        'echo:room-created,.RoomCreated' => 'executeJsCode',
        'updateChats' => 'setRooms',
    ];

    public function executeJsCode()
    {
        $this->setRooms();
        $this->dispatch('$refresh')->self();
    }

    /**
     * All rooms
     * @var
     */
    public $rooms;

    /**
     * All users for private chats
     * @var
     */
    public $allUsers = [];

    /**
     * Set and load user rooms
     * @return void
     */
    public function setRooms(): void
    {
        $this->rooms = Room::query()
            ->whereRelation('users', 'users.id', auth()->user()->id)
            ->with(['chats' => function ($query) {
                $query->whereNull('deleted_at')
                    ->where(function ($query) {
                        $query->where(function ($subQuery) {
                            $subQuery->where('user_id', auth()->user()->id)
                                ->where('is_visible', false);
                        })->orWhere(function ($subQuery) {
                            $subQuery->where('user_id', '!=', auth()->user()->id);
                        });
                    })
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

    /**
     * Load all users for start private chat
     * @return void
     */
    public function loadUsers(): void
    {
        $this->allUsers = User::where('id', '!=', auth()->user()->id)->where('status',true)->get();
    }

    public function mount(): void
    {
        $this->setRooms();
        $this->loadUsers();
    }

    public function render(): View
    {
        return view('livewire.layout.sidebar', [
            'rooms' => $this->rooms,
        ]);
    }

}

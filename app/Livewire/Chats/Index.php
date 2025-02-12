<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Member;
use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * @property-read ?Room $room
 */
class Index extends Component
{
    #[Url]
    public ?int $roomId = null;

    public string $message = '';

    public bool $isLoading;

    public $chats = [];
    public ?string $messageModal = null;

    public $rooms;

    protected $rules = [
        'message' => 'required|string|min:1',
    ];

    /**
     * Listeners
     * @var array
     */
    protected $listeners = [
        'updateChats'
    ];

    public function setRooms(): void
    {
        $this->rooms = Room::query()
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
            ->get();
    }

    public function mount(): void
    {
        $this->setRooms();
    }

    /**
     * Update chats
     * @return void
     */
    public function updateChats(): void
    {
        $checkRoom = Room::where('id', $this->roomId)->exists();
        if ($this->roomId != null and $checkRoom) {
            $this->chats = Chat::where('room_id', $this->roomId)
                ->where(function ($query) {
                    $query->where('is_visible', false);
                })
                ->orderBy('created_at')
                ->get();
        } else {
            $this->chats = [];
        }
    }

    /**
     * Set modal message and open modal
     * @param $message
     * @return void
     */
    #[On('modal-message')]
    public function setModalMessage($message): void
    {
        $this->messageModal = $message;
        $this->dispatch('open-modal', 'messages-modal');
    }

    #[Computed]
    public function room(): ?Room
    {
        return $this->roomId === null ? null : Room::query()
            ->whereRelation('users', 'users.id', auth()->id())
            ->find($this->roomId);
    }

    #[On('room-selected')]
    public function selectRoom(int $id): void
    {
        $this->roomId = $id;
    }

    /**
     * Send message
     */
    public function sendMessage(): void
    {
        $this->isLoading = true;
        $this->validate();
        $chat = Chat::query()->create([
            'user_id' => auth()->id(),
            'room_id' => $this->roomId,
            'message' => $this->message,
        ]);
        event(new MessageSent(auth()->user()->id, $chat->room_id));
        $this->reset('message');
        $this->isLoading = false;
    }

    /**
     * Dispatch chat id
     */
    public function dispatchChatId($chat_id): void
    {
        $chat = Chat::where('id', $chat_id)->where('user_id', auth()->id())->firstOrFail();
        if (empty($chat)) {
            $this->messageModal = 'Access Forbidden';
            $this->dispatch('open-modal', 'messages-modal');
        } else {
            $this->dispatch('chat-id', $chat->id);
        }
    }

    public function render(): View
    {
        $this->updateChats();
        $this->isLoading = false;

        return view('livewire.chats.index', [
            'room' => $this->room,
            'chats' => $this->chats !== null,
        ]);
    }
}

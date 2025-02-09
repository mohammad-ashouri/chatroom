<?php

declare(strict_types=1);

namespace App\Livewire\Chats;

use App\Events\MessageSent;
use App\Events\UpdateChatRooms;
use App\Models\Chat;
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
    #[Locked]
    #[Url]
    public ?int $roomId = null;

    public string $message = '';

    public bool $isLoading;

    public ?string $messageModal = null;

    protected $rules = [
        'message' => 'required|string|min:1',
    ];

    protected $listeners = [
        // Static listeners go here
    ];

    public function getListeners(): array
    {
        $listeners = [];

        if ($this->roomId !== null) {
            $listeners["echo-private:update-room-chats.{$this->roomId},.MessageSent1"] = '$refresh';
        }

        return array_merge($this->listeners, $listeners);
    }

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
        broadcast(new MessageSent(auth()->user()->id, $chat->room_id));
//        event(new UpdateChatRooms(auth()->user()->id, $chat->room_id));
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
        $this->isLoading = false;

        return view('livewire.chats.index', [
            'room' => $this->room,
            'chats' => $this->room !== null ? Chat::query()
                ->where('room_id', $this->roomId)
                ->where('is_visible', false)
                ->whereNot(function ($query) {
                    $query->where('user_id', auth()->id())
                        ->where('is_visible', true);
                })
                ->orWhere(function ($query) {
                    $query->where('room_id', $this->roomId)
                        ->where('is_visible', true)
                        ->where('user_id', '!=', auth()->id());
                })
                ->orderBy('created_at')
                ->get() : [],
        ]);
    }
}

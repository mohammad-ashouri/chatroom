<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Attributes\On;
use Livewire\Component;

class Delete extends Component
{
    public Chat $chat;
    public bool $is_visible = false;
    #[On('chat-id')]
    public function showDeleteChatModal($chat_id): void
    {
        $this->dispatch('open-modal', 'delete-chat');
        $this->chat = Chat::find($chat_id);
    }

    public function deleteChat(): void
    {
        if (!$this->is_visible) {
            $this->chat->is_visible = true;
            $this->chat->save();
        } else {
            $this->chat->delete();
        }
        $this->dispatch('close-modal', 'delete-chat');
    }
}

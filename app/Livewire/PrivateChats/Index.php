<?php

namespace App\Livewire\PrivateChats;

use App\Models\Chat;
use App\Models\PrivateChat;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    /**
     * Selected user
     * @var null
     */
    #[Url]
    public $user = null;

    /**
     * Message modal text
     * @var string|null
     */
    public ?string $messageModal = null;

    /**
     * All chat
     * @var null
     */
    public $chat = null;

    /**
     * Send button loading status
     * @var bool
     */
    public bool $isLoading;

    /**
     * Load chat
     * @return void
     */
    public function loadChat(): void
    {
        $this->chat = PrivateChat::where('user_2', $this->user)->first();
        if (empty($this->chat)) {
            $this->chat = [];
        }
    }


    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $this->loadChat();
        $this->isLoading = false;

        return view('livewire.private-chats.index', [
            'chat' => $this->chat
        ]);
    }
}

<?php

namespace App\Livewire\PrivateChats;

use App\Models\PrivateChat;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    /**
     * All users for private chats
     * @var
     */
    public $allUsers = [];

    #[Url]
    #[Validate([
        'user' => 'required|exists:users,id'
    ])]
    public ?int $user = null;

    #[On('user-selected')]
    public function selectUser(int $id): void
    {
        $this->user = $id;
    }

    public function startPrivateChat(): void
    {
        $this->validate();
        PrivateChat::firstOrCreate([
            'user_1' => auth()->user()->id,
            'user_2' => $this->user,
        ]);
        $this->dispatch('close-modal', 'create-private-chat');
    }

    /**
     * Mount component
     * @return void
     */
    public function mount(): void
    {
        $this->loadUsers();
    }

    /**
     * Load all users for start private chat
     * @return void
     */
    public function loadUsers(): void
    {
        $this->allUsers = User::where('id', '!=', auth()->user()->id)->where('status', true)->get();
    }


}

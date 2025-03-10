<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Events\MessageSent;
use App\Events\RoomCreated;
use App\Models\Room;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required')]
    #[Validate('min:2')]
    #[Validate('max:80')]
    public ?string $name = null;

    /**
     * @var array<array-key, int>
     */
    #[Validate([
        'members' => ['array', 'min:1'],
        'members.*' => [
            'required',
            'exists:users,id',
        ],
    ])]
    public ?array $members = [];

    public function store(): void
    {
        if (auth()->user() === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        /** @var array{members: array<int>, name: string} $validated */
        $validated = $this->validate();

        $room = auth()->user()->rooms()->create([
            'name' => $validated['name'],
        ]);

        $validated['members'][] = auth()->id();

        $room->users()->attach($validated['members']);
        $this->dispatch('close-modal', 'create-room');
        $this->dispatch('room-selected', id: $room->id);
        event(new MessageSent(auth()->user()->id, $room->id));
        event(new RoomCreated($room));
        $this->reset();
    }
    public function render(): View
    {
        return view('livewire.rooms.create', [
            'users' => User::query()
                ->where('id', '!=', auth()->id())
                ->pluck('name', 'id'),
        ]);
    }
}

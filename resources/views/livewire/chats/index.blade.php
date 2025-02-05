<div class="bg-white dark:bg-gray-800 w-3/4">
    <x-modal-info name="messages-modal">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                    @if ($messageModal)
                        {{ $messageModal }}
                    @endif
                </h2>
                <button x-on:click="$dispatch('close-modal', 'messages-modal')"
                        class="text-gray-500 dark:text-gray-400">
                    <x-icons.x class="h-6 w-6"/>
                </button>
            </div>
        </div>
    </x-modal-info>
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                @if ($room !== null)
                    <figure
                        class="rounded h-10 w-10 flex-shrink-0 transition-opacity group-hover:opacity-90 {{ $room->user->profile }}">
                        <img src="{{ $room->user->profile }}" alt="{{ $room->user->name }}" class="rounded h-10 w-10"/>
                    </figure>
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $room->name }}</p>
                    @if($room->user_id==auth()->user()->id)
                        <button type="button"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded flex items-center justify-center">
                            Change Name
                        </button>
                        <button type="button"
                                wire:click="$dispatch('open-modal', 'delete-room')"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded flex items-center justify-center">
                            Delete Room
                        </button>
                        <x-modal name="delete-room">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                                        Delete Room
                                    </h2>
                                    <button x-on:click="$dispatch('close-modal', 'delete-room')"
                                            class="text-gray-500 dark:text-gray-400">
                                        <x-icons.x class="h-6 w-6"/>
                                    </button>
                                </div>
                                <livewire:rooms.delete :room_id="$room->id"/>
                            </div>
                        </x-modal>
                    @endif
                @else
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">
                        Please select room.
                    </p>
                @endif
            </div>
        </div>
        <div wire:poll.2000ms
             class="mt-4 h-[calc(100vh-210px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 flex flex-col-reverse">
            <div class="flex flex-col gap-4">
                @forelse ($chats as $chat)
                    @php
                        $isCurrentUser = $chat->user->id === auth()->user()->id;
                        // for temporary
                    @endphp
                    <div @class([
                        'flex items-center space-x-2',
                        'justify-end' => $isCurrentUser,
                    ])>
                        @if (!$isCurrentUser)
                            <figure class="flex flex-shrink-0 self-start">
                                <img src="{{ $chat->user->profile }}" alt="{{ $chat->user->name }}"
                                     class="h-8 w-8 object-cover rounded-full">
                            </figure>
                        @endif
                        <div class="flex items-center justify-center space-x-2">
                            <div class="block">
                                <div class="w-auto rounded-xl px-2 pb-2">
                                    <div @class([
                                        'font-medium text-gray-800 dark:text-gray-100',
                                        'text-right' => $isCurrentUser,
                                    ])>
                                        <small class="text-sm sm:text-md">
                                            {{ $isCurrentUser ? 'Me' : $chat->user->name }}
                                        </small>
                                    </div>
                                    <div @class([
                                        'text-xs sm:text-sm bg-slate-100 dark:bg-gray-700 dark:text-gray-400 p-2',
                                        'rounded-tl-3xl rounded-bl-3xl rounded-br-xl' => $isCurrentUser,
                                        'rounded-tr-3xl rounded-br-3xl rounded-bl-xl' => !$isCurrentUser,
                                    ])>
                                        <p>
                                            {{ $chat->message }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($isCurrentUser)
                            <figure class="flex flex-shrink-0 self-start">
                                <img src="{{ $chat->user->profile }}" alt="{{ $chat->user->name }}"
                                     class="h-8 w-8 object-cover rounded-full">
                            </figure>
                        @endif
                    </div>
                @empty
                    @if ($room !== null)
                        <div class="bg-white dark:bg-gray-700 shadow-md rounded-lg py-4">
                            <div class="flex items-center gap-3 px-4">
                                <p class="text-gray-500 dark:text-gray-400">No chats found</p>
                            </div>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>
        @if ($room !== null)
            <div class="mt-4">
                <form wire:submit="sendMessage">
                    <div class="flex items-center gap-3">
                        <input type="text" wire:model="message" class="w-full rounded-lg border-gray-300"/>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center justify-center"
                                :disabled="$wire.message==='' || $wire.isLoading">
                            <span wire:loading.remove wire:target="sendMessage" class="flex items-center">Send</span>
                            <span wire:loading wire:target="sendMessage" class="send-loader-spinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

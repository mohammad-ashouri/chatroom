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
                                wire:click="$dispatch('open-modal', 'rename-room')"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded flex items-center justify-center">
                            Change Name
                        </button>
                        <x-modal name="rename-room">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                                        Rename Room
                                    </h2>
                                    <button x-on:click="$dispatch('close-modal', 'rename-room')"
                                            class="text-gray-500 dark:text-gray-400">
                                        <x-icons.x class="h-6 w-6"/>
                                    </button>
                                </div>
                                <livewire:rooms.rename :room_id="$room->id"/>
                            </div>
                        </x-modal>
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

        <x-modal-info name="delete-chat">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                        Do you want to delete this message?
                    </h2>
                    <button x-on:click="$dispatch('close-modal', 'delete-chat')"
                            class="text-gray-500 dark:text-gray-400">
                        <x-icons.x class="h-6 w-6"/>
                    </button>
                </div>
                <livewire:chats.delete/>
            </div>
        </x-modal-info>
        <div
            class="mt-4 h-[calc(100vh-210px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 flex flex-col-reverse">
            <div class="flex flex-col gap-4">
                @forelse ($chats as $chat)
                    @php($isCurrentUser = $chat->user->id === auth()->user()->id)
                    <div
                        wire:key="{{$chat->id}}"
                        x-init="
                                Echo.private(`update-room-chats.{{ $room->id }}`).listen('MessageSent1',(e)=>{ $wire.$refresh() });
                            "
                        @class([
                        'flex items-center space-x-2',
                        'justify-end' => $isCurrentUser,
                    ])>
                        @if (!$isCurrentUser)
                            <figure class="flex flex-shrink-0 self-start">
                                <img src="{{ $chat->user->profile }}" alt="{{ $chat->user->name }}"
                                     class="h-8 w-8 object-cover rounded-full">
                            </figure>
                        @endif
                        <div wire:key="{{ $chat->user_id . $chat->id }}"
                             class="flex items-center justify-center space-x-2">
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
                                    <div class="relative group gap-2 flex items-center">
                                        @if($chat->user_id==auth()->user()->id)
                                            <button
                                                wire:click="dispatchChatId({{$chat->id}})"
                                                class="opacity-0 group-hover:opacity-100 transition-all duration-300 bg-red-500 text-white p-2 rounded-lg order-first">
                                                Delete
                                            </button>
                                            <small class="dark:text-gray-400 text-sm sm:text-md">
                                                {{ $chat->created_at->format('H:i') }}
                                            </small>
                                        @endif
                                        <div @class([
                                            'text-xs sm:text-sm bg-slate-100 dark:bg-gray-700 dark:text-gray-400 p-2',
                                            'rounded-tl-3xl rounded-bl-3xl rounded-br-xl' => $isCurrentUser,
                                            'rounded-tr-3xl rounded-br-3xl rounded-bl-xl' => !$isCurrentUser,
                                        ])>
                                            <p>
                                                {{ $chat->message }}
                                            </p>
                                        </div>
                                        @if($chat->user_id!=auth()->user()->id)
                                            <small class="dark:text-gray-400 text-sm sm:text-md">
                                                {{ $chat->created_at->format('H:i') }}
                                            </small>
                                        @endif
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

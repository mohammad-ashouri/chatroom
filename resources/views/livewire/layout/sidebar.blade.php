<aside class="bg-white dark:bg-gray-800 w-1/4">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Chats</h1>
            <div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded"
                        x-on:click="$dispatch('open-modal', 'create-room')"
                        title="Create Room"
                >
                    Create Room
                </button>
                <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-2 rounded"
                        x-on:click="$dispatch('open-modal', 'create-private-chat')"
                        title="Private Chat"
                >
                    Private Chat
                </button>
            </div>
            <x-modal name="create-room">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                            Create Room
                        </h2>
                        <button x-on:click="$dispatch('close-modal', 'create-room')"
                                x-on:room-created.window="$dispatch('close-modal', 'create-room')"
                                class="text-gray-500 dark:text-gray-400">
                            <x-icons.x class="h-6 w-6"/>
                        </button>
                    </div>

                    <livewire:rooms.create/>
                </div>
            </x-modal>
            <x-modal name="create-private-chat">
                <div class="p-4 h-64">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                            Private Chat
                        </h2>
                        <button x-on:click="$dispatch('close-modal', 'create-private-chat')"
                                x-on:private-chat-created.window="$dispatch('close-modal', 'create-private-chat')"
                                class="text-gray-500 dark:text-gray-400">
                            <x-icons.x class="h-6 w-6"/>
                        </button>
                    </div>
                    <div
                        x-data
                        x-init="
                            const tomSelectInstance = new TomSelect($refs.selectUser, {
                            })
                        ">
                        <select x-ref="selectUser"
                                class="bg-gray-50 mt-3 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" selected disabled>Select or search user...</option>
                            @foreach($allUsers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 mt-4">
                    <x-primary-button>{{ __('Start Chat') }}</x-primary-button>
                </div>
            </x-modal>
        </div>
        <div
            class="mt-4 h-[calc(100vh-155px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <div class="flex flex-col gap-4">
                @forelse ($rooms as $room)
                    <div wire:key="{{$room->id}}"
                         class="bg-white dark:bg-gray-700 shadow-md rounded-lg py-4 cursor-pointer"
                         x-on:click="$dispatch('room-selected', { id: {{ $room->id }} })"
                    >
                        <div class="group flex items-center gap-3 px-4">
                            <figure
                                class="rounded h-10 w-10 flex-shrink-0 transition-opacity group-hover:opacity-90 {{ $room->user->profile }}">
                                <img src="{{ $room->user->profile }}" alt="{{ $room->user->name }}"
                                     class="rounded h-10 w-10"/>
                            </figure>

                            <div class="overflow-hidden text-sm dark:text-gray-100">
                                <div class="flex items-center ">
                                    <x-heroicon-m-user-group class="w-4 h-4 text-gray-500"/>
                                    <p class="truncate font-medium ml-2" title="{{ $room->name }}">
                                        {{ $room->name }}
                                    </p>
                                </div>
                                <p class="truncate text-gray-500 dark:text-gray-400">
                                    {{ !empty($room->chats()->latest()->first()->message) ? $room->chats()->latest()->first()->message : 'No Messages' }}
                                </p>
                                <div x-data="{
                                            lastChatTime: '{{ $room->chats()->latest()->first()?->created_at ?? null }}',
                                            diff: '',
                                            updateDiff() {
                                                if (this.lastChatTime) {
                                                    const parsedDate = dayjs(this.lastChatTime, 'YYYY-MM-DD HH:mm:ss');
                                                    this.diff = parsedDate.fromNow();
                                                } else {
                                                    this.diff = '';
                                                }
                                            },
                                            init() {
                                                this.updateDiff();
                                                setInterval(() => this.updateDiff(), 1000);
                                            }
                                        }" x-init="init">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-auto" x-text="diff"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-700 shadow-md rounded-lg py-4">
                        <div class="flex items-center gap-3 px-4">
                            <p class="text-gray-500 dark:text-gray-400">No rooms found</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <x-js-scripts :rooms="$this->rooms"/>
</aside>

<aside class="bg-white dark:bg-gray-800 w-1/4">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Chats</h1>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded"
                    x-on:click="$dispatch('open-modal', 'create-room')"
                    title="Create Room"
            >
                <x-icons.add class="h-6 w-6"/>
            </button>
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
    @script
    <script>
        let rooms =@json($this->rooms);
        if (rooms.length !== 0) {
            connectDynamicChannels(@json($this->rooms));
        }

        if ($wire.roomId !== null) {
            Echo.private(`update-room-chats.${$wire.roomId}`)
                .listen('.MessageSent1', (data) => {
                    console.log('MessageSent1:', data);
                    Livewire.dispatch('updateChats', data);
                });

            Echo.private(`removed-from-room.${$wire.roomId}`)
                .listen('.RemovedFromRoom', (data) => {
                    console.log('RemovedFromRoom:', data);
                    Livewire.dispatch('updateChats', data);
                });
        }

        function connectDynamicChannels(rooms) {
            rooms.forEach(room => {
                Echo.leave(`update-room-chats.${room.id}`);
                Echo.leave(`removed-from-room.${room.id}`);
            });

            rooms.forEach(room => {
                Echo.private(`update-room-chats.${room.id}`)
                    .listen('.MessageSent1', (data) => {
                        console.log('MessageSent1:', data);
                        Livewire.dispatch('updateChats', data);
                    });

                Echo.private(`removed-from-room.${room.id}`)
                    .listen('.RemovedFromRoom', (data) => {
                        console.log('RemovedFromRoom:', data);
                        Livewire.dispatch('updateChats', data);
                    });
            });
        }

        Echo.channel('room-created').listen('.RoomCreated', (newRoom) => {
            Echo.private(`update-room-chats.${newRoom.room.id}`)
                .listen('.MessageSent1', (data) => {
                    console.log('MessageSent1:', data);
                    Livewire.dispatch('updateChats', data);
                });

            Echo.private(`removed-from-room.${newRoom.room.id}`)
                .listen('.RemovedFromRoom', (data) => {
                    console.log('RemovedFromRoom:', data);
                    Livewire.dispatch('updateChats', data);
                });
        });


    </script>
    @endscript
</aside>

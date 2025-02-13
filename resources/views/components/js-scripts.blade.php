<div>
    @script
    <script>
        let rooms =@json($rooms);
        if (rooms.length !== 0) {
            connectDynamicChannels(@json($rooms));
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
</div>

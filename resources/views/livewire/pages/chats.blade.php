<div class="flex overflow-hidden">
    <livewire:layout.sidebar/>
    @if(request()->query('user'))
        <livewire:private-chats.index/>
    @elseif(request()->query('roomId'))
        <livewire:chats.index/>
    @endif
</div>

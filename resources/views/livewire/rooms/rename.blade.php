<div>
    <form wire:submit="renameRoom">
        <div class="mt-4">
            <x-input-label for="name" value="Name" />
            <x-text-input wire:model="name" id="name" wire:model="room_name" name="name" type="text" class="mt-1 block w-full" required
                          autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div class="mt-3 bg-red-300 dark:bg-red-300 shadow-md rounded-lg py-4">
            <div class="flex items-center gap-3 px-4">
                <p class="text-gray-800 dark:text-gray-800">Are you sure you want to rename this room?</p>
            </div>
        </div>
        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Rename') }}</x-primary-button>
            <x-action-message class="me-3" on="room-rename">
                {{ __('Room renamed.') }}
            </x-action-message>
        </div>
    </form>
</div>

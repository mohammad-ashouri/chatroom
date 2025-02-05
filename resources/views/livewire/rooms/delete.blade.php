<div>
    <form wire:submit.prevent="deleteRoom">
        <div class="mt-3 bg-red-300 dark:bg-red-300 shadow-md rounded-lg py-4">
            <div class="flex items-center gap-3 px-4">
                <p class="text-gray-800 dark:text-gray-800">Are you sure you want to delete this room?</p>
            </div>
        </div>
        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Delete') }}</x-primary-button>
            <x-action-message class="me-3" on="room-deleted">
                {{ __('Room deleted.') }}
            </x-action-message>
        </div>
    </form>
</div>

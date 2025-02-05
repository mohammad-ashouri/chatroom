<div>
    <form wire:submit="deleteChat">
        <div class="mt-3 bg-red-300 dark:bg-red-300 shadow-md rounded-lg py-4">
            <!-- Add checkbox -->
            <div class="flex items-center gap-2 px-4">
                <input type="checkbox" id="deleteForAll" wire:model="is_visible" class="form-checkbox h-3 w-3 text-indigo-600">
                <label for="deleteForAll" class="text-sm text-gray-700 dark:text-gray-700">Delete for all users</label>
            </div>
        </div>
        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Delete') }}</x-primary-button>
            <x-action-message class="me-3" on="chat-delete">
                {{ __('Chat delete.') }}
            </x-action-message>
        </div>
    </form>
</div>

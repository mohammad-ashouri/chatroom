<div
    x-data
    x-init="
        const tomSelectInstance = new TomSelect($refs.selectUser, {
    })
">
    <form wire:submit.prevent="startPrivateChat">
        <select x-ref="selectUser"
                wire:model="user"
                class="bg-gray-50 mt-3 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="" selected disabled>Select or search user...</option>
            @foreach($allUsers as $user)
                <option value="{{ $user->id }}">
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Start Chat') }}</x-primary-button>
        </div>
    </form>
</div>

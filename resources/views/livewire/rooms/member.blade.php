<div
    x-data
    x-init="
        const tomSelectInstance = new TomSelect($refs.selectMembers, {
            plugins: ['remove_button'],
            persist: false,
            create: false,
            onChange: function(values) {
                @this.set('members', values);
            }
        });
    "
    wire:ignore
>
    <form wire:submit.prevent="changeMembers">
        <div class="mt-3 bg-blue-300 dark:bg-blue-300 shadow-md rounded-lg py-4">
            <div class="flex items-center gap-3 px-4">
                <p class="text-gray-800 dark:text-gray-800">Owner: <span
                        class="font-bold">{{ auth()->user()->name }}</span></p>
            </div>
        </div>
        <select multiple x-ref="selectMembers"
                class="bg-gray-50 mt-3 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            @foreach($allMembers as $member)
                @continue($member->id===$this->room->user_id )
                <option value="{{ $member->id }}" @selected($this->room->users->contains('id', $member->id))>
                    {{ $member->name }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('members')"/>

        @if($this->room->user_id==auth()->user()->id)
            <div class="flex items-center gap-4 mt-4">
                <x-primary-button>{{ __('Change') }}</x-primary-button>
            </div>
        @endif
    </form>
</div>

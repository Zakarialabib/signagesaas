<div>
    <x-modal wire:model="openCreateDevice" id="create-device-modal" title="Create New Device" maxWidth="2xl">

        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Create a new device that will be used to display your content.
                </p>
            </div>
        </div>

        <div class="space-y-10 divide-y divide-gray-900/10 dark:divide-gray-700">
            <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                <div class="px-4 sm:px-0">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">Device Information
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                        Provide basic information about the device you're adding.
                    </p>
                </div>

                <form wire:submit="createDevice" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Device Name
                        </label>
                        <div class="mt-1">
                            <input type="text" id="name" wire:model="name"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Reception Display">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Device Type
                        </label>
                        <div class="mt-1">
                            <select id="type" wire:model="type"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach ($deviceTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Location (Optional)
                        </label>
                        <div class="mt-1">
                            <input type="text" id="location" wire:model="location"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Main Lobby">
                        </div>
                        @error('location')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Notes (Optional)
                        </label>
                        <div class="mt-1">
                            <textarea id="notes" wire:model="notes" rows="3"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Additional information about this device"></textarea>
                        </div>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-5">
                        <x-button type="button" color="secondary"
                            x-on:click="$dispatch('close-modal', 'create-device-modal')">
                            Cancel
                        </x-button>

                        <x-button type="submit" color="primary"
                            x-on:submit.prevent="loading = true; $wire.createDevice().then(() => { loading = false })">
                            Create Device
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </x-modal>
</div>

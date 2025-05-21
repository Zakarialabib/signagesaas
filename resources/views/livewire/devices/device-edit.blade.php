<div>
    <x-modal wire:model="editDevice" id="edit-device-modal" title="Edit Device" maxWidth="2xl">
        @if ($device)
            <div class="sm:flex sm:items-center mb-8">
                <div class="sm:flex-auto">
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                        Update information for device "{{ $device->name }}".
                    </p>
                </div>
            </div>

            <div class="space-y-10 divide-y divide-gray-900/10 dark:divide-gray-700">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                    <div class="px-4 sm:px-0">
                        <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">Device
                            Information
                        </h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                            Update the device information as needed.
                        </p>
                    </div>

                    <form wire:submit="updateDevice"
                        class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700 sm:rounded-xl md:col-span-2">
                        <div class="px-4 py-6 sm:p-8">
                            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                    <label for="name"
                                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Device Name
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" id="name" wire:model="name"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="type"
                                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Device Type
                                    </label>
                                    <div class="mt-2">
                                        <select id="type" wire:model="type"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @foreach ($deviceTypes as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('type')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-full">
                                    <label for="location"
                                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Location (Optional)
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" id="location" wire:model="location"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                    @error('location')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-full">
                                    <label for="notes"
                                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Notes (Optional)
                                    </label>
                                    <div class="mt-2">
                                        <textarea id="notes" wire:model="notes" rows="3"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    </div>
                                    @error('notes')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 dark:border-gray-700 px-4 py-4 sm:px-8">
                            <button type="button" wire:click="$dispatch('close-modal', {id: 'edit-device-modal'})"
                                class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                Cancel
                            </button>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="py-12 text-center">
                <p class="text-gray-500 dark:text-gray-400">No device selected or the device doesn't exist.</p>
                <button wire:click="$dispatch('close-modal', {id: 'edit-device-modal'})"
                    class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Close
                </button>
            </div>
        @endif
    </x-modal>
</div>

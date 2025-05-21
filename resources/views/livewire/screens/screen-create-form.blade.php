<div>    <x-modal wire:model="createScreenModal" id="create-screen-modal" title="Create New Screen" maxWidth="2xl" x-on:keydown.escape.window="$wire.closeModal()">

    <form wire:submit="save">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Screen Name</label>
                <div class="mt-1">
                    <input type="text" wire:model="name" id="name"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="device_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device</label>
                <div class="mt-1">
                    <select wire:model.live="device_id" id="device_id"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                        <option value="">Select Device</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('device_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <div class="mt-1">
                    <textarea wire:model="description" id="description" rows="3"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                </div>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <div class="mt-1">
                    <select wire:model="status" id="status"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="orientation" class="block text-sm font-medium text-gray-700">Orientation</label>
                <div class="mt-1">
                    <select wire:model.live="orientation" id="orientation"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @foreach ($orientations as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @error('orientation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="resolution" class="block text-sm font-medium text-gray-700">Resolution</label>
                <div class="mt-1">
                    <select wire:model="resolution" id="resolution"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @foreach ($resolutions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @error('resolution')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Location Details</h3>
            </div>

            <div class="sm:col-span-3">
                <label for="locationName" class="block text-sm font-medium text-gray-700">Location Name</label>
                <div class="mt-1">
                    <input type="text" wire:model="locationName" id="locationName"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="e.g. Main Office">
                </div>
                @error('locationName')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="locationAddress" class="block text-sm font-medium text-gray-700">Location Address</label>
                <div class="mt-1">
                    <input type="text" wire:model="locationAddress" id="locationAddress"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="e.g. 123 Main St">
                </div>
                @error('locationAddress')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="locationZone" class="block text-sm font-medium text-gray-700">Zone</label>
                <div class="mt-1">
                    <input type="text" wire:model="locationZone" id="locationZone"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="e.g. Reception, Cafeteria">
                </div>
                @error('locationZone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="locationFloor" class="block text-sm font-medium text-gray-700">Floor</label>
                <div class="mt-1">
                    <input type="text" wire:model="locationFloor" id="locationFloor"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="e.g. 1, Ground Floor">
                </div>
                @error('locationFloor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <x-button color="secondary" class="mr-3" x-on:click="$dispatch('close-modal', 'create-screen-modal')">
                Cancel
            </x-button>
            <x-button type="submit" color="primary">
                Create Screen
            </x-button>
        </div>
    </form>
    </x-modal>
</div>

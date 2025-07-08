<div><div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Zones</h2>
        <button
            wire:click="create"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
        >
            Create Zone
        </button>
    </div>

    <!-- Zone Form -->
    <form wire:submit.prevent="{{ $editing ? 'update' : 'store' }}" class="space-y-4 bg-white p-6 rounded-lg shadow">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Place Name</label>
            <input type="text" id="name" wire:model.defer="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" wire:model.defer="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Place Type</label>
            <input type="text" id="type" wire:model.defer="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., WALL_MOUNT, KIOSK, GENERAL_AREA">
            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="x" class="block text-sm font-medium text-gray-700">X Coordinate (on map)</label>
                <input type="number" step="any" id="x" wire:model.defer="x" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('x') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="y" class="block text-sm font-medium text-gray-700">Y Coordinate (on map)</label>
                <input type="number" step="any" id="y" wire:model.defer="y" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('y') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="width" class="block text-sm font-medium text-gray-700">Width (map/physical)</label>
                <input type="number" step="any" id="width" wire:model.defer="width" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('width') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="height" class="block text-sm font-medium text-gray-700">Height (map/physical)</label>
                <input type="number" step="any" id="height" wire:model.defer="height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('height') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label for="style_data_json" class="block text-sm font-medium text-gray-700">Style Data (JSON for map representation)</label>
            <textarea id="style_data_json" wire:model.defer="style_data_json" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm" placeholder='{ "color": "#FF0000", "icon": "pin" }'></textarea>
            @error('style_data_json') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="metadata_json" class="block text-sm font-medium text-gray-700">Metadata (JSON for additional place details)</label>
            <textarea id="metadata_json" wire:model.defer="metadata_json" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm" placeholder='{ "floor": "1", "gps": "lat,long" }'></textarea>
            @error('metadata_json') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <button type="button" wire:click="create" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                {{ $editing ? 'Update Place Zone' : 'Create Place Zone' }}
            </button>
        </div>
    </form>

    <!-- Zones Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coords (X,Y)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($zones as $zone)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $zone->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $zone->type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Str::limit($zone->description, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $zone->x !== null && $zone->y !== null ? $zone->x . ', ' . $zone->y : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="edit({{ $zone->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $zone->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this Place Zone? Associated screens will be unassigned from this zone.')">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        No Place Zones found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $zones->links() }}
    </div>
</div>
</div>
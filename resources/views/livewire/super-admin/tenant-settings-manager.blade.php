<div>
    <div class="p-6">
        <h2 class="text-2xl font-semibold mb-4">Tenant Settings Manager</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Tenant List -->
            <div class="w-full md:w-1/3 bg-white shadow-md rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-3">Select Tenant</h3>
                <input type="text" wire:model.live="search" placeholder="Search tenants..."
                    class="form-input rounded-md shadow-sm mt-1 block w-full mb-4">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th wire:click="sortBy('name')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Name
                                    @if ($sortField === 'name')
                                        <span class="ml-1">{!! $sortDirection === 'asc' ? '&uarr;' : '&darr;' !!}</span>
                                    @endif
                                </th>
                                <th wire:click="sortBy('domain')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Domain
                                    @if ($sortField === 'domain')
                                        <span class="ml-1">{!! $sortDirection === 'asc' ? '&uarr;' : '&darr;' !!}</span>
                                    @endif
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($tenants as $tenantItem)
                                <tr class="{{ $tenantId === $tenantItem->id ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $tenantItem->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $tenantItem->domain }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button wire:click="selectTenant('{{ $tenantItem->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No
                                        tenants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $tenants->links() }}
                </div>
            </div>

            <!-- Tenant Settings Form -->
            <div class="w-full md:w-2/3 bg-white shadow-md rounded-lg p-4">
                @if ($tenant)
                    <h3 class="text-xl font-semibold mb-3">Settings for {{ $tenant->name }}</h3>
                    <form wire:submit.prevent="saveSettings">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="siteName" class="block text-sm font-medium text-gray-700">Site Name</label>
                                <input type="text" id="siteName" wire:model="settings.siteName"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full">
                                @error('settings.siteName')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="contactEmail" class="block text-sm font-medium text-gray-700">Contact
                                    Email</label>
                                <input type="email" id="contactEmail" wire:model="settings.contactEmail"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full">
                                @error('settings.contactEmail')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                <select id="timezone" wire:model="settings.timezone"
                                    class="form-select rounded-md shadow-sm mt-1 block w-full">
                                    @foreach ($this->getTimezones() as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('settings.timezone')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="dateFormat" class="block text-sm font-medium text-gray-700">Date
                                    Format</label>
                                <select id="dateFormat" wire:model="settings.dateFormat"
                                    class="form-select rounded-md shadow-sm mt-1 block w-full">
                                    @foreach ($this->getDateFormats() as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('settings.dateFormat')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="timeFormat" class="block text-sm font-medium text-gray-700">Time
                                    Format</label>
                                <select id="timeFormat" wire:model="settings.timeFormat"
                                    class="form-select rounded-md shadow-sm mt-1 block w-full">
                                    @foreach ($this->getTimeFormats() as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('settings.timeFormat')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Settings
                            </button>
                            <button type="button" wire:click="resetTenantSelection"
                                class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-gray-600">Please select a tenant from the list to manage its settings.</p>
                @endif
            </div>
        </div>
    </div>
</div>

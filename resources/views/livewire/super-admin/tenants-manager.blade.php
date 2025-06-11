<div>
    <!-- Page Header -->
    <div class="sm:flex sm:items-center mb-8">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tenants</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Manage all tenants in your SignageSaaS platform.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex">
            <button wire:click="createTenant" 
                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Tenant
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm ring-1 ring-gray-300 dark:ring-gray-700 mb-6 p-4">
        <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-4 sm:gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <div class="mt-1">
                    <input type="text" wire:model.live.debounce.300ms="search" id="search" 
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 shadow-sm focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                </div>
            </div>
            <div>
                <label for="planFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan</label>
                <div class="mt-1">
                    <select wire:model.live="planFilter" id="planFilter" 
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 shadow-sm focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        <option value="">All Plans</option>
                        @foreach($plans as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <div class="mt-1">
                    <select wire:model.live="statusFilter" id="statusFilter" 
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 shadow-sm focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        <option value="">All Statuses</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-end">
                <button wire:click="resetFilters" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tenants Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 dark:ring-opacity-20 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">
                                    <button wire:click="sortBy('name')" class="group inline-flex items-center">
                                        Name
                                        <span class="ml-2 flex-none rounded text-gray-400 dark:text-gray-500">
                                            @if($sortField === 'name')
                                                @if($sortDirection === 'asc')
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @else
                                                <svg class="h-5 w-5 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <button wire:click="sortBy('email')" class="group inline-flex items-center">
                                        Email
                                        <span class="ml-2 flex-none rounded text-gray-400 dark:text-gray-500">
                                            @if($sortField === 'email')
                                                @if($sortDirection === 'asc')
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @else
                                                <svg class="h-5 w-5 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Domain
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <button wire:click="sortBy('created_at')" class="group inline-flex items-center">
                                        Created
                                        <span class="ml-2 flex-none rounded text-gray-400 dark:text-gray-500">
                                            @if($sortField === 'created_at')
                                                @if($sortDirection === 'asc')
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @else
                                                <svg class="h-5 w-5 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <button wire:click="sortBy('users_count')" class="group inline-flex items-center">
                                        Users
                                        <span class="ml-2 flex-none rounded text-gray-400 dark:text-gray-500">
                                            @if($sortField === 'users_count')
                                                @if($sortDirection === 'asc')
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @else
                                                <svg class="h-5 w-5 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <button wire:click="sortBy('devices_count')" class="group inline-flex items-center">
                                        Devices
                                        <span class="ml-2 flex-none rounded text-gray-400 dark:text-gray-500">
                                            @if($sortField === 'devices_count')
                                                @if($sortDirection === 'asc')
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @else
                                                <svg class="h-5 w-5 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <button wire:click="sortBy('screens_count')" class="group inline-flex items-center">
                                        Screens
                                        <span class="ml-2 flex-none rounded text-gray-400 dark:text-gray-500">
                                            @if($sortField === 'screens_count')
                                                @if($sortDirection === 'asc')
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @else
                                                <svg class="h-5 w-5 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Status
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Health
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($tenants as $tenant)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">
                                        {{ $tenant->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $tenant->email }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if($tenant->domains->isNotEmpty())
                                            {{ $tenant->domains->first()->domain }}
                                        @else
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                                No domain
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $tenant->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20">
                                            {{ $tenant->users_count }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                            {{ $tenant->devices_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-600/20 dark:bg-purple-500/10 dark:text-purple-400 dark:ring-purple-500/20">
                                            {{ $tenant->screens_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @php
                                            $status = $tenant->data['status'] ?? 'active';
                                            $statusColors = [
                                                'active' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20',
                                                'inactive' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20',
                                                'suspended' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20',
                                                'trial' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20'
                                            ];
                                        @endphp
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusColors[$status] ?? $statusColors['active'] }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zM12 13a1 1 0 110-2 1 1 0 010 2zM12 20a1 1 0 110-2 1 1 0 010 2z"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                    <button wire:click="updateTenantStatus('{{ $tenant->id }}', 'active')" @click="open = false" class="block w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        Set Active
                                                    </button>
                                                    <button wire:click="updateTenantStatus('{{ $tenant->id }}', 'inactive')" @click="open = false" class="block w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        Set Inactive
                                                    </button>
                                                    <button wire:click="updateTenantStatus('{{ $tenant->id }}', 'suspended')" @click="open = false" class="block w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                        Suspend
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @php
                                            $health = $this->getTenantHealthStatus($tenant->id);
                                            $healthColors = [
                                                'excellent' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20',
                                                'good' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20',
                                                'warning' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20',
                                                'critical' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $healthColors[$health] ?? $healthColors['good'] }}">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                @if($health === 'excellent')
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                @elseif($health === 'good')
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                @elseif($health === 'warning')
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                            {{ ucfirst($health) }}
                                        </span>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end items-center space-x-2">
                                            <button wire:click="viewTenantDetails('{{ $tenant->id }}')" 
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 rounded-md p-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </button>
                                            <button wire:click="manageSubscription('{{ $tenant->id }}')" 
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 rounded-md p-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                                </svg>
                                            </button>
                                            <a href="{{ route('superadmin.tenant.health', $tenant->id) }}" 
                                                class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 rounded-md p-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                </svg>
                                            </a>
                                            <button wire:click="confirmImpersonate('{{ $tenant->id }}')" 
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 rounded-md p-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </button>
                                            <button wire:click="editTenant('{{ $tenant->id }}')" 
                                                class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 rounded-md p-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </button>
                                            <button wire:click="confirmDelete('{{ $tenant->id }}')" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 rounded-md p-1 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-3 py-8 text-sm text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                            </svg>
                                            <p>No tenants found matching your criteria.</p>
                                            <button 
                                                wire:click="resetFilters" 
                                                class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                            >
                                                <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                                Reset Filters
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tenants->links() }}
    </div>

    <!-- Create/Edit Tenant Modal -->
    <x-modal wire:model="showTenantModal">
        <x-slot name="title">
            <div class="flex items-center">
                <div class="shrink-0 bg-indigo-100 dark:bg-indigo-900/30 rounded-md p-2 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        {{ $editingTenant ? 'Edit Tenant' : 'Create New Tenant' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $editingTenant ? 'Modify tenant information and domain settings.' : 'Set up a new tenant with their initial configuration.' }}
                    </p>
                </div>
            </div>
        </x-slot>

        <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 sm:rounded-xl mt-4">
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-6 gap-x-6">
                    <div class="sm:col-span-6">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Tenant Name
                        </label>
                        <div class="mt-2">
                            <input type="text" wire:model="name" id="name" 
                                class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6
                                text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-indigo-600
                                @error('name') ring-red-300 dark:ring-red-700 focus:ring-red-500 @enderror">
                            @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Primary Email
                        </label>
                        <div class="mt-2">
                            <input type="email" wire:model="email" id="email" 
                                class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6
                                text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-indigo-600
                                @error('email') ring-red-300 dark:ring-red-700 focus:ring-red-500 @enderror">
                            @error('email') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This email will be used for system notifications and admin communication.</p>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="domain" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Domain
                        </label>
                        <div class="mt-2">
                            <input type="text" wire:model="domain" id="domain" 
                                placeholder="tenant.signagesaas.com"
                                class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6
                                text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-indigo-600
                                @error('domain') ring-red-300 dark:ring-red-700 focus:ring-red-500 @enderror">
                            @error('domain') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tenant will access the application through this domain.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="plan_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Subscription Plan
                        </label>
                        <div class="mt-2">
                            <select wire:model="plan_id" id="plan_id" 
                                class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6
                                text-gray-900 dark:text-gray-100 dark:bg-gray-700 ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-indigo-600
                                @error('plan_id') ring-red-300 dark:ring-red-700 focus:ring-red-500 @enderror">
                                <option value="">Select a plan</option>
                                @foreach($plans as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('plan_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-200 dark:border-gray-700 px-4 py-4 sm:px-8">
                <button type="button" wire:click="$set('showTenantModal', false)" 
                    class="text-sm font-semibold text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300">
                    Cancel
                </button>
                <button type="button" wire:click="saveTenant" 
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    {{ $editingTenant ? 'Update Tenant' : 'Create Tenant' }}
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Tenant Details Modal -->
    <x-modal wire:model="showDetailsModal" max-width="4xl">
        <x-slot name="title">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Tenant Details
            </div>
        </x-slot>

        @if($selectedTenant)
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">General Information</h3>
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedTenant->name }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedTenant->email }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedTenant->created_at->format('M d, Y H:i') }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Users</dt>
                                <dd class="text-sm">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        {{ $selectedTenant->users_count }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Domains</h3>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($selectedTenant->domains as $domain)
                                <li class="py-3 flex items-center text-sm text-gray-900 dark:text-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $domain->domain }}
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500 dark:text-gray-400 italic">No domains configured</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end">
                <button type="button" wire:click="$set('showDetailsModal', false)" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Close
                </button>
            </div>
        </x-slot>
    </x-modal>

    <!-- Impersonate Confirmation Modal -->
    <x-modal wire:model="showImpersonateConfirmation">
        <x-slot name="title">
            <div class="flex items-center">
                <div class="shrink-0 bg-amber-100 dark:bg-amber-900/30 rounded-md p-2 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Impersonate Tenant
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        You will access the tenant's account with their permissions.
                    </p>
                </div>
            </div>
        </x-slot>

        <div class="mt-4">
            <div class="rounded-md bg-amber-50 dark:bg-amber-900/20 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">Important Information</h3>
                        <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                            <ul class="list-disc space-y-1 pl-5">
                                <li>You will be logged in as an admin user in tenant <span class="font-semibold">{{ $selectedTenant->name ?? '' }}</span>'s account.</li>
                                <li>Any actions you take will be performed as if you were the tenant's admin user.</li>
                                <li>Data created or modified will belong to the tenant, not to the system admin.</li>
                                <li>To stop impersonation, use the banner that will appear at the top of the page.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @if($selectedTenant?->domains?->isEmpty())
                <div class="mt-4 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Missing Domain</h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <p>This tenant has no domains configured. You must add a domain before impersonating.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-6 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">
            <button type="button" wire:click="$set('showImpersonateConfirmation', false)" 
                class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button type="button" wire:click="impersonateTenant" 
                @if($selectedTenant?->domains?->isEmpty()) disabled @endif
                class="mt-3 sm:mt-0 inline-flex justify-center items-center rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 
                      @if($selectedTenant?->domains?->isEmpty()) opacity-50 cursor-not-allowed @endif">
                <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Impersonate Tenant
            </button>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="showDeleteModal">
        <x-slot name="title">
            <div class="flex items-center text-red-600 dark:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Tenant
            </div>
        </x-slot>

        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/30 rounded-lg p-4 mb-4">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Warning</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>Are you sure you want to delete this tenant? This action cannot be undone.</p>
                        <p class="mt-2 font-semibold">All tenant data will be permanently removed, including:</p>
                        <ul class="mt-1 list-disc list-inside">
                            <li>User accounts</li>
                            <li>Content and media</li>
                            <li>Screens and devices</li>
                            <li>Settings and configurations</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="$set('showDeleteModal', false)" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <button type="button" wire:click="deleteTenant" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Delete
                </button>
            </div>
        </x-slot>
    </x-modal>
</div>

<div>
    <!-- Page Header -->
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Audit Logs</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                View all system activity across all tenants.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button wire:click="exportLogs" 
                class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Export Logs
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <div class="w-full md:w-1/4">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                    <div class="mt-1">
                        <input type="text" wire:model.live.debounce.300ms="search" id="search" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md"
                            placeholder="Search logs...">
                    </div>
                </div>
                
                <div class="w-full md:w-1/4">
                    <label for="tenantFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant</label>
                    <div class="mt-1">
                        <select wire:model.live="tenantFilter" id="tenantFilter" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                            <option value="">All Tenants</option>
                            @foreach($tenants as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="w-full md:w-1/4">
                    <label for="actionFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Action</label>
                    <div class="mt-1">
                        <select wire:model.live="actionFilter" id="actionFilter" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                            <option value="">All Actions</option>
                            @foreach($actionOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="w-full md:w-1/4">
                    <label for="entityTypeFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Entity Type</label>
                    <div class="mt-1">
                        <select wire:model.live="entityTypeFilter" id="entityTypeFilter" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                            <option value="">All Entity Types</option>
                            @foreach($entityTypeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <div class="w-full md:w-1/4">
                    <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                    <div class="mt-1">
                        <input type="date" wire:model.live="startDate" id="startDate" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                    </div>
                </div>
                
                <div class="w-full md:w-1/4">
                    <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                    <div class="mt-1">
                        <input type="date" wire:model.live="endDate" id="endDate" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                    </div>
                </div>
                
                <div class="w-full md:w-1/2 flex items-end space-x-2">
                    <button wire:click="setDateRange('today')" 
                        class="px-2 py-1 text-xs rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Today
                    </button>
                    <button wire:click="setDateRange('yesterday')" 
                        class="px-2 py-1 text-xs rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Yesterday
                    </button>
                    <button wire:click="setDateRange('week')" 
                        class="px-2 py-1 text-xs rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Last 7 Days
                    </button>
                    <button wire:click="setDateRange('month')" 
                        class="px-2 py-1 text-xs rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Last 30 Days
                    </button>
                    <button wire:click="resetFilters" 
                        class="ml-4 px-3 py-1 text-xs rounded-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">
                                    <button wire:click="sortBy('created_at')" class="group inline-flex">
                                        Timestamp
                                        <span class="ml-2 flex-none rounded text-gray-400">
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
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Tenant
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    User
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <button wire:click="sortBy('action')" class="group inline-flex">
                                        Action
                                        <span class="ml-2 flex-none rounded text-gray-400">
                                            @if($sortField === 'action')
                                                @if($sortDirection === 'asc')
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <button wire:click="sortBy('entity_type')" class="group inline-flex">
                                        Entity Type
                                        <span class="ml-2 flex-none rounded text-gray-400">
                                            @if($sortField === 'entity_type')
                                                @if($sortDirection === 'asc')
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @endif
                                        </span>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Description
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Details</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">
                                        {{ $log->created_at->format('M d, Y H:i:s') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->tenant->name ?? 'System' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->user->name ?? 'System' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($log->action === 'create') bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100
                                            @elseif($log->action === 'update') bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100
                                            @elseif($log->action === 'delete') bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100
                                            @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ ucfirst($log->entity_type) }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                        {{ $log->description }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <button
                                            x-data
                                            x-on:click="$dispatch('open-modal', 'log-details-{{ $log->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                        No audit logs found.
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
    <div class="mt-4">
        {{ $auditLogs->links() }}
    </div>

    <!-- Log Detail Modals -->
    @foreach($auditLogs as $log)
        <x-modal id="log-details-{{ $log->id }}">
            <x-slot name="title">
                Log Details - {{ ucfirst($log->action) }} {{ ucfirst($log->entity_type) }}
            </x-slot>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Timestamp</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $log->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Entity ID</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $log->entity_id }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">IP Address</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $log->ip_address }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">User Agent</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 truncate">{{ $log->user_agent ?? 'N/A' }}</p>
                    </div>
                </div>

                @if($log->old_values)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Old Values</h3>
                        <div class="mt-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <pre class="text-xs text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif

                @if($log->new_values)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">New Values</h3>
                        <div class="mt-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <pre class="text-xs text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif
            </div>
        </x-modal>
    @endforeach
</div> 
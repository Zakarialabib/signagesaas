<div>
    <div class="px-4 sm:px-6 lg:px-8" x-data="{
        autoRefresh: @entangle('autoRefresh'),
        refreshInterval: null,
        startAutoRefresh(interval) {
            this.stopAutoRefresh();
            this.refreshInterval = setInterval(() => {
                $wire.refreshData();
            }, interval);
        },
        stopAutoRefresh() {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
                this.refreshInterval = null;
            }
        }
    }"
        @start-auto-refresh.window="startAutoRefresh($event.detail.interval)"
        @stop-auto-refresh.window="stopAutoRefresh()">
        <!-- Header -->
        <div class="sm:flex sm:items-center justify-between mb-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Device Management</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Monitor and manage devices across all tenants from a centralized dashboard.
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <button wire:click="toggleAutoRefresh"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                    :class="{ 'bg-green-50 border-green-300 text-green-700 dark:bg-green-900 dark:border-green-600 dark:text-green-300': autoRefresh }">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span x-text="autoRefresh ? 'Auto Refresh ON' : 'Auto Refresh OFF'"></span>
                </button>
                <button wire:click="refreshData"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Devices -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-blue-100 dark:bg-blue-900 p-3">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Devices</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $metrics['total_devices'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Online Devices -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-green-100 dark:bg-green-900 p-3">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Online Devices
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $metrics['online_devices'] ?? 0 }}</div>
                                <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    of {{ $metrics['total_devices'] ?? 0 }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Offline Devices -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-red-100 dark:bg-red-900 p-3">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Offline Devices
                            </dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $metrics['offline_devices'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Average CPU Usage -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-purple-100 dark:bg-purple-900 p-3">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Avg CPU Usage</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $metrics['avg_cpu_usage'] ?? 0 }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Bulk Actions -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <!-- Search -->
                        <div class="relative">
                            <input wire:model.live="search" type="text" placeholder="Search devices or tenants..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <select wire:model.live="statusFilter"
                            class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="error">Error</option>
                        </select>

                        <!-- Device Type Filter -->
                        <select wire:model.live="deviceTypeFilter"
                            class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Types</option>
                            <option value="android">Android</option>
                            <option value="windows">Windows</option>
                            <option value="linux">Linux</option>
                        </select>
                    </div>

                    <!-- Bulk Actions -->
                    @if (count($selectedDevices) > 0)
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ count($selectedDevices) }} device(s) selected
                            </span>
                            <button wire:click="openBulkActionsModal"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Bulk Actions
                            </button>
                            <button wire:click="clearSelection"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Clear
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Device List by Tenant -->
        <div class="space-y-6">
            @forelse($filteredDevices as $tenant)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $tenant['tenant_name'] }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $tenant['tenant_domain'] }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $tenant['online_count'] }}/{{ $tenant['device_count'] }} Online
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <input type="checkbox"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Device</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Firmware</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Last Seen</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Performance</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($tenant['devices'] as $device)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox"
                                                wire:click="selectDevice('{{ $tenant['tenant_id'] }}', '{{ $device['id'] }}', '{{ $device['name'] }}', '{{ $tenant['tenant_name'] }}')"
                                                @if (isset($selectedDevices["{$tenant['tenant_id']}_{$device['id']}"])) checked @endif
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $device['name'] }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $device['location'] ?? 'No location' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($device['status'] === 'online') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($device['status'] === 'offline') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                                <span
                                                    class="w-2 h-2 mr-1 rounded-full
                                                @if ($device['status'] === 'online') bg-green-400
                                                @elseif($device['status'] === 'offline') bg-red-400
                                                @else bg-yellow-400 @endif"></span>
                                                {{ ucfirst($device['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            <div class="flex items-center">
                                                @if ($device['type'] === 'android')
                                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993.0001.5511-.4482.9997-.9993.9997m-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1521-.5676.416.416 0 00-.5676.1521l-2.0223 3.503C15.5902 8.2439 13.8533 7.8508 12 7.8508s-3.5902.3931-5.1367 1.0989L4.841 5.4467a.4161.4161 0 00-.5677-.1521.4157.4157 0 00-.1521.5676l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.761h24c-.3435-4.1021-2.6892-7.5743-6.1185-9.4396" />
                                                    </svg>
                                                @elseif($device['type'] === 'windows')
                                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M0 3.449L9.75 2.1v9.451H0m10.949-9.602L24 0v11.4H10.949M0 12.6h9.75v9.451L0 20.699M10.949 12.6H24V24l-13.051-1.351" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                                    </svg>
                                                @endif
                                                {{ ucfirst($device['type']) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $device['firmware_version'] ?? 'Unknown' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if ($device['last_seen_at'])
                                                {{ \Carbon\Carbon::parse($device['last_seen_at'])->diffForHumans() }}
                                            @else
                                                Never
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2 text-xs">
                                                <div class="flex items-center">
                                                    <span class="text-gray-500 dark:text-gray-400 mr-1">CPU:</span>
                                                    <div class="w-12 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full"
                                                            style="width: {{ $device['cpu_usage'] }}%"></div>
                                                    </div>
                                                    <span
                                                        class="ml-1 text-gray-500 dark:text-gray-400">{{ $device['cpu_usage'] }}%</span>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2 text-xs mt-1">
                                                <div class="flex items-center">
                                                    <span class="text-gray-500 dark:text-gray-400 mr-1">RAM:</span>
                                                    <div class="w-12 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-green-600 h-2 rounded-full"
                                                            style="width: {{ $device['memory_usage'] }}%"></div>
                                                    </div>
                                                    <span
                                                        class="ml-1 text-gray-500 dark:text-gray-400">{{ $device['memory_usage'] }}%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button
                                                wire:click="showDeviceDetails('{{ $tenant['tenant_id'] }}', '{{ $device['id'] }}')"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No devices found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No devices match your current filters.</p>
                </div>
            @endforelse
        </div>

        <!-- Bulk Actions Modal -->
        @if ($showBulkActionsModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Bulk Actions
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action</label>
                                    <select wire:model="bulkAction"
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">Select an action</option>
                                        <option value="reboot">Reboot Devices</option>
                                        <option value="firmware_update">Update Firmware</option>
                                        <option value="restart_service">Restart Service</option>
                                    </select>
                                </div>

                                @if ($bulkAction === 'firmware_update')
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Firmware
                                            Version</label>
                                        <input wire:model="firmwareVersion" type="text" placeholder="e.g., 2.1.0"
                                            class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                @endif

                                <div
                                    class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                                This action will be performed on {{ count($selectedDevices) }} selected
                                                device(s). This action cannot be undone.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="executeBulkAction" @if (empty($bulkAction)) disabled @endif
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Execute Action
                            </button>
                            <button wire:click="$set('showBulkActionsModal', false)"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Results Modal -->
        @if ($showResultsModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Bulk Action
                                Results</h3>

                            <div class="space-y-3">
                                @foreach ($bulkActionResults as $result)
                                    <div
                                        class="flex items-center justify-between p-3 rounded-lg {{ $result['success'] ? 'bg-green-50 dark:bg-green-900' : 'bg-red-50 dark:bg-red-900' }}">
                                        <div class="flex items-center">
                                            @if ($result['success'])
                                                <svg class="h-5 w-5 text-green-400 mr-3" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-red-400 mr-3" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            @endif
                                            <div>
                                                <p
                                                    class="text-sm font-medium {{ $result['success'] ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                                    {{ $result['device_name'] }} ({{ $result['tenant_name'] }})
                                                </p>
                                                <p
                                                    class="text-xs {{ $result['success'] ? 'text-green-600 dark:text-green-300' : 'text-red-600 dark:text-red-300' }}">
                                                    {{ $result['message'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="$set('showResultsModal', false)"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Device Details Modal -->
        @if ($showDeviceDetailsModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Device Details
                                </h3>
                                <button wire:click="$set('showDeviceDetailsModal', false)"
                                    class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            @if (!empty($selectedDevice))
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Basic Information -->
                                    <div class="space-y-4">
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Basic Information
                                        </h4>
                                        <dl class="space-y-2">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Device
                                                    Name</dt>
                                                <dd class="text-sm text-gray-900 dark:text-white">
                                                    {{ $selectedDevice['name'] ?? 'N/A' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant
                                                </dt>
                                                <dd class="text-sm text-gray-900 dark:text-white">
                                                    {{ $selectedDevice['tenant_name'] ?? 'N/A' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type
                                                </dt>
                                                <dd class="text-sm text-gray-900 dark:text-white">
                                                    {{ ucfirst($selectedDevice['type'] ?? 'N/A') }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status
                                                </dt>
                                                <dd>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if (($selectedDevice['status'] ?? '') === 'online') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif(($selectedDevice['status'] ?? '') === 'offline') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                                        {{ ucfirst($selectedDevice['status'] ?? 'Unknown') }}
                                                    </span>
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    Location</dt>
                                                <dd class="text-sm text-gray-900 dark:text-white">
                                                    {{ $selectedDevice['location'] ?? 'N/A' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    Firmware Version</dt>
                                                <dd class="text-sm text-gray-900 dark:text-white">
                                                    {{ $selectedDevice['firmware_version'] ?? 'N/A' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last
                                                    Seen</dt>
                                                <dd class="text-sm text-gray-900 dark:text-white">
                                                    @if (isset($selectedDevice['last_seen_at']))
                                                        {{ \Carbon\Carbon::parse($selectedDevice['last_seen_at'])->format('M j, Y g:i A') }}
                                                        <span
                                                            class="text-gray-500 dark:text-gray-400">({{ \Carbon\Carbon::parse($selectedDevice['last_seen_at'])->diffForHumans() }})</span>
                                                    @else
                                                        Never
                                                    @endif
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <!-- Performance Metrics -->
                                    <div class="space-y-4">
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Performance
                                            Metrics</h4>
                                        <div class="space-y-4">
                                            <!-- CPU Usage -->
                                            <div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-500 dark:text-gray-400">CPU Usage</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white">{{ $selectedDevice['cpu_usage'] ?? 0 }}%</span>
                                                </div>
                                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full"
                                                        style="width: {{ $selectedDevice['cpu_usage'] ?? 0 }}%"></div>
                                                </div>
                                            </div>

                                            <!-- Memory Usage -->
                                            <div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-500 dark:text-gray-400">Memory Usage</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white">{{ $selectedDevice['memory_usage'] ?? 0 }}%</span>
                                                </div>
                                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-green-600 h-2 rounded-full"
                                                        style="width: {{ $selectedDevice['memory_usage'] ?? 0 }}%">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Storage Usage -->
                                            <div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-500 dark:text-gray-400">Storage Usage</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white">{{ $selectedDevice['storage_usage'] ?? 0 }}%</span>
                                                </div>
                                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-purple-600 h-2 rounded-full"
                                                        style="width: {{ $selectedDevice['storage_usage'] ?? 0 }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="$set('showDeviceDetailsModal', false)"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

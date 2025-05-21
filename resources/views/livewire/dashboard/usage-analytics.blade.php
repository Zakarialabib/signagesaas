<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-(--breakpoint-xl) mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Usage Analytics</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Track device activity, content playtime, bandwidth usage, and user actions.
                </p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Date Range Filter -->
                <div class="w-full md:w-auto">
                    <label for="date-range" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date
                        Range</label>
                    <select id="date-range" wire:model.live="dateRange" wire:change="updateDateRange"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($dateRangeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Custom Date Range (shown when 'custom' is selected) -->
                @if ($dateRange === 'custom')
                    <div class="w-full md:w-auto flex flex-wrap gap-2">
                        <div>
                            <label for="start-date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start
                                Date</label>
                            <input type="date" id="start-date" wire:model="startDate"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="end-date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                            <input type="date" id="end-date" wire:model="endDate"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                @endif

                <!-- Device Filter -->
                <div class="w-full md:w-auto">
                    <label for="device-filter"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Device</label>
                    <select id="device-filter" wire:model.live="selectedDeviceId"
                        wire:change="setDeviceFilter($event.target.value)"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Devices</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }} ({{ $device->type }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div class="w-full md:w-auto">
                    <label for="user-filter"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                    <select id="user-filter" wire:model.live="selectedUserId"
                        wire:change="setUserFilter($event.target.value)"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Apply Filters Button -->
                <div class="w-full md:w-auto shrink-0 mt-4 md:mt-6">
                    <button wire:click="filterAnalytics"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Apply Filters
                    </button>
                    <button wire:click="clearFilters"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Device Usage Hours -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-blue-100 dark:bg-blue-900 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Device Usage</h2>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalDeviceUsageHours }}
                            hrs</p>
                        @if ($selectedDeviceId)
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ number_format($deviceUptime, 1) }}% uptime
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content Plays -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-green-100 dark:bg-green-900 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Content Plays</h2>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalContentPlays }}</p>
                    </div>
                </div>
            </div>

            <!-- Bandwidth Usage -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-purple-100 dark:bg-purple-900 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Bandwidth Usage</h2>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalBandwidthUsed }} MB
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Activity -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-red-100 dark:bg-red-900 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">User Activity</h2>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalUserActions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Device Usage Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Device Usage (Hours)</h3>

                <!-- Chart Placeholder -->
                <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded-md p-4 overflow-auto">
                    @if (!empty($deviceUsageChartData))
                        <ul>
                            @foreach ($deviceUsageChartData as $item)
                                <li>{{ $item['label'] }}: {{ $item['value'] }} hrs</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No device usage data for this period.</p>
                    @endif
                </div>

                <!-- Device Usage Table -->
                @if (!empty($deviceUsageData))
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Device</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Event</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Count</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Duration</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($deviceUsageData as $item)
                                    <tr>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $devices->firstWhere('id', $item->device_id)?->name ?? 'Unknown' }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($item->event_type) }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->event_count }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ floor($item->total_duration / 3600) }}h
                                            {{ floor(($item->total_duration % 3600) / 60) }}m
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-4 py-3 text-center text-gray-500 dark:text-gray-400">
                        No device usage data available for the selected filters.
                    </div>
                @endif
            </div>

            <!-- Content Plays Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Content Plays</h3>

                <!-- Chart Placeholder -->
                <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded-md p-4 overflow-auto">
                    @if (!empty($contentPlayChartData))
                        <ul>
                            @foreach ($contentPlayChartData as $item)
                                <li>{{ $item['label'] }}: {{ $item['value'] }} plays</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No content play data for this period.</p>
                    @endif
                </div>

                <!-- Content Plays Table -->
                @if (!empty($contentPlayData))
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Content</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Plays</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Duration</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($contentPlayData as $item)
                                    <tr>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            Content #{{ $item->content_id ?? 'Unknown' }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->play_count }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ floor($item->total_duration / 3600) }}h
                                            {{ floor(($item->total_duration % 3600) / 60) }}m
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-4 py-3 text-center text-gray-500 dark:text-gray-400">
                        No content play data available for the selected filters.
                    </div>
                @endif
            </div>

            <!-- Bandwidth Usage Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Daily Bandwidth Usage (MB)</h3>

                <!-- Chart Placeholder -->
                <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded-md p-4 overflow-auto">
                    @if (!empty($bandwidthChartData))
                        <ul>
                            @foreach ($bandwidthChartData as $item)
                                <li>{{ $item['label'] }}: {{ $item['value'] }} MB</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No bandwidth data for this period.</p>
                    @endif
                </div>

                <!-- Bandwidth Usage Summary -->
                @if (!empty($bandwidthData))
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Date</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Direction</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Bandwidth</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($bandwidthData->take(5) as $item)
                                    <tr>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->date }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($item->direction) }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ round($item->total_bytes / (1024 * 1024), 2) }} MB
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-4 py-3 text-center text-gray-500 dark:text-gray-400">
                        No bandwidth data available for the selected filters.
                    </div>
                @endif
            </div>

            <!-- User Activity Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">User Actions</h3>

                <!-- Chart Placeholder -->
                <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded-md p-4 overflow-auto">
                    @if (!empty($userActivityChartData))
                        <ul>
                            @foreach ($userActivityChartData as $item)
                                <li>{{ $item['label'] }}: {{ $item['value'] }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No user activity data for this period.</p>
                    @endif
                </div>

                <!-- User Activity Table -->
                @if (!empty($userActivityData))
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        User</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Action</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Count</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($userActivityData->take(5) as $item)
                                    <tr>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $users->firstWhere('id', $item->user_id)?->name ?? 'Unknown' }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ ucwords(str_replace('_', ' ', $item->action)) }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->action_count }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-4 py-3 text-center text-gray-500 dark:text-gray-400">
                        No user activity data available for the selected filters.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div>
    <div class="px-4 sm:px-6 lg:px-8" x-data="{ autoRefresh: @entangle('autoRefresh') }">
        <div class="sm:flex sm:items-center justify-between mb-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">System Overview</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Real-time monitoring and analytics for your SignageSaaS platform.
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <button wire:click="refreshMetrics" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
                <button wire:click="toggleAutoRefresh" 
                        :class="autoRefresh ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Auto Refresh
                </button>
            </div>
        </div>

        <!-- Real-Time Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Tenants -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-indigo-100 dark:bg-indigo-900 p-3">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Tenants</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $metrics['total_tenants'] ?? 0 }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold {{ ($metrics['tenant_growth_rate'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    @if(($metrics['tenant_growth_rate'] ?? 0) >= 0)
                                        <svg class="self-center flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg class="self-center flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                    <span class="sr-only">{{ ($metrics['tenant_growth_rate'] ?? 0) >= 0 ? 'Increased' : 'Decreased' }} by</span>
                                    {{ abs($metrics['tenant_growth_rate'] ?? 0) }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-green-100 dark:bg-green-900 p-3">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Subscriptions</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $metrics['total_subscriptions'] ?? 0 }}</div>
                                <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($metrics['monthly_revenue'] ?? 0, 0) }}/mo
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Active Devices -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-blue-100 dark:bg-blue-900 p-3">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Devices</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $metrics['active_devices'] ?? 0 }}</div>
                                <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    of {{ $metrics['total_devices'] ?? 0 }} ({{ $metrics['device_utilization'] ?? 0 }}%)
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- System Uptime -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="shrink-0 rounded-md bg-purple-100 dark:bg-purple-900 p-3">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">System Uptime</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $metrics['system_uptime'] ?? 0 }}%</div>
                                <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($metrics['api_requests_today'] ?? 0) }} API calls today
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach($systemHealth as $service => $health)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($health['status'] === 'healthy')
                                    <div class="h-3 w-3 bg-green-400 rounded-full"></div>
                                @elseif($health['status'] === 'warning')
                                    <div class="h-3 w-3 bg-yellow-400 rounded-full"></div>
                                @else
                                    <div class="h-3 w-3 bg-red-400 rounded-full"></div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $service }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $health['message'] }}</p>
                            </div>
                        </div>
                        @if(isset($health['response_time']))
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $health['response_time'] }}ms</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activity Feed -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Recent Tenant Activity</h2>
                    <a href="{{ route('superadmin.tenants') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                        View all tenants →
                    </a>
                </div>
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($recentActivity as $index => $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900 dark:text-white">
                                                    New tenant <span class="font-medium">{{ $activity['tenant_name'] }}</span> signed up
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    Domain: {{ $activity['tenant_domain'] }} • Plan: {{ $activity['plan_name'] }}
                                                </p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                <time datetime="{{ $activity['created_at'] }}">{{ $activity['created_at']->diffForHumans() }}</time>
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                        @if($activity['status'] === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                        @elseif($activity['status'] === 'trial') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                        @elseif($activity['status'] === 'suspended') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                        {{ ucfirst($activity['status']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent activity</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No new tenant signups in the last 24 hours.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">
                                    Tenant</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Plan</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            <tr>
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    Acme Inc</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Enterprise</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">July
                                    15, 2023</td>
                            </tr>
                            <tr>
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    Company X</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Pro
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">July
                                    12, 2023</td>
                            </tr>
                            <tr>
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    Digital Solutions</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Basic
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">July
                                    10, 2023</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">System Status</h2>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">API Health</div>
                        <div
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Operational
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">Database</div>
                        <div
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Operational
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">Storage</div>
                        <div
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Operational
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">CDN</div>
                        <div
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                            Degraded
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">Queue Workers</div>
                        <div
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Operational
                        </div>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">System Resources</h3>
                        <div class="space-y-2">
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-500 dark:text-gray-400">CPU Usage</span>
                                    <span class="text-gray-900 dark:text-white">42%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: 42%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-500 dark:text-gray-400">Memory Usage</span>
                                    <span class="text-gray-900 dark:text-white">68%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: 68%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-500 dark:text-gray-400">Storage Usage</span>
                                    <span class="text-gray-900 dark:text-white">35%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: 35%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="space-y-6">
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                    Health Monitor - {{ $tenant->name }}
                </h3>
                <p class="mt-2 max-w-4xl text-sm text-gray-500 dark:text-gray-400">
                    Real-time health monitoring and system diagnostics for tenant infrastructure.
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <button wire:click="refreshHealth" 
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
                <a href="{{ route('super-admin.tenants') }}" 
                    class="inline-flex items-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Tenants
                </a>
            </div>
        </div>
    </div>

    <!-- Overall Health Status -->
    <div class="rounded-lg bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">Overall Health Status</h4>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Last updated: {{ now()->format('M d, Y H:i:s') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    @php
                        $healthColors = [
                            'excellent' => 'text-green-600 dark:text-green-400',
                            'good' => 'text-blue-600 dark:text-blue-400',
                            'warning' => 'text-yellow-600 dark:text-yellow-400',
                            'critical' => 'text-red-600 dark:text-red-400'
                        ];
                        $healthBgColors = [
                            'excellent' => 'bg-green-50 dark:bg-green-900/20',
                            'good' => 'bg-blue-50 dark:bg-blue-900/20',
                            'warning' => 'bg-yellow-50 dark:bg-yellow-900/20',
                            'critical' => 'bg-red-50 dark:bg-red-900/20'
                        ];
                    @endphp
                    <div class="flex items-center space-x-2">
                        <div class="relative h-16 w-16">
                            <svg class="h-16 w-16 transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-gray-200 dark:text-gray-700" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                <path class="{{ $healthColors[$overallHealth] }}" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" 
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" 
                                    stroke-dasharray="{{ $tenant->data['health_score'] ?? 75 }}, 100">
                                </path>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-sm font-semibold {{ $healthColors[$overallHealth] }}">{{ round($tenant->data['health_score'] ?? 75) }}%</span>
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $healthBgColors[$overallHealth] }} {{ $healthColors[$overallHealth] }}">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    @if($overallHealth === 'excellent')
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    @elseif($overallHealth === 'good')
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    @elseif($overallHealth === 'warning')
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    @else
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    @endif
                                </svg>
                                {{ ucfirst($overallHealth) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Metrics Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($healthMetrics as $metricName => $metric)
            @php
                $statusColors = [
                    'healthy' => 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20',
                    'warning' => 'text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20',
                    'critical' => 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20'
                ];
                $iconColors = [
                    'healthy' => 'text-green-500',
                    'warning' => 'text-yellow-500',
                    'critical' => 'text-red-500'
                ];
            @endphp
            <div class="rounded-lg bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                @if($metricName === 'database')
                                    <svg class="h-8 w-8 {{ $iconColors[$metric['status']] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                                    </svg>
                                @elseif($metricName === 'users')
                                    <svg class="h-8 w-8 {{ $iconColors[$metric['status']] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                @elseif($metricName === 'devices')
                                    <svg class="h-8 w-8 {{ $iconColors[$metric['status']] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                @elseif($metricName === 'screens')
                                    <svg class="h-8 w-8 {{ $iconColors[$metric['status']] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                @elseif($metricName === 'content')
                                    <svg class="h-8 w-8 {{ $iconColors[$metric['status']] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                @else
                                    <svg class="h-8 w-8 {{ $iconColors[$metric['status']] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($metricName) }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $metric['message'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative h-12 w-12">
                                <svg class="h-12 w-12 transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-200 dark:text-gray-700" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                    <path class="{{ $iconColors[$metric['status']] }}" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" 
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" 
                                        stroke-dasharray="{{ $metric['score'] }}, 100">
                                    </path>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-semibold {{ $iconColors[$metric['status']] }}">{{ $metric['score'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Metric Details -->
                    <div class="mt-4 space-y-2">
                        @if(isset($metric['details']) && is_array($metric['details']))
                            @foreach($metric['details'] as $key => $value)
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $value }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="mt-3">
                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $statusColors[$metric['status']] }}">
                            {{ ucfirst($metric['status']) }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Detailed Health Table -->
    <div class="rounded-lg bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h4 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">Detailed Health Metrics</h4>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comprehensive breakdown of all system health indicators</p>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Component</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Score</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Message</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($healthMetrics as $metricName => $metric)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ ucfirst($metricName) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'healthy' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'critical' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$metric['status']] }}">
                                    {{ ucfirst($metric['status']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                        <div class="h-2 rounded-full {{ $metric['score'] >= 80 ? 'bg-green-500' : ($metric['score'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $metric['score'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium">{{ $metric['score'] }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $metric['message'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                @if(isset($metric['details']) && is_array($metric['details']))
                                    <div class="space-y-1">
                                        @foreach(array_slice($metric['details'], 0, 3) as $key => $value)
                                            <div class="text-xs">
                                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ $value }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">No details available</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Auto-refresh every 30 seconds
    setInterval(function() {
        @this.call('refreshHealth');
    }, {{ $refreshInterval * 1000 }});
</script>
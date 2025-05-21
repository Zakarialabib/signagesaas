<x-base-widget>

    @section('content')
        <div class="space-y-4">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $currentMonth }}
                </h3>
            </div>

            <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                    <div class="bg-gray-50 dark:bg-gray-800 p-2 text-center">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $dayName }}
                        </span>
                    </div>
                @endforeach

                @foreach ($calendar as $week)
                    @foreach ($week as $day)
                        @if ($day === null)
                            <div class="bg-gray-50 dark:bg-gray-800 p-2"></div>
                        @else
                            <div class="bg-white dark:bg-gray-800 p-2 min-h-[100px] relative">
                                <div class="absolute top-2 left-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $day['day'] }}
                                    </span>
                                </div>
                                @if (!empty($day['events']))
                                    <div class="mt-6 space-y-1">
                                        @foreach ($day['events'] as $event)
                                            <div class="text-xs p-1 rounded truncate"
                                                :class="{
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': '{{ $event['source'] }}'
                                                    === 'outlook',
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': '{{ $event['source'] }}'
                                                    === 'google'
                                                }">
                                                <div class="font-medium">{{ $event['title'] }}</div>
                                                <div class="text-xs opacity-75">
                                                    {{ \Carbon\Carbon::parse($event['start'])->format('g:i A') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>

            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Upcoming Events</h4>
                <div class="space-y-2">
                    @foreach ($events as $event)
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $event['title'] }}
                                    </h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($event['start'])->format('M j, g:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($event['end'])->format('g:i A') }}
                                    </p>
                                    @if ($event['location'])
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            📍 {{ $event['location'] }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    @if ($event['source'] === 'google')
                                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M7.88 12.04q0 .45-.11.87-.1.41-.33.74-.22.33-.58.52-.37.2-.87.2t-.85-.2q-.35-.21-.57-.55-.22-.33-.33-.75-.1-.42-.1-.86t.1-.87q.1-.43.34-.76.22-.34.59-.54.36-.2.87-.2t.86.2q.35.21.57.55.22.34.31.77.1.43.1.88zM24 12v9.38q0 .46-.33.8-.33.32-.8.32H7.13q-.46 0-.8-.33-.32-.33-.32-.8V18H1q-.41 0-.7-.3-.3-.29-.3-.7v-7q0-.41.3-.7Q.58 9 1 9h5V6.62q0-.47.33-.8.33-.32.8-.32h5.25V2.53q0-.13.1-.23.1-.1.24-.1h12.74q.14 0 .24.1.1.1.1.23v9.47zm-6.12 0q0-.45-.11-.87-.1-.41-.33-.74-.22-.33-.58-.52-.37-.2-.87-.2-.5 0-.85.2-.35.21-.57.55-.22.33-.33.75-.1.42-.1.86t.1.87q.1.43.34.76.22.34.59.54.36.2.87.2t.86-.2q.35-.21.57-.55.22-.34.31-.77.1-.43.1-.88zm0-6.5V2.54H12v2.96q0 .8-.32 1.49-.33.68-.89 1.2-.56.5-1.3.79-.74.28-1.61.28v4.48q.87 0 1.61.28.74.28 1.3.79.56.52.89 1.2.32.68.32 1.49v2.96h5.88V5.54zm6.12 0q0-.45-.11-.87-.1-.41-.33-.74-.22-.33-.58-.52-.37-.2-.87-.2-.5 0-.85.2-.35.21-.57.55-.22.33-.33.75-.1.42-.1.86t.1.87q.1.43.34.76.22.34.59.54.36.2.87.2t.86-.2q.35-.21.57-.55.22-.34.31-.77.1-.43.1-.88zm0-6.5V2.54h-5.88v2.96q0 .8-.32 1.49-.33.68-.89 1.2-.56.5-1.3.79-.74.28-1.61.28v4.48q.87 0 1.61.28.74.28 1.3.79.56.52.89 1.2.32.68.32 1.49v2.96h5.88V5.54z" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            Last updated {{ $lastUpdated }}
        </div>
    @endsection

    @section('settings')
        <div class="space-y-4">
            <div>
                <label for="view" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Calendar
                    View</label>
                <select id="view" wire:model="view"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="month">Month</option>
                    <option value="week">Week</option>
                    <option value="day">Day</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Calendar Sources</label>
                <div class="mt-2 space-y-2">
                    @foreach (['google', 'outlook'] as $source)
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="calendarSources" value="{{ $source }}"
                                class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <span
                                class="ml-2 text-sm text-gray-700 dark:text-gray-300 capitalize">{{ $source }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="refreshInterval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refresh
                    Interval (seconds)</label>
                <input type="number" id="refreshInterval" wire:model="refreshInterval" min="60" max="3600"
                    step="60"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
        </div>
    @endsection

</x-base-widget>

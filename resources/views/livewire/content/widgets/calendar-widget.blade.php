<x-base-widget>
    @section('content')
        <div class="p-1 bg-gradient-to-br from-slate-50 to-gray-100 dark:from-slate-800 dark:to-gray-900 rounded-lg shadow-inner h-full flex flex-col">
            <div class="mb-6 text-center">
                <h3 class="text-2xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-500 to-red-500 py-1">
                    {{ $currentMonth }}
                </h3>
            </div>

            <div class="grid grid-cols-7 gap-1 bg-slate-200 dark:bg-slate-700/50 rounded-md shadow overflow-hidden flex-grow">
                {{-- Calendar Header --}}
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                    <div class="bg-slate-100 dark:bg-slate-700 p-3 text-center shadow-sm">
                        <span class="text-xs font-semibold uppercase text-slate-600 dark:text-slate-300 tracking-wider">
                            {{ $dayName }}
                        </span>
                    </div>
                @endforeach

                {{-- Calendar Days --}}
                @foreach ($calendar as $week)
                    @foreach ($week as $day)
                        @if ($day === null)
                            <div class="bg-slate-50 dark:bg-slate-800/30 p-2 min-h-[80px] sm:min-h-[100px] transition-colors duration-150"></div> {{-- Empty cell --}}
                        @else
                            <div class="bg-white dark:bg-slate-800 p-2.5 min-h-[80px] sm:min-h-[100px] relative group hover:bg-slate-50 dark:hover:bg-slate-700/70 transition-colors duration-150 shadow-sm border border-transparent hover:border-purple-300 dark:hover:border-purple-700 rounded-sm flex flex-col">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold {{ $day['isToday'] ? 'text-purple-600 dark:text-purple-400 font-bold' : 'text-slate-700 dark:text-slate-300' }}">
                                        {{ $day['day'] }}
                                    </span>
                                    @if($day['isToday'])
                                        <span class="text-xs bg-purple-500 text-white px-1.5 py-0.5 rounded-full font-semibold">Today</span>
                                    @endif
                                </div>
                                @if (!empty($day['events']))
                                    <div class="mt-1.5 space-y-1.5 overflow-y-auto flex-grow scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-600 scrollbar-track-transparent pr-1">
                                        @foreach (collect($day['events'])->take(3) as $event) {{-- Limit to 3 events per day for cleaner look --}}
                                            <div class="text-xs p-1.5 rounded-md shadow-sm transition-all hover:shadow-md"
                                                :class="{
                                                    'bg-blue-100 hover:bg-blue-200 text-blue-800 dark:bg-blue-600/30 dark:hover:bg-blue-600/50 dark:text-blue-200': '{{ $event['source'] }}' === 'outlook',
                                                    'bg-green-100 hover:bg-green-200 text-green-800 dark:bg-green-600/30 dark:hover:bg-green-600/50 dark:text-green-200': '{{ $event['source'] }}' === 'google',
                                                    'bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-600/30 dark:hover:bg-gray-600/50 dark:text-gray-200': !['outlook', 'google'].includes('{{ $event['source'] }}')
                                                }">
                                                <div class="font-semibold truncate">{{ $event['title'] }}</div>
                                                <div class="text-xs opacity-80">
                                                    {{ \Carbon\Carbon::parse($event['start'])->format('g:i A') }}
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(count($day['events']) > 3)
                                            <div class="text-xs text-slate-500 dark:text-slate-400 text-center pt-1">+{{ count($day['events']) - 3 }} more</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex-grow"></div> {{-- Ensure empty days also take up space --}}
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>

            {{-- Upcoming Events Section --}}
            @if(!empty($events))
            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
                <h4 class="text-base font-semibold text-slate-800 dark:text-slate-200 mb-3 px-1">Upcoming Events</h4>
                <div class="space-y-3 max-h-48 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-600 scrollbar-track-transparent pr-2">
                    @foreach ($events as $event)
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-3.5 shadow-lg hover:shadow-xl transition-shadow duration-200 border-l-4"
                             :class="{
                                'border-blue-500': '{{ $event['source'] }}' === 'outlook',
                                'border-green-500': '{{ $event['source'] }}' === 'google',
                                'border-gray-400': !['outlook', 'google'].includes('{{ $event['source'] }}')
                             }">
                            <div class="flex items-center justify-between">
                                <div class="flex-grow min-w-0">
                                    <h5 class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $event['title'] }}</h5>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($event['start'])->format('M j, g:i A') }}
                                        @if(\Carbon\Carbon::parse($event['start'])->format('g:i A') !== \Carbon\Carbon::parse($event['end'])->format('g:i A'))
                                            - {{ \Carbon\Carbon::parse($event['end'])->format('g:i A') }}
                                        @endif
                                    </p>
                                    @if ($event['location'])
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="truncate">{{ $event['location'] }}</span>
                                        </p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0 ml-3">
                                    @if ($event['source'] === 'google')
                                        <span title="Google Calendar" class="p-1.5 bg-green-100 dark:bg-green-700/50 rounded-full">
                                            <svg class="h-4 w-4 text-green-600 dark:text-green-300" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z" />
                                            </svg>
                                        </span>
                                    @elseif ($event['source'] === 'outlook')
                                        <span title="Outlook Calendar" class="p-1.5 bg-blue-100 dark:bg-blue-700/50 rounded-full">
                                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-300" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M7.88 12.04q0 .45-.11.87-.1.41-.33.74-.22.33-.58.52-.37.2-.87.2t-.85-.2q-.35-.21-.57-.55-.22-.33-.33-.75-.1-.42-.1-.86t.1-.87q.1-.43.34-.76.22-.34.59-.54.36-.2.87-.2t.86.2q.35.21.57.55.22.34.31.77.1.43.1.88zM24 12v9.38q0 .46-.33.8-.33.32-.8.32H7.13q-.46 0-.8-.33-.32-.33-.32-.8V18H1q-.41 0-.7-.3-.3-.29-.3-.7v-7q0-.41.3-.7Q.58 9 1 9h5V6.62q0-.47.33-.8.33-.32.8-.32h5.25V2.53q0-.13.1-.23.1-.1.24-.1h12.74q.14 0 .24.1.1.1.1.23v9.47zm-6.12 0q0-.45-.11-.87-.1-.41-.33-.74-.22-.33-.58-.52-.37-.2-.87-.2-.5 0-.85.2-.35-.21-.57-.55-.22-.33-.33-.75-.1-.42-.1.86t.1.87q.1.43.34-.76.22-.34.59-.54.36.2.87.2t.86-.2q.35-.21.57.55.22-.34.31.77.1-.43.1.88zm0-6.5V2.54H12v2.96q0 .8-.32 1.49-.33.68-.89 1.2-.56.5-1.3.79-.74.28-1.61.28v4.48q.87 0 1.61.28.74.28 1.3.79.56.52.89 1.2.32.68.32 1.49v2.96h5.88V5.54zm6.12 0q0-.45-.11-.87-.1-.41-.33-.74-.22-.33-.58-.52-.37-.2-.87-.2-.5 0-.85.2-.35-.21-.57.55-.22-.33-.33-.75-.1-.42-.1.86t.1.87q.1.43.34-.76.22-.34.59-.54.36.2.87.2t.86-.2q.35-.21.57.55.22-.34.31.77.1-.43.1.88zm0-6.5V2.54h-5.88v2.96q0 .8-.32 1.49-.33.68-.89 1.2-.56.5-1.3.79-.74.28-1.61.28v4.48q.87 0 1.61.28.74.28 1.3.79.56.52.89 1.2.32.68.32 1.49v2.96h5.88V5.54z" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mt-auto pt-4 text-xs text-slate-400 dark:text-slate-500 text-right px-1">
                Last updated: {{ $lastUpdated }}
            </div>
        </div>
    @endsection

    @section('settings')
        <div class="space-y-6 p-1">
            <div>
                <label for="calendarView" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Calendar View</label>
                <select id="calendarView" wire:model.live="view" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 text-sm">
                    <option value="month">Month</option>
                    <option value="week">Week</option>
                    <option value="day">Day</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Calendar Sources</label>
                <div class="mt-2 space-y-2.5">
                    @foreach (['google', 'outlook'] as $source)
                        <label class="flex items-center p-2 bg-slate-50 dark:bg-slate-700/50 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <input type="checkbox" wire:model.live="calendarSources" value="{{ $source }}" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-purple-600 shadow-sm focus:ring-purple-500 focus:ring-offset-0 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-700/50">
                            <span class="ml-3 text-sm text-slate-700 dark:text-slate-300 capitalize">{{ $source }} Calendar</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="refreshIntervalCalendar" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Refresh Interval (seconds)</label>
                <input type="number" id="refreshIntervalCalendar" wire:model.live="refreshInterval" min="60" max="3600" step="60" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 text-sm" placeholder="e.g., 300">
            </div>
        </div>
    @endsection
</x-base-widget>

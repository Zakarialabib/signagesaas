<div>
    @php
        use Carbon\Carbon;
        $currentDate = Carbon::create($currentYear, $currentMonth, $currentDay, 0, 0, 0, $displayTimeZone);

        // Determine the start and end of the week based on $startOfWeek setting
        if ($startOfWeek === 'sunday') {
            $startOfWeekCarbon = $currentDate->copy()->startOfWeek(Carbon::SUNDAY);
            $endOfWeekCarbon = $currentDate->copy()->endOfWeek(Carbon::SATURDAY);
        } else {
            $startOfWeekCarbon = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
            $endOfWeekCarbon = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);
        }

        $weekDates = [];
        $tempDate = $startOfWeekCarbon->copy();
        for ($i = 0; $i < 7; $i++) {
            $dayEvents = collect($events)
                ->filter(function ($event) use ($tempDate, $displayTimeZone) {
                    $eventStart = Carbon::parse($event['start'], $displayTimeZone);
                    $eventEnd = Carbon::parse($event['end'], $displayTimeZone);
                    // Check if event falls on this day, or if it's a multi-day event spanning this day
            return $tempDate->isSameDay($eventStart->copy()->startOfDay()) ||
                $tempDate->isBetween($eventStart->copy()->startOfDay(), $eventEnd->copy()->endOfDay());
        })
        ->sortBy(function ($event) {
            return Carbon::parse($event['start'], $displayTimeZone)->format('His'); // Sort by time
        })
        ->values()
        ->all();

    $weekDates[] = [
        'date' => $tempDate->copy(),
        'isToday' => $tempDate->isToday(),
        'events' => $dayEvents,
    ];
    $tempDate->addDay();
}

$weekPeriod = $startOfWeekCarbon->format('M d') . ' - ' . $endOfWeekCarbon->format('M d, Y');
    @endphp

    <div class="h-full flex flex-col bg-gray-50 dark:bg-gray-850 text-gray-700 dark:text-gray-200 rounded-lg shadow week-view-calendar-content"
        x-data="{
            selectedEvent: null,
            showEventModal: false,
            openEventModal(event) {
                this.selectedEvent = event;
                this.selectedEvent.formattedStart = new Date(event.start).toLocaleString(undefined, { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                this.selectedEvent.formattedEnd = new Date(event.end).toLocaleString(undefined, { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                this.showEventModal = true;
            },
            getEventColor(colorName) {
                const colors = {
                    blue: 'border-blue-500 dark:border-blue-600',
                    red: 'border-red-500 dark:border-red-600',
                    green: 'border-green-500 dark:border-green-600',
                    purple: 'border-purple-500 dark:border-purple-600',
                    amber: 'border-amber-500 dark:border-amber-500',
                    pink: 'border-pink-500 dark:border-pink-600',
                    gray: 'border-gray-400 dark:border-gray-500',
                    default: 'border-gray-500 dark:border-gray-600'
                };
                return colors[colorName] || colors.default;
            },
            getEventBgColor(colorName) {
                const bgColors = {
                    blue: 'bg-blue-50 dark:bg-blue-900/30',
                    red: 'bg-red-50 dark:bg-red-900/30',
                    green: 'bg-green-50 dark:bg-green-900/30',
                    purple: 'bg-purple-50 dark:bg-purple-900/30',
                    amber: 'bg-amber-50 dark:bg-amber-900/30',
                    pink: 'bg-pink-50 dark:bg-pink-900/30',
                    gray: 'bg-gray-100 dark:bg-gray-700/30',
                    default: 'bg-gray-50 dark:bg-gray-900/30'
                };
                return bgColors[colorName] || bgColors.default;
            }
        }">

        <header
            class="p-4 flex items-center justify-between border-b dark:border-gray-700 shrink-0 bg-white dark:bg-gray-800 rounded-t-lg">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Week: {{ $weekPeriod }}</h2>
            <div class="flex space-x-2">
                <button wire:click="goToDate('{{ $startOfWeekCarbon->copy()->subWeek()->toDateString() }}')"
                    title="Previous Week"
                    class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <i class="fas fa-chevron-left text-gray-600 dark:text-gray-300"></i>
                </button>
                <button wire:click="goToDate('{{ Carbon::now($displayTimeZone)->toDateString() }}')" title="This Week"
                    class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm font-medium text-gray-600 dark:text-gray-300">
                    This Week
                </button>
                <button wire:click="goToDate('{{ $startOfWeekCarbon->copy()->addWeek()->toDateString() }}')"
                    title="Next Week"
                    class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <i class="fas fa-chevron-right text-gray-600 dark:text-gray-300"></i>
                </button>
            </div>
        </header>

        @if (!empty($error))
            <div class="m-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <p class="font-bold">Calendar Error</p>
                <p>{{ $error }}</p>
            </div>
        @endif

        @if ($isLoading && empty($events))
            <div class="flex-grow flex items-center justify-center p-6">
                <div class="animate-pulse flex flex-col items-center space-y-2">
                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Loading week's events...</p>
                </div>
            </div>
        @else
            <div class="flex-grow grid grid-cols-1 md:grid-cols-7 md:divide-x dark:divide-gray-700 overflow-hidden">
                @foreach ($weekDates as $day)
                    <div
                        class="flex flex-col {{ $loop->first ? '' : 'md:border-l' }} dark:border-gray-700 {{ $loop->iteration <= 7 - 1 ? 'border-b md:border-b-0' : '' }} dark:border-gray-700">
                        <header
                            class="p-3 text-center border-b dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm md:shadow-none">
                            <h3
                                class="text-sm font-semibold uppercase {{ $day['isToday'] ? 'text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300' }}">
                                {{ $day['date']->format('D') }}
                            </h3>
                            <p
                                class="text-2xl font-bold {{ $day['isToday'] ? 'text-purple-600 dark:text-purple-400' : 'text-gray-800 dark:text-gray-100' }}">
                                {{ $day['date']->format('d') }}
                            </p>
                        </header>
                        <div
                            class="flex-grow p-2 space-y-2 overflow-y-auto custom-scrollbar-thin min-h-[200px] md:min-h-0">
                            @forelse ($day['events'] as $event)
                                <div @click="openEventModal({{ json_encode($event) }})"
                                    class="p-2.5 rounded-md shadow-sm cursor-pointer hover:shadow-lg transition-shadow border-l-4 text-gray-800 dark:text-gray-100"
                                    :class="[getEventColor('{{ $event['color'] ?? 'default' }}'), getEventBgColor(
                                        '{{ $event['color'] ?? 'default' }}')]">
                                    <p class="font-semibold text-sm truncate" title="{{ $event['title'] }}">
                                        {{ $event['title'] }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">
                                        @if ($event['isFullDay'])
                                            All Day
                                        @else
                                            {{ Carbon::parse($event['start'])->setTimezone($displayTimeZone)->format('g:i A') }}
                                            -
                                            {{ Carbon::parse($event['end'])->setTimezone($displayTimeZone)->format('g:i A') }}
                                        @endif
                                    </p>
                                    @if (!empty($event['location']))
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><i
                                                class="fas fa-map-marker-alt fa-xs mr-1"></i>{{ $event['location'] }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center text-xs text-gray-400 dark:text-gray-500 pt-4 italic">
                                    No events scheduled.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Event Detail Modal (re-uses the same structure as month-view) --}}
        <div x-show="showEventModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" @click.away="showEventModal = false"
            @keydown.escape.window="showEventModal = false"
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[100] p-4" x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full max-h-[85vh] flex flex-col">
                <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="selectedEvent?.title"></h4>
                    <button @click="showEventModal = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-5 flex-grow overflow-y-auto space-y-3 text-sm custom-scrollbar-thin">
                    <p><strong class="text-gray-600 dark:text-gray-400">Starts:</strong> <span
                            class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.formattedStart"></span></p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Ends:</strong> <span
                            class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.formattedEnd"></span></p>
                    <div x-show="selectedEvent?.isFullDay">
                        <p><strong class="text-gray-600 dark:text-gray-400">Duration:</strong> <span
                                class="text-gray-800 dark:text-gray-200">All Day</span></p>
                    </div>
                    <div x-show="selectedEvent?.location">
                        <strong class="text-gray-600 dark:text-gray-400">Location:</strong>
                        <span class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.location"></span>
                    </div>
                    <div x-show="selectedEvent?.category">
                        <strong class="text-gray-600 dark:text-gray-400">Category:</strong>
                        <span class="px-2 py-0.5 text-xs rounded-full text-white"
                            :class="selectedEvent && getEventBgColor(selectedEvent.color || 'default') + ' ' + getEventColor(
                                selectedEvent.color || 'default').replace('border-', 'text-')"
                            x-text="selectedEvent?.category">
                        </span>
                    </div>
                    <div x-show="selectedEvent?.description">
                        <strong class="text-gray-600 dark:text-gray-400 block mb-1">Description:</strong>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300"
                            x-html="selectedEvent?.description"></div>
                    </div>
                    <div x-show="selectedEvent?.attendees && selectedEvent.attendees.length > 0">
                        <strong class="text-gray-600 dark:text-gray-400 block mb-1">Attendees:</strong>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                            <template x-for="attendee in selectedEvent.attendees" :key="attendee">
                                <li x-text="attendee"></li>
                            </template>
                        </ul>
                    </div>
                </div>
                <div class="p-4 border-t dark:border-gray-700 flex justify-end bg-gray-50 dark:bg-gray-850/50">
                    <button @click="showEventModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <style>
            .custom-scrollbar-thin::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }

            .custom-scrollbar-thin::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar-thin::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                /* Tailwind gray-300 */
                border-radius: 2.5px;
            }

            .dark .custom-scrollbar-thin::-webkit-scrollbar-thumb {
                background: #4b5563;
                /* Tailwind gray-600 */
            }

            .prose ul {
                padding-left: 1.25em;
            }

            .prose li {
                margin-top: 0.25em;
                margin-bottom: 0.25em;
            }
        </style>
    </div>
</div>

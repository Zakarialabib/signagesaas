@php
    use Carbon\Carbon;
    $firstDayOfMonth = Carbon::create($currentYear, $currentMonth, 1, 0, 0, 0, $displayTimeZone);
    // Determine the actual first day to display on the calendar (could be from previous month)
    $startDayCalendar = $firstDayOfMonth->copy()->startOfWeek(Carbon::MONDAY);
    if ($startOfWeek === 'sunday') {
        $startDayCalendar = $firstDayOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
    }
    $daysInMonth = $firstDayOfMonth->daysInMonth;
    $monthName = $firstDayOfMonth->format('F Y');

    $weeks = [];
    $currentCalDay = $startDayCalendar->copy();
    for ($w = 0; $w < 6; $w++) { // Max 6 weeks to display for a month
        $week = [];
        for ($d = 0; $d < 7; $d++) {
            $dayEvents = collect($events)->filter(function ($event) use ($currentCalDay, $displayTimeZone) {
                $eventStart = Carbon::parse($event['start'], $displayTimeZone)->startOfDay();
                $eventEnd = Carbon::parse($event['end'], $displayTimeZone)->endOfDay();
                return $currentCalDay->between($eventStart, $eventEnd) || $currentCalDay->isSameDay($eventStart);
            })->sortBy('start')->values()->all();

            $week[] = [
                'date' => $currentCalDay->copy(),
                'isCurrentMonth' => $currentCalDay->month == $currentMonth,
                'isToday' => $currentCalDay->isToday(),
                'events' => $dayEvents,
            ];
            $currentCalDay->addDay();
        }
        $weeks[] = $week;
        if ($currentCalDay->month != $currentMonth && $currentCalDay->day > 7) break; // Optimization: stop if next month and past first week
    }

    $dayNames = ($startOfWeek === 'sunday')
        ? ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
        : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
@endphp

<div class="h-full flex flex-col bg-white dark:bg-gray-850 text-gray-700 dark:text-gray-200 rounded-lg shadow month-view-calendar-content"
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
                blue: 'bg-blue-500 dark:bg-blue-600',
                red: 'bg-red-500 dark:bg-red-600',
                green: 'bg-green-500 dark:bg-green-600',
                purple: 'bg-purple-500 dark:bg-purple-600',
                amber: 'bg-amber-500 dark:bg-amber-500',
                pink: 'bg-pink-500 dark:bg-pink-600',
                gray: 'bg-gray-400 dark:bg-gray-500',
                default: 'bg-gray-500 dark:bg-gray-600'
            };
            return colors[colorName] || colors.default;
        }
    }">

    <header class="p-4 flex items-center justify-between border-b dark:border-gray-700 shrink-0">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ $monthName }}</h2>
        <div class="flex space-x-2">
            <button wire:click="goToPreviousMonth" title="Previous Month" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                <i class="fas fa-chevron-left text-gray-600 dark:text-gray-300"></i>
            </button>
            <button wire:click="goToDate('{{ Carbon::now($displayTimeZone)->toDateString() }}')" title="Today" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm font-medium text-gray-600 dark:text-gray-300">
                Today
            </button>
            <button wire:click="goToNextMonth" title="Next Month" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
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
        <div class="flex-grow flex items-center justify-center">
            <div class="animate-pulse flex flex-col items-center space-y-2">
                <div class="h-8 w-8 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                <p class="text-gray-500 dark:text-gray-400">Loading Events...</p>
            </div>
        </div>
    @else
        <div class="flex-grow grid grid-cols-7 border-t border-l dark:border-gray-700">
            {{-- Day Headers --}}
            @foreach ($dayNames as $dayName)
                <div class="p-2 text-center text-xs font-medium uppercase text-gray-500 dark:text-gray-400 border-r border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 tracking-wider">
                    {{ $dayName }}
                </div>
            @endforeach

            {{-- Calendar Days --}}
            @foreach ($weeks as $week)
                @foreach ($week as $day)
                    <div class="p-1.5 border-r border-b dark:border-gray-700 min-h-[100px] flex flex-col relative {{ $day['isCurrentMonth'] ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-800/30' }} {{ $day['isToday'] ? 'ring-2 ring-purple-500 dark:ring-purple-400 z-10' : '' }} group hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors duration-150 ease-in-out">
                        <span class="text-xs font-semibold mb-1 {{ $day['isCurrentMonth'] ? ($day['isToday'] ? 'text-purple-600 dark:text-purple-300' : 'text-gray-700 dark:text-gray-200') : 'text-gray-400 dark:text-gray-500' }}">
                            {{ $day['date']->day }}
                        </span>
                        <div class="flex-grow space-y-0.5 overflow-y-auto text-xs pr-0.5 custom-scrollbar-thin">
                            @forelse (collect($day['events'])->take($eventDisplayLimit) as $event)
                                <div @click="openEventModal({{ json_encode($event) }})" 
                                     class="p-1 rounded text-white truncate cursor-pointer hover:opacity-80 transition-opacity text-[10px] leading-tight tracking-tighter"
                                     :class="getEventColor('{{ $event['color'] ?? 'default' }}')" title="{{ $event['title'] }}">
                                    <i class="fas fa-circle fa-xs mr-1 opacity-75" style="font-size: 0.5rem;"></i>{{ $event['title'] }}
                                </div>
                            @empty
                                {{-- No events for this day --}}
                            @endforelse
                            @if (count($day['events']) > $eventDisplayLimit)
                                <div class="text-center text-gray-500 dark:text-gray-400 mt-1 text-[10px] cursor-pointer hover:underline"
                                     @click="openEventModal({{ json_encode($day['events'][0]) }})" {{-- Or a dedicated modal for "more events" --}}
                                >
                                    +{{ count($day['events']) - $eventDisplayLimit }} more
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @endif

    {{-- Event Detail Modal --}}
    <div x-show="showEventModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         @click.away="showEventModal = false" 
         @keydown.escape.window="showEventModal = false"
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[100] p-4" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full max-h-[85vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="selectedEvent?.title"></h4>
                <button @click="showEventModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-5 flex-grow overflow-y-auto space-y-3 text-sm">
                <p><strong class="text-gray-600 dark:text-gray-400">Starts:</strong> <span class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.formattedStart"></span></p>
                <p><strong class="text-gray-600 dark:text-gray-400">Ends:</strong> <span class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.formattedEnd"></span></p>
                <div x-show="selectedEvent?.location">
                    <strong class="text-gray-600 dark:text-gray-400">Location:</strong> 
                    <span class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.location"></span>
                </div>
                <div x-show="selectedEvent?.category">
                    <strong class="text-gray-600 dark:text-gray-400">Category:</strong> 
                    <span class="px-2 py-0.5 text-xs rounded-full text-white" 
                          :class="getEventColor(selectedEvent?.color || 'default')" 
                          x-text="selectedEvent?.category">
                    </span>
                </div>
                <div x-show="selectedEvent?.description">
                    <strong class="text-gray-600 dark:text-gray-400 block mb-1">Description:</strong>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300" x-html="selectedEvent?.description"></div>
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
                <button @click="showEventModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
    <style>
        .custom-scrollbar-thin::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .custom-scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar-thin::-webkit-scrollbar-thumb {
            background: #d1d5db; /* Tailwind gray-300 */
            border-radius: 2px;
        }
        .dark .custom-scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4b5563; /* Tailwind gray-600 */
        }
        .prose ul { padding-left: 1.25em; }
        .prose li { margin-top: 0.25em; margin-bottom: 0.25em; }
    </style>
</div> 
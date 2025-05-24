@php
    use Carbon\Carbon;
    $today = Carbon::now($displayTimeZone)->startOfDay();

    $upcomingEvents = collect($events)->filter(function ($event) use ($today, $displayTimeZone) {
        $eventEnd = Carbon::parse($event['end'], $displayTimeZone);
        return $eventEnd->isSameDay($today) || $eventEnd->isAfter($today);
    })->sortBy(function($event) {
        return Carbon::parse($event['start'], $displayTimeZone)->timestamp; // Sort by start time
    })->groupBy(function($event) use ($displayTimeZone) {
        return Carbon::parse($event['start'], $displayTimeZone)->format('Y-m-d'); // Group by date
    })->all();

    // If you want to limit the number of days shown, you can slice $upcomingEvents here.
    // For example: $upcomingEvents = array_slice($upcomingEvents, 0, 7, true); // Show for next 7 days that have events

@endphp

<div class="h-full flex flex-col bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-lg shadow list-view-calendar-content"
    x-data="{
        selectedEvent: null,
        showEventModal: false,
        openEventModal(event) {
            this.selectedEvent = event;
            // Ensure start/end are parsed correctly for display, assuming they are full ISO strings
            this.selectedEvent.formattedStart = new Date(event.start).toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' });
            this.selectedEvent.formattedEnd = new Date(event.end).toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' });
            this.showEventModal = true;
        },
        getEventColor(colorName) {
            const colors = {
                blue: 'bg-blue-500 dark:bg-blue-600 text-white',
                red: 'bg-red-500 dark:bg-red-600 text-white',
                green: 'bg-green-500 dark:bg-green-600 text-white',
                purple: 'bg-purple-500 dark:bg-purple-600 text-white',
                amber: 'bg-amber-500 dark:bg-amber-500 text-black',
                pink: 'bg-pink-500 dark:bg-pink-600 text-white',
                gray: 'bg-gray-400 dark:bg-gray-500 text-white',
                default: 'bg-gray-500 dark:bg-gray-600 text-white'
            };
            return colors[colorName] || colors.default;
        }
    }">

    <header class="p-4 border-b dark:border-gray-700 shrink-0">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Upcoming Events List</h2>
        {{-- No specific navigation for list view header, could add date range if limited --}}
    </header>

    @if (!empty($error))
        <div class="m-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
            <p class="font-bold">Calendar Error</p>
            <p>{{ $error }}</p>
        </div>
    @endif

    @if ($isLoading && empty($upcomingEvents) && empty($events) )
        <div class="flex-grow flex items-center justify-center p-6">
            <div class="animate-pulse flex flex-col items-center space-y-2">
                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                <p class="text-gray-500 dark:text-gray-400">Fetching event list...</p>
            </div>
        </div>
    @elseif (empty($upcomingEvents))
        <div class="flex-grow flex items-center justify-center p-6 text-center">
            <div>
                <i class="fas fa-calendar-times fa-3x text-gray-400 dark:text-gray-500 mb-3"></i>
                <p class="text-lg text-gray-500 dark:text-gray-400">No upcoming events found.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">Check back later or try a different view.</p>
            </div>
        </div>
    @else
        <div class="flex-grow overflow-y-auto p-4 space-y-6 custom-scrollbar-list">
            @foreach ($upcomingEvents as $date => $eventsOnDate)
                @php $carbonDate = Carbon::parse($date, $displayTimeZone); @endphp
                <section class="day-group fade-in-list" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <h3 class="text-lg font-semibold text-purple-600 dark:text-purple-400 mb-2 sticky top-0 bg-white dark:bg-gray-800 py-2 z-10 border-b dark:border-gray-700/50">
                        {{ $carbonDate->isToday() ? 'Today' : '' }}
                        {{ $carbonDate->isTomorrow() ? 'Tomorrow' : '' }}
                        @if(!$carbonDate->isToday() && !$carbonDate->isTomorrow())
                            {{ $carbonDate->format('l, F j, Y') }}
                        @else
                             <span class="text-gray-500 dark:text-gray-400 font-normal text-base"> - {{ $carbonDate->format('F j, Y') }}</span>
                        @endif
                    </h3>
                    <ul class="space-y-3">
                        @foreach ($eventsOnDate as $event)
                            <li @click="openEventModal({{ json_encode($event) }})"
                                class="event-item flex items-start p-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer bg-gray-50 dark:bg-gray-700/60 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="time-info text-center mr-4 shrink-0 w-20">
                                    @if($event['isFullDay'])
                                        <span class="block text-xs uppercase tracking-wider font-semibold text-gray-500 dark:text-gray-400 py-1 px-2 rounded-md bg-gray-200 dark:bg-gray-600">All Day</span>
                                    @else
                                        <span class="block text-md font-bold text-purple-600 dark:text-purple-400">{{ Carbon::parse($event['start'])->setTimezone($displayTimeZone)->format('g:i A') }}</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">to {{ Carbon::parse($event['end'])->setTimezone($displayTimeZone)->format('g:i A') }}</span>
                                    @endif
                                </div>
                                <div class="event-details flex-grow min-w-0">
                                    <h4 class="font-semibold text-gray-800 dark:text-gray-100 truncate" title="{{ $event['title'] }}">{{ $event['title'] }}</h4>
                                    @if(!empty($event['location']))
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><i class="fas fa-map-marker-alt fa-xs mr-1 opacity-75"></i>{{ $event['location'] }}</p>
                                    @endif
                                    @if(!empty($event['description']))
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 truncate">{{ Str::limit($event['description'], 100) }}</p>
                                    @endif
                                </div>
                                <div class="category-badge ml-3 shrink-0">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $event['color'] ?? 'gray' }}"
                                          :class="getEventColor('{{ $event['color'] ?? 'default' }}')">
                                        {{ $event['category'] ?? 'General' }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    @endif

    {{-- Event Detail Modal (re-uses the same structure) --}}
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
            <div class="p-5 flex-grow overflow-y-auto space-y-3 text-sm custom-scrollbar-list">
                <p><strong class="text-gray-600 dark:text-gray-400">Starts:</strong> <span class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.formattedStart"></span></p>
                <p><strong class="text-gray-600 dark:text-gray-400">Ends:</strong> <span class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.formattedEnd"></span></p>
                 <div x-show="selectedEvent?.isFullDay">
                     <p><strong class="text-gray-600 dark:text-gray-400">Duration:</strong> <span class="text-gray-800 dark:text-gray-200">All Day</span></p>
                </div>
                <div x-show="selectedEvent?.location">
                    <strong class="text-gray-600 dark:text-gray-400">Location:</strong> 
                    <span class="text-gray-800 dark:text-gray-200" x-text="selectedEvent?.location"></span>
                </div>
                <div x-show="selectedEvent?.category">
                    <strong class="text-gray-600 dark:text-gray-400">Category:</strong> 
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                          :class="selectedEvent && getEventColor(selectedEvent.color || 'default')" 
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
        .custom-scrollbar-list::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar-list::-webkit-scrollbar-track {
            background: transparent; 
        }
        .custom-scrollbar-list::-webkit-scrollbar-thumb {
            background: #e5e7eb; /* Tailwind gray-200 */
            border-radius: 3px;
        }
        .dark .custom-scrollbar-list::-webkit-scrollbar-thumb {
            background: #4b5563; /* Tailwind gray-600 */
        }
        .fade-in-list {
            animation: fadeInListAnimation 0.4s ease-out forwards;
            opacity: 0;
        }
        @keyframes fadeInListAnimation {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .prose ul { padding-left: 1.25em; }
        .prose li { margin-top: 0.25em; margin-bottom: 0.25em; }
    </style>
</div> 
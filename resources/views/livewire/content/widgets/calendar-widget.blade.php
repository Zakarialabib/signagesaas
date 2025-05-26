<div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
    {{-- Widget Header (from BaseWidget) --}}
    <div class="p-4 border-b dark:border-gray-700 shrink-0 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
        {{-- Potentially add current month/year display here for month/week views, or other context --}}
    </div>

    {{-- Main Content Area: Tabs + Active Template + Settings --}}
    <div class="flex flex-row flex-grow overflow-hidden">
        {{-- Content Side: Tabs + Active Template --}}
        <div class="flex-grow flex flex-col p-4 overflow-y-auto">
            {{-- Loading Indicator --}}
            <div wire:loading.flex wire:target="setView,loadData,goToNextMonth,goToPreviousMonth,goToDate" class="absolute inset-x-0 top-0 bg-white bg-opacity-75 dark:bg-gray-800 dark:bg-opacity-75 flex items-center justify-center z-50 p-4">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-purple-500"></div>
                <span class="ml-2 text-gray-700 dark:text-gray-200">Updating Calendar...</span>
            </div>

            {{-- Error Message --}}
            @if ($error)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            {{-- Tab Navigation for different calendar views/templates --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700 shrink-0">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="calendarWidgetTabs-{{ $widgetId }}" role="tablist">
                    @foreach ($availableViews as $viewKey => $viewDetails)
                        <li class="mr-2" role="presentation">
                            <button
                                class="inline-block p-4 border-b-2 rounded-t-lg focus:outline-none font-semibold
                                       {{ $activeView === $viewKey
                                           ? 'text-purple-600 border-purple-600 dark:text-purple-400 dark:border-purple-400'
                                           : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
                                id="tab-{{ $widgetId }}-{{ $viewKey }}" wire:click="setView('{{ $viewKey }}')"
                                type="button" role="tab" aria-controls="content-{{ $widgetId }}-{{ $viewKey }}"
                                aria-selected="{{ $activeView === $viewKey ? 'true' : 'false' }}">
                                {{ $viewDetails['name'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Content Area for the Active Tab (the selected calendar template) --}}
            <div class="flex-grow overflow-y-auto calendar-widget-active-template-content">
                 @include($availableViews[$activeView]['view_path'], [
                    'widgetTitle' => $widgetTitle,
                    'events' => $events, // Filtered/processed events for the current view span
                    'currentYear' => $currentYear,
                    'currentMonth' => $currentMonth,
                    'currentDay' => $currentDay,
                    'startOfWeek' => $startOfWeek,
                    'eventDisplayLimit' => $eventDisplayLimit,
                    'displayTimeZone' => $displayTimeZone,
                    'error' => $error,
                    'isLoading' => $isLoading,
                    'widgetId' => $widgetId . '-' . $activeView // For unique IDs within templates
                ])
            </div>

            {{-- Auto-refresh polling --}}
            @if ($refreshInterval > 0)
                <div wire:poll.{{ $refreshInterval }}s="loadData" class="hidden">
                    {{-- This div will trigger loadData periodically --}}
                </div>
            @endif
        </div>

        {{-- Settings Panel (Sidebar) --}}
        <div class="w-72 bg-gray-50 dark:bg-gray-850 p-4 border-l dark:border-gray-700 overflow-y-auto shrink-0">
            <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Calendar Settings</h4>
            <div class="space-y-4">
                <div>
                    <label for="calendarWidgetActiveViewSelector-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Active View</label>
                    <select id="calendarWidgetActiveViewSelector-{{ $widgetId }}" wire:model.live="activeView"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                        @foreach($availableViews as $key => $details)
                            <option value="{{ $key }}">{{ $details['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="calendarDefaultDate-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Display Date</label>
                    <input type="date" id="calendarDefaultDate-{{ $widgetId }}" 
                           wire:model.live="defaultDate" 
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                     <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Currently: {{ Carbon\Carbon::create($currentYear, $currentMonth, $currentDay)->format('M d, Y') }}</p>
                </div>

                <div>
                    <label for="calendarStartOfWeek-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start of Week</label>
                    <select id="calendarStartOfWeek-{{ $widgetId }}" wire:model.live="startOfWeek"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                        <option value="sunday">Sunday</option>
                        <option value="monday">Monday</option>
                    </select>
                </div>

                 <div>
                    <label for="calendarEventDisplayLimit-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event Limit (Month View)</label>
                    <input type="number" id="calendarEventDisplayLimit-{{ $widgetId }}" wire:model.live="eventDisplayLimit"
                           min="1" max="10" step="1"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                </div>
                 <div>
                    <label for="calendarDisplayTimeZone-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Timezone</label>
                    <input type="text" id="calendarDisplayTimeZone-{{ $widgetId }}" wire:model.live="displayTimeZone" 
                           placeholder="e.g., America/New_York"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                </div>

                <div class="space-y-2">
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Options</span>
                    <label class="inline-flex items-center w-full">
                        <input type="checkbox" wire:model.live="showRemindersOption"
                            class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Reminders (Visual)</span>
                    </label>
                </div>

                <div>
                    <label for="calendarRefreshInterval-{{ $widgetId }}"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refresh Interval (sec)</label>
                    <input type="number" id="calendarRefreshInterval-{{ $widgetId }}" wire:model.live="refreshInterval"
                        min="60" max="3600" step="60"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                </div>
                
                {{-- Navigation buttons for month view (could be part of the template itself too) --}}
                @if($activeView === 'month-view')
                <div class="pt-2 space-y-2 border-t dark:border-gray-700">
                     <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Quick Navigation</h5>
                    <div class="flex items-center justify-between">
                        <button wire:click="goToPreviousMonth" class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-md text-gray-700 dark:text-gray-200 transition-colors"><i class="fas fa-chevron-left mr-1"></i> Prev</button>
                        <button wire:click="goToDate('{{ Carbon\Carbon::now($displayTimeZone)->toDateString() }}')" class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-md text-gray-700 dark:text-gray-200 transition-colors">Today</button>
                        <button wire:click="goToNextMonth" class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-md text-gray-700 dark:text-gray-200 transition-colors">Next <i class="fas fa-chevron-right ml-1"></i></button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

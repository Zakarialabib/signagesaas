<div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
    {{-- Optional: Widget Header (if not handled by a base widget layout) --}}
    <div class="p-4 border-b dark:border-gray-700 shrink-0">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3> {{-- BaseWidget's title --}}
    </div>

    {{-- Main Content Area: Tabs + Active Template + Settings --}}
    <div class="flex flex-row flex-grow overflow-hidden">
        {{-- Content Side: Tabs + Active Template --}}
        <div class="flex-grow flex flex-col p-4 overflow-y-auto">
            {{-- Loading Indicator --}}
            <div wire:loading.flex wire:target="setView,loadData" class="absolute inset-x-0 top-0 bg-white bg-opacity-75 flex items-center justify-center z-50 p-4">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                <span class="ml-2 text-gray-700">Loading...</span>
            </div>

            {{-- Error Message --}}
            @if ($error)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            {{-- Tab Navigation for different views/templates --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700 shrink-0">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="menuWidgetTabs-{{ $widgetId }}" role="tablist">
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

            {{-- Content Area for the Active Tab (the selected template) --}}
            <div class="flex-grow overflow-y-auto menu-widget-active-template-content">
                {{-- Ensure the included template's root is h-full and can scroll its own content if needed --}}
                @include($availableViews[$activeView]['view_path'], [
                    'widgetTitle' => $widgetTitle,
                    'menu' => $menu,
                    'lastUpdated' => $lastUpdated,
                    'showPrices' => $showPrices,
                    'showCalories' => $showCalories,
                    'showAllergens' => $showAllergens,
                    'currency' => $currency,
                    'error' => $error, // Pass error to sub-template if it needs to display it
                    'isLoading' => $isLoading, // Pass isLoading to sub-template
                    'widgetId' => $widgetId . '-' . $activeView // Pass a more specific ID if needed by template JS
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
            <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Widget Settings</h4>
            <div class="space-y-4">
                <div>
                    <label for="widgetActiveViewSelector-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Active Template</label>
                    <select id="widgetActiveViewSelector-{{ $widgetId }}" wire:model.live="activeView"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                        @foreach($availableViews as $key => $details)
                            <option value="{{ $key }}">{{ $details['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="menuType-{{ $widgetId }}"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Menu Type</label>
                    <select id="menuType-{{ $widgetId }}" wire:model.live="menuType"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                        <option value="restaurant">Restaurant</option>
                        <option value="cafeteria">Cafeteria</option>
                        <option value="bar">Bar</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Options</span>
                    <label class="inline-flex items-center w-full">
                        <input type="checkbox" wire:model.live="showPrices"
                            class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Prices</span>
                    </label>
                    <label class="inline-flex items-center w-full">
                        <input type="checkbox" wire:model.live="showCalories"
                            class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Calories</span>
                    </label>
                    <label class="inline-flex items-center w-full">
                        <input type="checkbox" wire:model.live="showAllergens"
                            class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Allergens</span>
                    </label>
                </div>
                <div>
                    <label for="currency-{{ $widgetId }}"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                    <input type="text" id="currency-{{ $widgetId }}" wire:model.live="currency" maxlength="3"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                </div>
                <div>
                    <label for="refreshInterval-{{ $widgetId }}"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refresh Interval (sec)</label>
                    <input type="number" id="refreshInterval-{{ $widgetId }}" wire:model.live="refreshInterval"
                        min="30" max="3600" step="30"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                </div>
            </div>
        </div>
    </div>
</div>

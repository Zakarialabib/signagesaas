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
            <div wire:loading.flex wire:target="setView,loadData"
                class="fixed inset-0 bg-white bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 flex items-center justify-center z-50 p-4">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-purple-500 mb-3"></div>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">
                        @if ($isLoading)
                            Loading
                            {{ $activeView === 'default' ? 'Classic View' : $availableViews[$activeView]['name'] }}...
                        @else
                            Processing your request...
                        @endif
                    </span>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-3">
                        <div class="bg-purple-500 h-2.5 rounded-full animate-pulse" style="width: 70%"></div>
                    </div>
                </div>
            </div>


            {{-- Error Message --}}
            @if ($error)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            {{-- Tab Navigation for different views/templates --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700 shrink-0">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center"
                    id="menuWidgetTabs-{{ $widgetId }}" role="tablist">
                    @foreach ($availableViews as $viewKey => $viewDetails)
                        <li class="mr-2" role="presentation">
                            <button
                                class="inline-block p-4 border-b-2 rounded-t-lg focus:outline-none font-semibold
                                       {{ $activeView === $viewKey
                                           ? 'text-purple-600 border-purple-600 dark:text-purple-400 dark:border-purple-400'
                                           : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
                                id="tab-{{ $widgetId }}-{{ $viewKey }}"
                                wire:click="setView('{{ $viewKey }}')" type="button" role="tab"
                                aria-controls="content-{{ $widgetId }}-{{ $viewKey }}"
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
                    'widgetId' => $widgetId . '-' . $activeView, // Pass a more specific ID if needed by template JS
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
            <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-cog mr-2"></i> Widget Settings
            </h4>

            <div class="space-y-5">
                <!-- Template Selection -->
                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                    <label for="widgetActiveViewSelector-{{ $widgetId }}"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Active Template</label>
                    <select id="widgetActiveViewSelector-{{ $widgetId }}" wire:model.live="activeView"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                        @foreach ($availableViews as $key => $details)
                            <option value="{{ $key }}">{{ $details['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Menu Type Selection -->
                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                    <label for="menuType-{{ $widgetId }}"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Menu Type</label>
                    <select id="menuType-{{ $widgetId }}" wire:model.live="menuType"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                        <option value="restaurant">Restaurant</option>
                        <option value="cafeteria">Cafeteria</option>
                        <option value="bar">Bar</option>
                    </select>
                </div>

                <!-- Display Options -->
                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Options</span>
                        <button type="button" class="text-xs text-purple-600 dark:text-purple-400 hover:underline"
                            wire:click="toggleAllDisplayOptions">
                            {{ $showPrices && $showCalories && $showAllergens ? 'Disable All' : 'Enable All' }}
                        </button>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="flex items-center justify-between p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Show Prices</span>
                            <input type="checkbox" wire:model.live="showPrices"
                                class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </label>

                        <label
                            class="flex items-center justify-between p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Show Calories</span>
                            <input type="checkbox" wire:model.live="showCalories"
                                class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </label>

                        <label
                            class="flex items-center justify-between p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Show Allergens</span>
                            <input type="checkbox" wire:model.live="showAllergens"
                                class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </label>
                    </div>
                </div>

                <!-- Currency and Refresh Settings -->
                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                    <div class="space-y-3">
                        <div>
                            <label for="currency-{{ $widgetId }}"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency</label>
                            <input type="text" id="currency-{{ $widgetId }}" wire:model.live="currency"
                                maxlength="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                        </div>

                        <div>
                            <label for="refreshInterval-{{ $widgetId }}"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Refresh Interval
                                (sec)</label>
                            <div class="flex items-center space-x-2">
                                <input type="number" id="refreshInterval-{{ $widgetId }}"
                                    wire:model.live="refreshInterval" min="30" max="3600" step="30"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm">
                                <button type="button" wire:click="resetRefreshInterval"
                                    class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500">
                                    Reset
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Set to 0 to disable auto-refresh
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

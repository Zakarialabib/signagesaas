<div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
    {{-- Widget Header (from BaseWidget) --}}
    <div class="p-4 border-b dark:border-gray-700 shrink-0">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
    </div>

    {{-- Main Content Area: Tabs + Active Template + Settings --}}
    <div class="flex flex-row flex-grow overflow-hidden">
        {{-- Content Side: Tabs + Active Template --}}
        <div class="flex-grow flex flex-col p-4 overflow-y-auto">
            {{-- Loading Indicator --}}
            <div wire:loading.flex wire:target="setView,loadData"
                class="fixed inset-0 bg-white bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 flex items-center justify-center z-50 p-4">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-3"></div>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">
                        @if ($isLoading)
                            Loading
                            {{ $activeView === 'modern-grid' ? 'Modern Grid' : $availableViews[$activeView]['name'] }}...
                        @else
                            Processing your request...
                        @endif
                    </span>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-3">
                        <div class="bg-blue-500 h-2.5 rounded-full animate-pulse" style="width: 70%"></div>
                    </div>
                </div>
            </div>

            {{-- Error Message --}}
            @if ($error)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            {{-- Tab Navigation for different retail views/templates --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700 shrink-0">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="retailWidgetTabs-{{ $widgetId }}" role="tablist">
                    @foreach ($availableViews as $viewKey => $viewDetails)
                        <li class="mr-2" role="presentation">
                            <button
                                class="inline-block p-4 border-b-2 rounded-t-lg focus:outline-none font-semibold
                                       {{ $activeView === $viewKey
                                           ? 'text-indigo-600 border-indigo-600 dark:text-indigo-400 dark:border-indigo-400'
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

            {{-- Content Area for the Active Tab (the selected retail template) --}}
            <div class="flex-grow overflow-y-auto retail-widget-active-template-content">
                 @include($availableViews[$activeView]['view_path'], [
                    'widgetTitle' => $widgetTitle,
                    'products' => $products,
                    'lastUpdated' => $lastUpdated,
                    'showPrice' => $showPrice,
                    'showRating' => $showRating,
                    'showAddToCartButton' => $showAddToCartButton,
                    'currency' => $currency,
                    'gridColumns' => $gridColumns,
                    'error' => $error,
                    'isLoading' => $isLoading,
                    'widgetId' => $widgetId . '-' . $activeView
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
            <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Retail Settings</h4>
            <div class="space-y-4">
                <div>
                    <label for="retailWidgetActiveViewSelector-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Active Template</label>
                    <select id="retailWidgetActiveViewSelector-{{ $widgetId }}" wire:model.live="activeView"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach($availableViews as $key => $details)
                            <option value="{{ $key }}">{{ $details['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="retailDefaultSort-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default Sort Order</label>
                    <select id="retailDefaultSort-{{ $widgetId }}" wire:model.live="defaultSort"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="popularity">Popularity</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="newest">Newest Arrivals</option>
                        <option value="rating">Highest Rating</option>
                    </select>
                </div>

                 <div>
                    <label for="retailGridColumns-{{ $widgetId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grid Columns (for grid views)</label>
                    <select id="retailGridColumns-{{ $widgetId }}" wire:model.live="gridColumns"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="2">2 Columns</option>
                        <option value="3">3 Columns</option>
                        <option value="4">4 Columns</option>
                        <option value="5">5 Columns</option> {{-- Added more options --}}
                        <option value="6">6 Columns</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Options</span>
                    <label class="inline-flex items-center w-full">
                        <input type="checkbox" wire:model.live="showPrice"
                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Prices</span>
                    </label>
                    <label class="inline-flex items-center w-full">
                        <input type="checkbox" wire:model.live="showRating"
                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Ratings</span>
                    </label>
                    <label class="inline-flex items-center w-full">
                        <input type="checkbox" wire:model.live="showAddToCartButton"
                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show 'Add to Cart' (Visual)</span>
                    </label>
                </div>

                <div>
                    <label for="retailCurrency-{{ $widgetId }}"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency Symbol</label>
                    <input type="text" id="retailCurrency-{{ $widgetId }}" wire:model.live="currency" maxlength="5"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label for="retailRefreshInterval-{{ $widgetId }}"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refresh Interval (sec)</label>
                    <input type="number" id="retailRefreshInterval-{{ $widgetId }}" wire:model.live="refreshInterval"
                        min="30" max="3600" step="30"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            </div>
        </div>
    </div>
</div> 
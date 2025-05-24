<div class="h-full flex flex-col p-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg">
    <h2 class="text-3xl font-bold mb-6 shrink-0 text-gray-800 dark:text-white">{{ $widgetTitle }}</h2>

    @if (!empty($error))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ $error }}</p>
        </div>
    @endif

    @if ($isLoading && empty($menu))
        <div class="flex-grow flex items-center justify-center">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-purple-500"></div>
            <p class="ml-3 text-gray-600 dark:text-gray-400">Loading menu...</p>
        </div>
    @elseif (empty($menu))
        <div class="flex-grow flex items-center justify-center">
            <p class="text-gray-600 dark:text-gray-400">No menu items available. Please check back later.</p>
        </div>
    @else
        <div class="flex-grow overflow-y-auto space-y-8 pr-2 custom-scrollbar">
            @foreach($menu as $categoryIndex => $category)
                <section class="mb-8 fade-in" style="animation-delay: {{ $categoryIndex * 0.1 }}s">
                    <h3 class="text-2xl font-semibold text-purple-600 dark:text-purple-400 mb-3 sticky top-0 bg-white dark:bg-gray-800 py-2 z-10">{{ $category['name'] }}</h3>
                    @if(!empty($category['description']))
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $category['description'] }}</p>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($category['items'] as $itemIndex => $item)
                            <article class="menu-item-card bg-gray-50 dark:bg-gray-750 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-4 flex flex-col justify-between fade-in" style="animation-delay: {{ ($categoryIndex * 0.1) + ($itemIndex * 0.05) }}s">
                                <div>
                                    @if(!empty($item['image']))
                                        <img src="{{ filter_var($item['image'], FILTER_VALIDATE_URL) ? $item['image'] : asset('images/menu/' . $item['image']) }}"
                                             alt="{{ $item['name'] }}" class="w-full h-40 object-cover rounded-md mb-3 shadow"
                                             loading="lazy"
                                             onerror="this.style.display='none';">
                                    @endif
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="text-xl font-bold text-gray-800 dark:text-white truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</h4>
                                        @if($showPrices && isset($item['price']))
                                            <span class="text-xl font-bold text-purple-500 dark:text-purple-400 whitespace-nowrap">{{ $currency }}{{ number_format((float)$item['price'], 2) }}</span>
                                        @endif
                                    </div>
                                    @if(!empty($item['description']))
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 min-h-[40px]">{{ $item['description'] }}</p>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs">
                                        @if(isset($item['special']) && $item['special'])
                                            <span class="font-semibold px-2 py-1 bg-yellow-400 text-yellow-800 rounded-full shadow-sm">Special</span>
                                        @endif
                                        @if($showCalories && isset($item['calories']))
                                            <span class="text-gray-500 dark:text-gray-400"><i class="fas fa-fire mr-1 text-orange-500"></i>{{ $item['calories'] }} kcal</span>
                                        @endif
                                        @if($showAllergens && !empty($item['allergens']))
                                            <span class="text-red-500 dark:text-red-400" aria-label="Allergens">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> {{ is_array($item['allergens']) ? implode(', ', $item['allergens']) : $item['allergens'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 col-span-full italic">No items in this category.</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    @endif

    @if($lastUpdated)
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-xs text-right text-gray-500 dark:text-gray-400 shrink-0">
        Last updated: {{ $lastUpdated }}
    </div>
    @endif

    <style>
        .menu-item-card {
            /* Add any specific styles for the card itself */
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; /* Tailwind gray-300 */
            border-radius: 4px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4b5563; /* Tailwind gray-600 */
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af; /* Tailwind gray-400 */
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #374151; /* Tailwind gray-700 */
        }
        .fade-in {
            animation: fadeInAnimation 0.5s ease-out forwards;
            opacity: 0;
        }
        @keyframes fadeInAnimation {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    {{-- Font Awesome should be loaded by the main layout or widget page if icons are used --}}
</div>

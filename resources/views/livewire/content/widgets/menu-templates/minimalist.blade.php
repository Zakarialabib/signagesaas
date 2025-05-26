<div class="h-full flex flex-col p-6 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg minimalist-menu-content">
    <header class="mb-8 shrink-0">
        <h2 class="text-4xl font-light tracking-wider text-center text-gray-800 dark:text-gray-100">{{ $widgetTitle }}</h2>
    </header>

    @if (!empty($error))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ $error }}</p>
        </div>
    @endif

    @if ($isLoading && empty($menu))
        <div class="flex-grow flex items-center justify-center">
            <div class="animate-ping h-3 w-3 bg-gray-500 rounded-full"></div>
            <div class="animate-ping h-3 w-3 bg-gray-500 rounded-full mx-2"></div>
            <div class="animate-ping h-3 w-3 bg-gray-500 rounded-full"></div>
        </div>
    @elseif (empty($menu))
        <div class="flex-grow flex items-center justify-center">
            <p class="text-lg text-gray-500 dark:text-gray-400">Menu currently empty.</p>
        </div>
    @else
        <main class="flex-grow overflow-y-auto space-y-10 pr-2 custom-scrollbar-minimalist">
            @foreach($menu as $categoryIndex => $category)
                <section class="mb-6 fade-in-minimalist" style="animation-delay: {{ $categoryIndex * 0.1 }}s">
                    <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">{{ $category['name'] }}</h3>
                    @if(!empty($category['description']))
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-4 italic">{{ $category['description'] }}</p>
                    @endif
                    <ul class="space-y-3">
                        @forelse($category['items'] as $itemIndex => $item)
                            <li class="flex justify-between items-baseline fade-in-minimalist" style="animation-delay: {{ ($categoryIndex * 0.1) + ($itemIndex * 0.05) }}s">
                                <div>
                                    <span class="text-md text-gray-800 dark:text-gray-200">{{ $item['name'] }}</span>
                                    @if(!empty($item['description']))
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($item['description'], 80) }}</p>
                                    @endif
                                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs">
                                        @if(isset($item['special']) && $item['special'])
                                            <span class="font-semibold text-green-600 dark:text-green-400">Special</span>
                                        @endif
                                        @if($showCalories && isset($item['calories']))
                                            <span class="text-gray-400 dark:text-gray-500">{{ $item['calories'] }} kcal</span>
                                        @endif
                                        @if($showAllergens && !empty($item['allergens']))
                                            <span class="text-red-500 dark:text-red-400">
                                                Allergens: {{ is_array($item['allergens']) ? implode(', ', $item['allergens']) : $item['allergens'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($showPrices && isset($item['price']))
                                    <span class="text-md font-semibold text-gray-900 dark:text-gray-100 whitespace-nowrap ml-4">{{ $currency }}{{ number_format((float)$item['price'], 2) }}</span>
                                @endif
                            </li>
                        @empty
                            <li class="text-sm text-gray-400 dark:text-gray-500 italic">No items in this category.</li>
                        @endforelse
                    </ul>
                </section>
            @endforeach
        </main>
    @endif

    @if($lastUpdated)
    <footer class="mt-auto pt-6 border-t border-gray-200 dark:border-gray-700 text-xs text-center text-gray-400 dark:text-gray-500 shrink-0">
        Last updated: {{ $lastUpdated }}
    </footer>
    @endif
    <style>
        .custom-scrollbar-minimalist::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar-minimalist::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar-minimalist::-webkit-scrollbar-thumb {
            background: #e5e7eb; /* Tailwind gray-200 */
            border-radius: 3px;
        }
        .dark .custom-scrollbar-minimalist::-webkit-scrollbar-thumb {
            background: #374151; /* Tailwind gray-700 */
        }
        .fade-in-minimalist {
            animation: fadeInMinimalistAnimation 0.5s ease-out forwards;
            opacity: 0;
        }
        @keyframes fadeInMinimalistAnimation {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div> 
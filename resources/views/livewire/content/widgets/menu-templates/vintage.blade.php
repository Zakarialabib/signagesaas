<div class="h-full flex flex-col p-6 bg-amber-50 dark:bg-stone-800 text-stone-700 dark:text-amber-100 rounded-lg vintage-menu-content font-serif">
    <header class="text-center mb-8 shrink-0 border-b-4 border-double border-stone-600 dark:border-amber-200 pb-4">
        <h2 class="text-5xl font-bold tracking-wide">{{ $widgetTitle }}</h2>
        <p class="text-sm text-stone-500 dark:text-amber-300 mt-1">~ Est. 1923 ~</p>
    </header>

    @if (!empty($error))
        <div class="bg-red-200 border-l-4 border-red-600 text-red-800 p-4 mb-4 rounded shadow" role="alert">
            <p class="font-bold">An Error Occurred</p>
            <p>{{ $error }}</p>
        </div>
    @endif

    @if ($isLoading && empty($menu))
        <div class="flex-grow flex items-center justify-center">
            <p class="text-2xl text-stone-500 dark:text-amber-300 animate-pulse">Loading Our Exquisite Menu...</p>
        </div>
    @elseif (empty($menu))
        <div class="flex-grow flex items-center justify-center">
            <p class="text-lg text-stone-500 dark:text-amber-300">Our chefs are preparing something special. Menu coming soon!</p>
        </div>
    @else
        <main class="flex-grow overflow-y-auto space-y-10 pr-2 custom-scrollbar-vintage">
            @foreach($menu as $categoryIndex => $category)
                <section class="mb-6 fade-in-vintage" style="animation-delay: {{ $categoryIndex * 0.15 }}s">
                    <h3 class="text-3xl font-semibold text-stone-800 dark:text-amber-50 mb-3 text-center tracking-wider">~ {{ $category['name'] }} ~</h3>
                    @if(!empty($category['description']))
                        <p class="text-xs text-stone-500 dark:text-amber-400 mb-5 text-center italic">{{ $category['description'] }}</p>
                    @endif
                    <ul class="space-y-4">
                        @forelse($category['items'] as $itemIndex => $item)
                            <li class="pb-3 border-b border-dotted border-stone-400 dark:border-amber-300/50 fade-in-vintage flex justify-between items-start" style="animation-delay: {{ ($categoryIndex * 0.15) + ($itemIndex * 0.07) }}s">
                                <div>
                                    <span class="text-xl text-stone-800 dark:text-amber-100 font-medium">{{ $item['name'] }}</span>
                                    @if(isset($item['special']) && $item['special'])
                                        <span class="ml-2 text-xs font-bold text-red-700 dark:text-red-400 tracking-widest">SPECIAL!</span>
                                    @endif
                                    @if(!empty($item['description']))
                                        <p class="text-sm text-stone-600 dark:text-amber-200/80 my-1">{{ $item['description'] }}</p>
                                    @endif
                                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-stone-500 dark:text-amber-300/70">
                                        @if($showCalories && isset($item['calories']))
                                            <span>{{ $item['calories'] }} Calories</span>
                                        @endif
                                        @if($showAllergens && !empty($item['allergens']))
                                            <span>
                                                Contains: {{ is_array($item['allergens']) ? implode(', ', $item['allergens']) : $item['allergens'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($showPrices && isset($item['price']))
                                    <span class="text-xl font-semibold text-stone-800 dark:text-amber-50 whitespace-nowrap ml-6">{{ $currency }}{{ number_format((float)$item['price'], 2) }}</span>
                                @endif
                            </li>
                        @empty
                            <li class="text-sm text-stone-500 dark:text-amber-400 italic">Awaiting culinary creations for this section.</li>
                        @endforelse
                    </ul>
                </section>
            @endforeach
        </main>
    @endif

    @if($lastUpdated)
    <footer class="mt-auto pt-6 border-t-2 border-stone-500 dark:border-amber-200/70 text-xs text-center text-stone-500 dark:text-amber-400 shrink-0">
        Menu Updated: {{ $lastUpdated }}
    </footer>
    @endif
    <style>
        .custom-scrollbar-vintage::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar-vintage::-webkit-scrollbar-track {
            background: rgba(120, 113, 108, 0.1); /* stone-500 with opacity */
        }
        .dark .custom-scrollbar-vintage::-webkit-scrollbar-track {
            background: rgba(217, 119, 6, 0.1); /* amber-500 with opacity */
        }
        .custom-scrollbar-vintage::-webkit-scrollbar-thumb {
            background: #a8a29e; /* stone-400 */
            border-radius: 4px;
            border: 2px solid #f5f5f4; /* bg-amber-50 or similar vintage paper */
        }
        .dark .custom-scrollbar-vintage::-webkit-scrollbar-thumb {
            background: #d69e2e; /* amber-600 */
            border: 2px solid #292524; /* dark bg-stone-800 */
        }
        .fade-in-vintage {
            animation: fadeInVintageAnimation 0.7s ease-out forwards;
            opacity: 0;
        }
        @keyframes fadeInVintageAnimation {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .vintage-menu-content {
            /* You could add a subtle paper texture background image here */
            /* background-image: url('/path/to/vintage-paper-texture.jpg'); */
        }
    </style>
</div> 
<div class="h-full flex flex-col text-white bg-gray-850 rounded-lg overflow-hidden modern-dark-content">
    {{-- Header part of the Modern Dark template --}}
    <div class="bg-gray-900 p-3 flex items-center justify-between shrink-0">
        <div class="flex items-center">
            <div class="flex space-x-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
            </div>
            <div class="text-white text-sm ml-4 font-semibold">{{ $widgetTitle }}</div>
        </div>
        @if ($lastUpdated)
            <div class="text-xs text-gray-400">Updated: {{ $lastUpdated }}</div>
        @endif
    </div>

    {{-- Main scrollable content for Modern Dark template --}}
    <div class="flex-grow overflow-y-auto p-6 space-y-8 custom-scrollbar">
        @if (!empty($error))
            <div class="bg-red-700 border border-red-600 text-white px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error:</strong>
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @endif

        @if ($isLoading && empty($menu))
            <div class="flex-grow flex items-center justify-center h-full">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-yellow-400"></div>
                <p class="ml-3 text-gray-300">Loading menu...</p>
            </div>
        @elseif (empty($menu))
            <div class="flex-grow flex items-center justify-center h-full">
                <p class="text-gray-400 text-center py-8">Menu information is currently unavailable.</p>
            </div>
        @else
            @foreach ($menu as $categoryIndex => $category)
                <section class="mb-8 fade-in-modern" style="animation-delay: {{ $categoryIndex * 0.15 }}s" x-data="{ expanded: true }">
                    <div class="flex justify-between items-center mb-3 cursor-pointer" @click="expanded = !expanded">
                        <h3 class="text-2xl font-bold text-yellow-400 hover:text-yellow-300 transition-colors">{{ $category['name'] }}</h3>
                        <i class="fas text-yellow-400" :class="expanded ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                    </div>
                    @if (!empty($category['description']))
                        <p class="text-sm text-gray-300 mb-4" x-show="expanded" x-transition.opacity>{{ $category['description'] }}</p>
                    @endif

                    <div x-show="expanded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($category['items'] as $itemIndex => $item)
                            @php
                                $isFeaturedItem = isset($item['special']) && $item['special'] === true;
                                $itemAnimationDelay = ($categoryIndex * 0.15) + ($itemIndex * 0.08);
                            @endphp
                            <article class="menu-item-card-modern rounded-lg p-4 text-white {{ $isFeaturedItem ? 'bg-gradient-to-br from-red-700 via-orange-600 to-red-800 shadow-orange-500/30 featured-item-pulse' : 'bg-gray-700 shadow-lg' }} hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 fade-in-modern" style="animation-delay: {{ $itemAnimationDelay }}s">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold {{ $isFeaturedItem ? 'text-xl text-yellow-300' : 'text-lg' }}">{{ $item['name'] }}</h4>
                                        @if($isFeaturedItem && !empty($item['sub_heading']))
                                            <p class="text-yellow-200 text-sm">{{ $item['sub_heading'] }}</p>
                                        @endif
                                    </div>
                                    @if($showPrices && isset($item['price']))
                                        <div class="{{ $isFeaturedItem ? 'bg-yellow-400' : 'bg-gray-800' }} text-{{ $isFeaturedItem ? 'black' : 'white' }} font-bold px-3 py-1 rounded-full text-sm whitespace-nowrap shadow-md">{{ $currency }}{{ number_format((float)$item['price'], 2) }}</div>
                                    @endif
                                </div>
                                @if(!empty($item['image']))
                                     <img src="{{ filter_var($item['image'], FILTER_VALIDATE_URL) ? $item['image'] : asset('images/menu/' . $item['image']) }}"
                                         alt="{{ $item['name'] }}" class="w-full h-32 object-cover rounded-md my-3 shadow-lg"
                                         loading="lazy" onerror="this.style.display='none';">
                                @endif
                                @if(!empty($item['description']))
                                    <p class="text-gray-300 text-sm mt-2 {{ empty($item['image']) ? '' : 'min-h-[40px]' }}">{{ $item['description'] }}</p>
                                @endif
                                <div class="flex flex-wrap mt-3 space-x-2">
                                    @if($showCalories && isset($item['calories']))
                                        <span class="bg-black bg-opacity-40 px-2 py-1 rounded-full text-xs nutrition-tag hover-grow-sm" title="{{ $item['calories'] }} Calories"><i class="fas fa-fire mr-1 text-orange-400"></i> {{ $item['calories'] }} Cal</span>
                                    @endif
                                    @if($showAllergens && !empty($item['allergens']))
                                        @php $allergensList = is_array($item['allergens']) ? $item['allergens'] : [$item['allergens']]; @endphp
                                        @foreach($allergensList as $allergen)
                                            <span class="bg-black bg-opacity-40 px-2 py-1 rounded-full text-xs nutrition-tag hover-grow-sm" title="Contains {{ Str::title($allergen) }}"><i class="fas fa-exclamation-triangle mr-1 text-red-400"></i> {{ Str::title($allergen) }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </article>
                        @empty
                            <p class="text-gray-400 italic col-span-full">No items available in this category.</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        @endif
    </div>

    <style>
        .modern-dark-content ::-webkit-scrollbar {
            width: 8px;
        }
        .modern-dark-content ::-webkit-scrollbar-track {
            background: #1f2937; /* bg-gray-800 */
        }
        .modern-dark-content ::-webkit-scrollbar-thumb {
            background: #4b5563; /* bg-gray-600 */
            border-radius: 4px;
        }
        .modern-dark-content ::-webkit-scrollbar-thumb:hover {
            background: #6b7280; /* bg-gray-500 */
        }
        .fade-in-modern {
            animation: fadeInModernAnimation 0.6s ease-out forwards;
            opacity: 0;
        }
        @keyframes fadeInModernAnimation {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .featured-item-pulse {
            animation: pulseFeatured 2.5s infinite ease-in-out;
        }
        @keyframes pulseFeatured {
            0%, 100% { box-shadow: 0 0 10px 0px rgba(251, 146, 60, 0.5); transform: scale(1); }
            50% { box-shadow: 0 0 20px 8px rgba(251, 146, 60, 0.3); transform: scale(1.02); }
        }
        .nutrition-tag {
            transition: transform 0.2s ease-out;
        }
        .hover-grow-sm:hover {
            transform: scale(1.1);
        }
    </style>

    {{-- Alpine.js is expected to be available from the main layout or widget page --}}
    {{-- Font Awesome icons are used, ensure it's available --}}
</div>

<div class="p-4 h-full overflow-y-auto">
    @if($data['settings']['template_style'] === 'grid')
        <!-- Grid Layout -->
        <div class="grid grid-cols-2 gap-4">
            @foreach($data['categories'] ?? [] as $category)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3 text-center border-b border-gray-200 dark:border-gray-600 pb-2">
                        {{ $category['name'] }}
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($category['items'] ?? [] as $item)
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</h4>
                                    
                                    @if(($data['settings']['show_descriptions'] ?? true) && !empty($item['description']))
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $item['description'] }}</p>
                                    @endif
                                    
                                    <div class="flex items-center space-x-2 mt-1">
                                        @if(($data['settings']['show_calories'] ?? false) && !empty($item['calories']))
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item['calories'] }} cal</span>
                                        @endif
                                        
                                        @if(($data['settings']['show_allergens'] ?? false) && !empty($item['allergens']))
                                            <span class="text-xs px-1 py-0.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded">
                                                {{ implode(', ', $item['allergens']) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($data['settings']['show_prices'] ?? true)
                                    <div class="ml-3 text-right">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            ${{ number_format($item['price'], 2) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- List Layout -->
        <div class="space-y-6">
            @foreach($data['categories'] ?? [] as $category)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4 text-center border-b border-gray-200 dark:border-gray-600 pb-2">
                        {{ $category['name'] }}
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($category['items'] ?? [] as $item)
                            <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-700 pb-3 last:border-b-0">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</h4>
                                    
                                    @if(($data['settings']['show_descriptions'] ?? true) && !empty($item['description']))
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $item['description'] }}</p>
                                    @endif
                                    
                                    <div class="flex items-center space-x-3 mt-2">
                                        @if(($data['settings']['show_calories'] ?? false) && !empty($item['calories']))
                                            <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                                <x-heroicon-o-fire class="w-3 h-3 mr-1" />
                                                {{ $item['calories'] }} cal
                                            </span>
                                        @endif
                                        
                                        @if(($data['settings']['show_allergens'] ?? false) && !empty($item['allergens']))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($item['allergens'] as $allergen)
                                                    <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">
                                                        {{ $allergen }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($data['settings']['show_prices'] ?? true)
                                    <div class="ml-4 text-right">
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                            ${{ number_format($item['price'], 2) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    @if(empty($data['categories']))
        <div class="flex items-center justify-center h-full">
            <div class="text-center">
                <x-heroicon-o-document-text class="mx-auto h-12 w-12 text-gray-400" />
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No menu items to display</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Select content with menu data to see preview</p>
            </div>
        </div>
    @endif
</div>
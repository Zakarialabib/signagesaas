<div class="p-4 h-full overflow-y-auto">
    <div class="grid gap-4" style="grid-template-columns: repeat({{ $data['settings']['columns'] ?? 2 }}, 1fr);">
        @foreach($data['products'] ?? [] as $product)
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if(($data['settings']['show_images'] ?? true) && !empty($product['image']))
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100 dark:bg-gray-700">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" 
                             class="w-full h-32 object-cover">
                    </div>
                @endif
                
                <div class="p-3">
                    <h3 class="font-medium text-gray-900 dark:text-white text-sm mb-1">{{ $product['name'] }}</h3>
                    
                    @if(!empty($product['description']))
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2 line-clamp-2">{{ $product['description'] }}</p>
                    @endif
                    
                    @if($data['settings']['show_prices'] ?? true)
                        <div class="flex items-center justify-between">
                            @if(!empty($product['original_price']) && $product['original_price'] != $product['price'])
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                                        {{ $data['settings']['currency'] ?? 'USD' }} {{ number_format($product['price'], 2) }}
                                    </span>
                                    <span class="text-xs text-gray-500 line-through">
                                        {{ $data['settings']['currency'] ?? 'USD' }} {{ number_format($product['original_price'], 2) }}
                                    </span>
                                </div>
                            @else
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $data['settings']['currency'] ?? 'USD' }} {{ number_format($product['price'], 2) }}
                                </span>
                            @endif
                            
                            @if(!empty($product['stock_status']))
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $product['stock_status'] === 'in_stock' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $product['stock_status'] === 'in_stock' ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            @endif
                        </div>
                    @endif
                    
                    @if(!empty($product['category']))
                        <div class="mt-2">
                            <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full">
                                {{ $product['category'] }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    
    @if(empty($data['products']))
        <div class="flex items-center justify-center h-full">
            <div class="text-center">
                <x-heroicon-o-shopping-bag class="mx-auto h-12 w-12 text-gray-400" />
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No products to display</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Select content with product data to see preview</p>
            </div>
        </div>
    @endif
</div>
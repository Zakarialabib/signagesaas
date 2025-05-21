<div>
    <div class="bg-slate-50 p-4 rounded-lg shadow-md h-full flex flex-col">
        @if($isLoading)
            <div class="flex justify-center items-center h-full">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
                <span class="ml-3 text-gray-600">Loading products...</span>
            </div>
        @elseif($error)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative h-full" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @else
            <div class="mb-6 text-center">
                <h2 class="text-3xl font-bold text-gray-800">{{ $widgetTitle }}</h2>
            </div>
    
            @if(!empty($products))
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $gridColumns > 0 ? $gridColumns : 3 }} gap-6 flex-grow">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col transition-transform hover:scale-105 duration-300">
                            <div class="relative">
                                <img src="{{ $product['image'] ?? $defaultProductImage }}" alt="{{ $product['name'] }}" class="w-full h-48 object-cover">
                                @if(!empty($product['promotion_badge']))
                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">{{ $product['promotion_badge'] }}</span>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $product['name'] }}</h3>
                                <p class="text-gray-600 text-sm mb-3 flex-grow">{{ Str::limit($product['description'] ?? 'No description available.', 100) }}</p>
                                <div class="mt-auto">
                                    @if(isset($product['sale_price']) && !empty($product['sale_price']) && $product['sale_price'] < $product['price'])
                                        <p class="text-2xl font-bold text-red-600">
                                            {{ $currencySymbol }}{{ number_format((float)$product['sale_price'], 2) }}
                                            <span class="text-sm text-gray-500 line-through ml-2">{{ $currencySymbol }}{{ number_format((float)$product['price'], 2) }}</span>
                                        </p>
                                    @else
                                        <p class="text-2xl font-bold text-gray-800">{{ $currencySymbol }}{{ number_format((float)$product['price'], 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-10 flex-grow">
                    <p>No products to display at the moment.</p>
                </div>
            @endif
    
            @if(!empty($footerPromoText))
                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <p class="text-md text-gray-700 italic">{{ $footerPromoText }}</p>
                </div>
            @endif
        @endif
    </div>
</div>

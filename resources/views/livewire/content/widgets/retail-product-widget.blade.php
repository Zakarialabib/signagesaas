<div class="antialiased text-gray-900">
    <div class="bg-neutral-100 p-6 rounded-xl shadow-2xl h-full flex flex-col">
        @if($isLoading)
            <div class="flex flex-col justify-center items-center h-full">
                <svg class="animate-spin h-16 w-16 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="mt-4 text-lg font-medium text-gray-700">Loading awesome products...</span>
            </div>
        @elseif($error)
            <div class="bg-red-50 border border-red-300 text-red-700 px-6 py-4 rounded-lg relative h-full flex flex-col justify-center items-center shadow-md">
                <div class="flex items-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <strong class="font-bold text-xl">Oops! Something went wrong.</strong>
                </div>
                <span class="block sm:inline text-md">{{ $error }}</span>
                <p class="text-sm text-red-600 mt-2">Please try refreshing the page or contact support if the issue persists.</p>
            </div>
        @else
            <div class="mb-8 text-center">
                <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-500 to-red-500 py-2">{{ $widgetTitle }}</h2>
            </div>
    
            @if(!empty($products))
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $gridColumns > 0 ? $gridColumns : 3 }} gap-8 flex-grow">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-xl overflow-hidden flex flex-col transition-all duration-300 ease-in-out hover:shadow-2xl hover:-translate-y-1">
                            <div class="relative group">
                                <img src="{{ $product['image'] ?? $defaultProductImage }}" alt="{{ $product['name'] }}" class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-110">
                                @if(!empty($product['promotion_badge']))
                                    <span class="absolute top-3 right-3 bg-gradient-to-r from-pink-500 to-red-500 text-white text-sm font-bold px-4 py-1.5 rounded-full uppercase tracking-wide shadow-md">{{ $product['promotion_badge'] }}</span>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <button class="text-white bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-lg font-semibold text-lg transform hover:scale-105 transition-transform duration-200">View Details</button>
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $product['name'] }}</h3>
                                <p class="text-gray-700 text-base mb-4 flex-grow leading-relaxed">{{ Str::limit($product['description'] ?? 'No description available.', 120) }}</p>
                                <div class="mt-auto pt-4 border-t border-gray-200">
                                    @if(isset($product['sale_price']) && !empty($product['sale_price']) && (float)$product['sale_price'] < (float)$product['price'])
                                        <div class="flex items-baseline justify-between">
                                            <p class="text-3xl font-extrabold text-red-600">
                                                {{ $currencySymbol }}{{ number_format((float)$product['sale_price'], 2) }}
                                            </p>
                                            <p class="text-lg text-gray-500 line-through ml-2">
                                                {{ $currencySymbol }}{{ number_format((float)$product['price'], 2) }}
                                            </p>
                                        </div>
                                        @if(isset($product['discount_percentage']) && !empty($product['discount_percentage']))
                                          <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-0.5 rounded-full mt-1 inline-block">{{ $product['discount_percentage'] }}% OFF</span>
                                        @endif
                                    @else
                                        <p class="text-3xl font-extrabold text-gray-800">{{ $currencySymbol }}{{ number_format((float)$product['price'], 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-600 py-16 flex-grow flex flex-col items-center justify-center bg-white rounded-lg shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75A4.5 4.5 0 015.25 6H4.5a2.25 2.25 0 00-2.25 2.25v1.5a2.25 2.25 0 002.25 2.25h.75m8.25-6H19.5a2.25 2.25 0 012.25 2.25v1.5a2.25 2.25 0 01-2.25 2.25h-.75m-1.5-6l-3.86-3.86A4.5 4.5 0 009.75 6H6a4.5 4.5 0 00-4.5 4.5v6A4.5 4.5 0 006 21h12a4.5 4.5 0 004.5-4.5v-6a4.5 4.5 0 00-4.5-4.5H14.25m-4.5 0L9 10.5m3.75-3.75L15 10.5m-3.75-3.75V3.75m0 16.5V18" />
                    </svg>
                    <p class="text-xl font-semibold">No Products Available</p>
                    <p class="text-sm text-gray-500 mt-1">Check back soon for new arrivals!</p>
                </div>
            @endif
    
            @if(!empty($footerPromoText))
                <div class="mt-10 pt-8 border-t-2 border-dashed border-gray-300 text-center">
                    <p class="text-lg text-purple-700 font-medium italic tracking-wide">{{ $footerPromoText }}</p>
                </div>
            @endif
        @endif
    </div>
</div>

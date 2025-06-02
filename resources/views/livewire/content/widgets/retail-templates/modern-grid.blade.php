<div>
    <div
        class="h-full flex flex-col p-4 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white rounded-lg modern-grid-retail-content">
        <header class="mb-6 shrink-0">
            <h2 class="text-3xl font-semibold text-center text-gray-800 dark:text-gray-100">{{ $widgetTitle }}</h2>
        </header>

        @if (!empty($error))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow" role="alert">
                <p class="font-bold">Error Displaying Products</p>
                <p>{{ $error }}</p>
            </div>
        @endif

        @if ($isLoading && empty($products))
            <div class="flex-grow flex items-center justify-center">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500 mb-3"></div>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">Fetching latest products...</p>
                </div>
            </div>
        @elseif (empty($products))
            <div class="flex-grow flex items-center justify-center">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Products Available</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">There are currently no products to display.
                        Please check back later.</p>
                </div>
            </div>
        @else
            <div
                class="flex-grow overflow-y-auto pr-2 custom-scrollbar-modern-grid 
            grid 
            {{ $gridColumns == 2 ? 'grid-cols-1 sm:grid-cols-2' : '' }}
            {{ $gridColumns == 3 ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' : '' }}
            {{ $gridColumns == 4 ? 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4' : '' }}
            {{ $gridColumns == 5 ? 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5' : '' }}
            {{ $gridColumns == 6 ? 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6' : '' }}
            gap-6">
                @foreach ($products as $productIndex => $product)
                    <article
                        class="product-card bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 flex flex-col overflow-hidden fade-in-grid-item"
                        style="animation-delay: {{ $productIndex * 0.07 }}s" x-data="{ quickViewOpen: false }">
                        <div class="relative">
                            <img src="{{ $product['image'] ?? 'https://placehold.co/600x400/cccccc/969696?text=No+Image' }}"
                                alt="{{ $product['name'] }}"
                                class="w-full h-56 object-cover product-image transform transition-transform duration-500 hover:scale-110"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/600x400/cccccc/969696?text=Image+Error'; this.classList.add('image-error');">
                            @if (isset($product['original_price']) && $product['original_price'] > $product['price'])
                                @php
                                    $discount =
                                        (($product['original_price'] - $product['price']) /
                                            $product['original_price']) *
                                        100;
                                @endphp
                                <span
                                    class="absolute top-3 right-3 bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">{{ round($discount) }}%
                                    OFF</span>
                            @elseif(in_array('New', $product['tags'] ?? []))
                                <span
                                    class="absolute top-3 right-3 bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">NEW</span>
                            @endif
                            <div
                                class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-30 p-2 flex justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                <button @click="quickViewOpen = true"
                                    class="text-white text-sm bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg shadow-md transition-colors"><i
                                        class="fas fa-eye mr-2"></i>Quick View</button>
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 truncate"
                                title="{{ $product['name'] }}">{{ Str::limit($product['name'], 50) }}</h3>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 flex-grow min-h-[60px]">
                                {{ Str::limit($product['description'], 80) }}</p>

                            @if ($showPrice && isset($product['price']))
                                <div class="flex items-baseline justify-between mb-3">
                                    <span
                                        class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $currency }}{{ number_format((float) $product['price'], 2) }}</span>
                                    @if (isset($product['original_price']) && $product['original_price'] > $product['price'])
                                        <span
                                            class="text-sm text-gray-400 dark:text-gray-500 line-through ml-2">{{ $currency }}{{ number_format((float) $product['original_price'], 2) }}</span>
                                    @endif
                                </div>
                            @endif

                            @if (!empty($product['tags']))
                                <div class="mb-3 flex flex-wrap gap-1">
                                    @foreach (collect($product['tags'])->take(3) as $tag)
                                        <span
                                            class="text-xs bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            @if ($showAddToCartButton)
                                <button
                                    class="mt-auto w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-opacity-75">
                                    <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                                </button>
                            @endif
                        </div>

                        {{-- Quick View Modal (Alpine.js) --}}
                        <div x-show="quickViewOpen" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-90" @click.away="quickViewOpen = false"
                            @keydown.escape.window="quickViewOpen = false"
                            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[100] p-4 modern-grid-retail-content"
                            x-cloak>
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                                <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        {{ $product['name'] }}</h4>
                                    <button @click="quickViewOpen = false"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i
                                            class="fas fa-times text-xl"></i></button>
                                </div>
                                <div class="p-6 flex-grow overflow-y-auto space-y-4">
                                    <img src="{{ $product['image'] ?? 'https://placehold.co/600x400/cccccc/969696?text=No+Image' }}"
                                        alt="{{ $product['name'] }}"
                                        class="w-full h-64 object-contain rounded-md mb-4">
                                    <p class="text-gray-700 dark:text-gray-300">{{ $product['description'] }}</p>
                                    @if ($showPrice && isset($product['price']))
                                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $currency }}{{ number_format((float) $product['price'], 2) }}
                                            @if (isset($product['original_price']) && $product['original_price'] > $product['price'])
                                                )
                                                <span
                                                    class="text-lg text-gray-400 dark:text-gray-500 line-through ml-2">{{ $currency }}{{ number_format((float) $product['original_price'], 2) }}</span>
                                            @endif
                                        </p>
                                    @endif
                                    @if (!empty($product['features']))
                                        <div>
                                            <h5 class="font-semibold mb-1 text-gray-800 dark:text-gray-200">Key
                                                Features:</h5>
                                            <ul
                                                class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                @foreach ($product['features'] as $feature)
                                                    <li>{{ $feature }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock Status: <span
                                            class="font-semibold {{ $product['stock_status'] === 'In Stock' ? 'text-green-500' : 'text-red-500' }}">{{ $product['stock_status'] }}</span>
                                    </p>
                                </div>
                                <div
                                    class="p-4 border-t dark:border-gray-700 flex justify-end space-x-3 bg-gray-50 dark:bg-gray-850">
                                    <button @click="quickViewOpen = false"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Close</button>
                                    @if ($showAddToCartButton)
                                        <button
                                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"><i
                                                class="fas fa-cart-plus mr-2"></i>Add to Cart</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        @if ($lastUpdated)
            <footer
                class="mt-auto pt-6 border-t border-gray-200 dark:border-gray-700 text-xs text-center text-gray-500 dark:text-gray-400 shrink-0">
                Prices and availability last updated: {{ $lastUpdated }}
            </footer>
        @endif

        <style>
            .custom-scrollbar-modern-grid::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            .custom-scrollbar-modern-grid::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar-modern-grid::-webkit-scrollbar-thumb {
                background: #a5b4fc;
                /* Tailwind indigo-300 */
                border-radius: 4px;
            }

            .dark .custom-scrollbar-modern-grid::-webkit-scrollbar-thumb {
                background: #6366f1;
                /* Tailwind indigo-500 */
            }

            .custom-scrollbar-modern-grid::-webkit-scrollbar-thumb:hover {
                background: #818cf8;
                /* Tailwind indigo-400 */
            }

            .dark .custom-scrollbar-modern-grid::-webkit-scrollbar-thumb:hover {
                background: #4f46e5;
                /* Tailwind indigo-600 */
            }

            .fade-in-grid-item {
                animation: fadeInGridItemAnimation 0.5s ease-out forwards;
                opacity: 0;
            }

            @keyframes fadeInGridItemAnimation {
                from {
                    opacity: 0;
                    transform: translateY(20px) scale(0.98);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .product-image.image-error {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #e5e7eb;
                /* bg-gray-200 */
            }

            .product-card:hover .product-image {
                /* Add a subtle zoom or effect on hover if desired beyond scale-110 */
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
        {{-- Ensure Font Awesome is loaded (e.g., via CDN in main layout or if using Blade Icons) --}}
    </div>
</div>

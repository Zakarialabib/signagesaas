<div>
    <div
        class="h-full flex flex-col p-4 bg-white dark:bg-gray-850 text-gray-900 dark:text-white rounded-lg detailed-list-retail-content">
        <header class="mb-6 shrink-0 pb-4 border-b dark:border-gray-700">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $widgetTitle }} - Detailed View</h2>
        </header>

        @if (!empty($error))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow" role="alert">
                <p class="font-bold">Error Displaying Product List</p>
                <p>{{ $error }}</p>
            </div>
        @endif

        @if ($isLoading && empty($products))
            <div class="flex-grow flex items-center justify-center">
                <div class="flex flex-col items-center">
                    <svg class="animate-spin h-10 w-10 text-indigo-500 mb-3" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-md">Loading product details...</p>
                </div>
            </div>
        @elseif (empty($products))
            <div class="flex-grow flex items-center justify-center">
                <div class="text-center p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <i class="fas fa-box-open fa-3x text-gray-400 dark:text-gray-500 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Product List is Empty</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">We couldn't find any products to display at
                        the moment.</p>
                </div>
            </div>
        @else
            <div class="flex-grow overflow-y-auto space-y-6 pr-2 custom-scrollbar-detailed-list">
                @foreach ($products as $productIndex => $product)
                    <article
                        class="product-list-item bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 flex flex-col sm:flex-row overflow-hidden fade-in-list-item"
                        style="animation-delay: {{ $productIndex * 0.1 }}s">
                        <div class="sm:w-1/3 md:w-1/4 shrink-0">
                            <img src="{{ $product['image'] ?? 'https://placehold.co/400x300/cccccc/969696?text=No+Image' }}"
                                alt="{{ $product['name'] }}"
                                class="w-full h-48 sm:h-full object-cover product-list-image" loading="lazy"
                                onerror="this.src='https://placehold.co/400x300/cccccc/969696?text=Img+Error';">
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white"
                                    title="{{ $product['name'] }}">{{ $product['name'] }}</h3>
                                @if (isset($product['original_price']) && $product['original_price'] > $product['price'])
                                    @php
                                        $discount =
                                            (($product['original_price'] - $product['price']) /
                                                $product['original_price']) *
                                            100;
                                    @endphp
                                    <span
                                        class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full ml-2 shrink-0">SAVE
                                        {{ round($discount) }}%</span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $product['description'] }}</p>

                            @if (!empty($product['features']))
                                <div class="mb-3">
                                    <h5 class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">
                                        Key Features:</h5>
                                    <ul
                                        class="list-disc list-inside text-xs text-gray-600 dark:text-gray-300 space-y-0.5">
                                        @foreach (collect($product['features'])->take(3) as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                        @if (count($product['features']) > 3)
                                            <li>And more...</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <div
                                class="mt-auto flex flex-col sm:flex-row sm:items-center sm:justify-between pt-3 border-t dark:border-gray-700">
                                @if ($showPrice && isset($product['price']))
                                    <div class="mb-3 sm:mb-0">
                                        <span
                                            class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $currency }}{{ number_format((float) $product['price'], 2) }}</span>
                                        @if (isset($product['original_price']) && $product['original_price'] > $product['price'])
                                            <span
                                                class="text-sm text-gray-400 dark:text-gray-500 line-through ml-2">{{ $currency }}{{ number_format((float) $product['original_price'], 2) }}</span>
                                        @endif
                                    </div>
                                @endif

                                @if ($showAddToCartButton)
                                    <button
                                        class="w-full sm:w-auto bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-opacity-75">
                                        <i class="fas fa-shopping-cart mr-2"></i>View Details
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        @if ($lastUpdated)
            <footer
                class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700 text-xs text-center text-gray-500 dark:text-gray-400 shrink-0">
                Listing updated: {{ $lastUpdated }}
            </footer>
        @endif

        <style>
            .custom-scrollbar-detailed-list::-webkit-scrollbar {
                width: 7px;
            }

            .custom-scrollbar-detailed-list::-webkit-scrollbar-track {
                background: #f3f4f6;
                /* Tailwind gray-100 */
            }

            .dark .custom-scrollbar-detailed-list::-webkit-scrollbar-track {
                background: #1f2937;
                /* Tailwind gray-800 */
            }

            .custom-scrollbar-detailed-list::-webkit-scrollbar-thumb {
                background: #9ca3af;
                /* Tailwind gray-400 */
                border-radius: 3px;
            }

            .dark .custom-scrollbar-detailed-list::-webkit-scrollbar-thumb {
                background: #4b5563;
                /* Tailwind gray-600 */
            }

            .custom-scrollbar-detailed-list::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
                /* Tailwind gray-500 */
            }

            .fade-in-list-item {
                animation: fadeInListItemAnimation 0.6s ease-out forwards;
                opacity: 0;
            }

            @keyframes fadeInListItemAnimation {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .product-list-image {
                transition: transform 0.3s ease-out;
            }

            .product-list-item:hover .product-list-image {
                transform: scale(1.03);
            }
        </style>
    </div>
</div>

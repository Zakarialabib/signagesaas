<div>
    <div
        class="h-full flex flex-col p-6 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg minimalist-showcase-retail-content">
        <header class="mb-8 shrink-0 text-center">
            <h2 class="text-4xl font-extralight tracking-wider text-gray-700 dark:text-gray-200">{{ $widgetTitle }}</h2>
            @if ($lastUpdated)
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Updated: {{ $lastUpdated }}</p>
            @endif
        </header>

        @if (!empty($error))
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-md relative mb-6 shadow-sm"
                role="alert">
                <strong class="font-medium">Oops! Something went wrong.</strong>
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @endif

        @if ($isLoading && empty($products))
            <div class="flex-grow flex items-center justify-center">
                <div class="space-y-3 text-center">
                    <div class="flex justify-center items-center space-x-2">
                        <div class="animate-pulse h-3 w-3 bg-gray-400 dark:bg-gray-600 rounded-full"></div>
                        <div class="animate-pulse h-3 w-3 bg-gray-400 dark:bg-gray-600 rounded-full delay-150"></div>
                        <div class="animate-pulse h-3 w-3 bg-gray-400 dark:bg-gray-600 rounded-full delay-300"></div>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">Curating our finest selection...</p>
                </div>
            </div>
        @elseif (empty($products))
            <div
                class="flex-grow flex flex-col items-center justify-center text-center p-8 bg-gray-50 dark:bg-gray-800/30 rounded-lg">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300">Our Showcase is Empty</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No featured products are available right now.
                    Please visit again soon!</p>
            </div>
        @else
            <main class="flex-grow overflow-y-auto space-y-12 pr-2 custom-scrollbar-minimalist-showcase">
                @foreach ($products as $productIndex => $product)
                    <section
                        class="product-showcase-item grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-center fade-in-showcase-item"
                        style="animation-delay: {{ $productIndex * 0.15 }}s">
                        <div
                            class="{{ $productIndex % 2 === 0 ? 'md:order-1' : 'md:order-2' }} aspect-square rounded-lg overflow-hidden shadow-2xl group relative">
                            <img src="{{ $product['image'] ?? 'https://placehold.co/800x800/e2e8f0/94a3b8?text=Product' }}"
                                alt="{{ $product['name'] }}"
                                class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/800x800/e2e8f0/94a3b8?text=Error';">
                            @if (isset($product['original_price']) && $product['original_price'] > $product['price'])
                                )
                                <div
                                    class="absolute top-4 left-4 bg-rose-500 text-white text-sm font-semibold px-3 py-1.5 rounded-full shadow-lg">
                                    SALE</div>
                            @endif
                        </div>
                        <div class="{{ $productIndex % 2 === 0 ? 'md:order-2' : 'md:order-1' }} py-4">
                            <h3 class="text-3xl font-light text-gray-800 dark:text-gray-100 mb-3 tracking-tight">
                                {{ $product['name'] }}</h3>

                            <p class="text-gray-600 dark:text-gray-400 mb-5 leading-relaxed text-sm">
                                {{ Str::limit($product['description'], 150) }}</p>

                            @if (!empty($product['features']) && count($product['features']) > 0)
                                <div class="mb-5">
                                    <ul class="space-y-1.5">
                                        @foreach (collect($product['features'])->take(2) as $feature)
                                            <li class="flex items-center text-xs text-gray-600 dark:text-gray-300">
                                                <i
                                                    class="fas fa-check-circle text-green-500 dark:text-green-400 mr-2"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($showPrice && isset($product['price']))
                                <div class="mb-6">
                                    <span
                                        class="text-4xl font-bold text-gray-800 dark:text-white">{{ $currency }}{{ number_format((float) $product['price'], 2) }}</span>
                                    @if (isset($product['original_price']) && $product['original_price'] > $product['price'])
                                        <span
                                            class="text-lg text-gray-400 dark:text-gray-500 line-through ml-2">{{ $currency }}{{ number_format((float) $product['original_price'], 2) }}</span>
                                    @endif
                                </div>
                            @endif

                            @if ($showAddToCartButton)
                                <button
                                    class="w-full sm:w-auto bg-gray-800 dark:bg-indigo-600 hover:bg-gray-700 dark:hover:bg-indigo-500 text-white font-medium py-3 px-8 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-indigo-400 focus:ring-opacity-50">
                                    Discover More
                                </button>
                            @endif
                        </div>
                    </section>
                    @if (!$loop->last)
                        <hr class="my-8 border-gray-200 dark:border-gray-700/50">
                    @endif
                @endforeach
            </main>
        @endif

        <style>
            .custom-scrollbar-minimalist-showcase::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar-minimalist-showcase::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar-minimalist-showcase::-webkit-scrollbar-thumb {
                background: #d1d5db;
                /* Tailwind gray-300 */
                border-radius: 3px;
            }

            .dark .custom-scrollbar-minimalist-showcase::-webkit-scrollbar-thumb {
                background: #4b5563;
                /* Tailwind gray-600 */
            }

            .fade-in-showcase-item {
                animation: fadeInShowcaseItemAnimation 0.8s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
                opacity: 0;
            }

            @keyframes fadeInShowcaseItemAnimation {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </div>
</div>

<div>
    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Information Widgets Section -->
            <div class="mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-10 sm:mb-12">Information Widgets</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 lg:gap-12 lg:grid-cols-2">
                    @foreach ($informationWidgets as $widget)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                            <div class="h-2 bg-gradient-to-r {{ $widget['gradient'] }}"></div>
                            <div class="p-8 md:p-10">
                                <div class="flex items-center mb-6">
                                    <div @class([
                                        'w-12 h-12 flex items-center justify-center rounded-lg',
                                        'bg-blue-100 dark:bg-blue-900' => str_contains($widget['gradient'], 'blue'),
                                        'text-blue-600 dark:text-blue-400' => str_contains(
                                            $widget['gradient'],
                                            'blue'),
                                    ])>
                                        <x-icon :name="$widget['category']->getIcon()" class="w-6 h-6" />
                                    </div>
                                    <h3 class="ml-4 text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-white">
                                        {{ $widget['title'] }}</h3>
                                </div>
                                
                                <!-- Widget Preview Section -->
                                <div class="mb-6 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    @if($widget['category'] === \App\Enums\TemplateCategory::WEATHER)
                                        <div class="text-center">
                                            <div class="text-5xl sm:text-6xl font-bold text-gray-900 dark:text-white mb-2">{{ $widget['preview']['temperature'] }}</div>
                                            <div class="text-xl text-gray-600 dark:text-gray-300 mb-4">{{ $widget['preview']['condition'] }}</div>
                                            <div class="text-lg text-gray-500 dark:text-gray-400">{{ $widget['preview']['location'] }}</div>
                                            <div class="flex justify-between mt-4">
                                                @foreach($widget['preview']['forecast'] as $day)
                                                    <div class="text-center">
                                                        <div class="text-lg font-medium text-gray-600 dark:text-gray-300">{{ $day['day'] }}</div>
                                                        <x-icon :name="$day['icon']" class="w-6 h-6 mx-auto my-2 text-blue-500" />
                                                        <div class="text-lg text-gray-600 dark:text-gray-300">{{ $day['temp'] }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($widget['category'] === \App\Enums\TemplateCategory::CLOCK)
                                        <div class="space-y-4">
                                            <div class="text-center font-mono text-5xl sm:text-6xl text-gray-900 dark:text-white">
                                                {{ $widget['preview']['time'] }}
                                            </div>
                                            <div class="text-center text-lg text-gray-600 dark:text-gray-300">
                                                {{ $widget['preview']['date'] }}
                                            </div>
                                            <div class="flex justify-center space-x-2 text-base text-gray-500 dark:text-gray-400">
                                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 rounded">{{ $widget['preview']['timezone'] }}</span>
                                            </div>
                                        </div>
                                    @elseif($widget['category'] === \App\Enums\TemplateCategory::RSS_FEED)
                                        <div class="space-y-3">
                                            @foreach($widget['preview']['feeds'] as $feed)
                                                <div>
                                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ $feed['title'] }}</h4>
                                                    @foreach($feed['items'] as $item)
                                                        <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                                                            <span class="text-base text-gray-600 dark:text-gray-300">{{ $item['title'] }}</span>
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $item['time'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">{{ $widget['description'] }}</p>
                                <ul class="space-y-4">
                                    @foreach ($widget['features'] as $feature)
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-blue-500 dark:text-blue-400 mt-1 flex-shrink-0"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span
                                                class="ml-3 text-lg text-gray-600 dark:text-gray-300">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-8">
                                    <a href="{{ route('tenant.tv.widget', ['category' => $widget['category']->value]) }}"
                                        class="inline-flex items-center text-lg text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        View Demo
                                        <svg class="ml-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Social Widgets Section -->
            <div class="mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-10 sm:mb-12">Social & News Widgets</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 lg:gap-12 lg:grid-cols-2">
                    @foreach ($socialWidgets as $widget)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                            <div class="h-2 bg-gradient-to-r {{ $widget['gradient'] }}"></div>
                            <div class="p-8 md:p-10">
                                <div class="flex items-center mb-6">
                                    <div @class([
                                        'w-12 h-12 flex items-center justify-center rounded-lg',
                                        'bg-pink-100 dark:bg-pink-900' => str_contains($widget['gradient'], 'pink'),
                                        'text-pink-600 dark:text-pink-400' => str_contains(
                                            $widget['gradient'],
                                            'pink'),
                                    ])>
                                        <x-icon :name="$widget['category']->getIcon()" class="w-6 h-6" />
                                    </div>
                                    <h3 class="ml-4 text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-white">
                                        {{ $widget['title'] }}</h3>
                                </div>

                                <!-- Widget Preview Section -->
                                <div class="mb-6 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    @if($widget['category'] === \App\Enums\TemplateCategory::SOCIAL_MEDIA)
                                        <div class="space-y-4">
                                            @foreach($widget['preview']['posts'] as $post)
                                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                                    <div class="flex items-center mb-2">
                                                        <x-icon :name="$post['platform']" class="w-5 h-5 text-gray-500" />
                                                        <span class="ml-2 text-lg font-medium text-gray-900 dark:text-white">{{ $post['author'] }}</span>
                                                    </div>
                                                    @if(isset($post['image']))
                                                        <div class="aspect-video bg-gray-200 dark:bg-gray-600 rounded mb-2"></div>
                                                    @endif
                                                    <p class="text-base text-gray-600 dark:text-gray-300">{{ $post['content'] ?? '' }}</p>
                                                    <div class="flex items-center mt-2 text-base text-gray-500 dark:text-gray-400">
                                                        <span class="flex items-center">
                                                            <x-icon name="heart" class="w-4 h-4 mr-1" />
                                                            {{ number_format($post['engagement']['likes']) }}
                                                        </span>
                                                        <span class="flex items-center ml-4">
                                                            <x-icon name="share" class="w-4 h-4 mr-1" />
                                                            {{ number_format($post['engagement']['shares'] ?? $post['engagement']['comments']) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($widget['category'] === \App\Enums\TemplateCategory::NEWS)
                                        <div class="space-y-4">
                                            @foreach($widget['preview']['articles'] as $article)
                                                <div class="border-l-4 border-pink-500 pl-4">
                                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-1">
                                                        <span class="font-medium text-pink-600 dark:text-pink-400">{{ $article['source'] }}</span>
                                                        <span class="mx-2">•</span>
                                                        <span>{{ $article['time'] }}</span>
                                                    </div>
                                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $article['headline'] }}</h4>
                                                    <span class="inline-block mt-1 px-2 py-1 text-sm font-medium bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200 rounded">
                                                        {{ $article['category'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($widget['category'] === \App\Enums\TemplateCategory::ANNOUNCEMENTS)
                                        <div class="space-y-4">
                                            @foreach($widget['preview']['announcements'] as $announcement)
                                                <div @class([
                                                    'border-l-4 p-4 rounded-r-lg',
                                                    'border-red-500 bg-red-50 dark:bg-red-900/20' => $announcement['priority'] === 'high',
                                                    'border-gray-300 bg-gray-50 dark:bg-gray-700' => $announcement['priority'] === 'normal',
                                                ])>
                                                    <div class="flex items-center mb-2">
                                                        <x-icon :name="$announcement['icon']" @class([
                                                            'w-5 h-5 mr-2',
                                                            'text-red-600 dark:text-red-400' => $announcement['priority'] === 'high',
                                                            'text-gray-600 dark:text-gray-400' => $announcement['priority'] === 'normal',
                                                        ]) />
                                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $announcement['title'] }}</h4>
                                                    </div>
                                                    <p class="text-base text-gray-600 dark:text-gray-300">{{ $announcement['content'] }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">{{ $widget['description'] }}</p>
                                <ul class="space-y-4">
                                    @foreach ($widget['features'] as $feature)
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-pink-500 dark:text-pink-400 mt-1 flex-shrink-0"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span
                                                class="ml-3 text-lg text-gray-600 dark:text-gray-300">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-8">
                                    <a href="{{ route('tenant.tv.widget', ['category' => $widget['category']->value]) }}"
                                        class="inline-flex items-center text-lg text-pink-600 dark:text-pink-400 hover:text-pink-800 dark:hover:text-pink-300">
                                        View Demo
                                        <svg class="ml-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Business Widgets Section -->
            <div>
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-10 sm:mb-12">Business Widgets</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 lg:gap-12 lg:grid-cols-2">
                    @foreach ($businessWidgets as $widget)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                            <div class="h-2 bg-gradient-to-r {{ $widget['gradient'] }}"></div>
                            <div class="p-8 md:p-10">
                                <div class="flex items-center mb-6">
                                    <div @class([
                                        'w-12 h-12 flex items-center justify-center rounded-lg',
                                        'bg-emerald-100 dark:bg-emerald-900' => str_contains(
                                            $widget['gradient'],
                                            'emerald'),
                                        'text-emerald-600 dark:text-emerald-400' => str_contains(
                                            $widget['gradient'],
                                            'emerald'),
                                    ])>
                                        <x-icon :name="$widget['category']->getIcon()" class="w-6 h-6" />
                                    </div>
                                    <h3 class="ml-4 text-2xl sm:text-3xl font-semibold text-gray-900 dark:text-white">
                                        {{ $widget['title'] }}</h3>
                                </div>

                                <!-- Widget Preview Section -->
                                <div class="mb-6 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    @if($widget['category'] === \App\Enums\TemplateCategory::MENU)
                                        <div class="space-y-6">
                                            @foreach($widget['preview']['categories'] as $category)
                                                <div>
                                                    <h4 class="text-xl font-medium text-gray-900 dark:text-white mb-4">{{ $category['name'] }}</h4>
                                                    <div class="space-y-4">
                                                        @foreach($category['items'] as $item)
                                                            <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <h5 class="text-lg font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</h5>
                                                                        <p class="text-base text-gray-600 dark:text-gray-300 mt-1">{{ $item['description'] }}</p>
                                                                    </div>
                                                                    <span class="text-xl font-medium text-emerald-600 dark:text-emerald-400">{{ $item['price'] }}</span>
                                                                </div>
                                                                <div class="flex items-center mt-2 space-x-2">
                                                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $item['calories'] }}</span>
                                                                    @foreach($item['allergens'] as $allergen)
                                                                        <span class="px-2 py-1 text-sm bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">
                                                                            {{ ucfirst($allergen) }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($widget['category'] === \App\Enums\TemplateCategory::RETAIL)
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach($widget['preview']['products'] as $product)
                                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                                    <div class="aspect-square bg-gray-200 dark:bg-gray-600 rounded mb-3"></div>
                                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</h4>
                                                    <div class="flex items-baseline mt-2">
                                                        @if(isset($product['sale_price']))
                                                            <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $product['sale_price'] }}</span>
                                                            <span class="ml-2 text-base text-gray-500 line-through">{{ $product['price'] }}</span>
                                                        @else
                                                            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ $product['price'] }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center justify-between mt-2">
                                                        <span @class([
                                                            'text-sm px-2 py-1 rounded',
                                                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' => $product['status'] === 'In Stock',
                                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $product['status'] === 'Low Stock',
                                                        ])>
                                                            {{ $product['status'] }}
                                                        </span>
                                                        <div class="flex items-center text-yellow-400">
                                                            <x-icon name="star" class="w-4 h-4" />
                                                            <span class="ml-1 text-sm text-gray-600 dark:text-gray-300">{{ $product['rating'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($widget['category'] === \App\Enums\TemplateCategory::CALENDAR)
                                        <div class="space-y-6">
                                            <div class="space-y-4">
                                                @foreach($widget['preview']['events'] as $event)
                                                    <div class="flex items-start p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                                        <div class="flex-shrink-0 w-16 text-center">
                                                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ explode(' - ', $event['time'])[0] }}</div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $event['title'] }}</h4>
                                                            <div class="mt-1 text-base">
                                                                <span class="text-gray-600 dark:text-gray-300">{{ $event['location'] }}</span>
                                                                <span class="mx-2 text-gray-300 dark:text-gray-600">•</span>
                                                                <span @class([
                                                                    'px-2 py-1 text-base rounded',
                                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $event['category'] === 'Meeting',
                                                                    'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' => $event['category'] === 'Event',
                                                                ])>
                                                                    {{ $event['category'] }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto flex items-center">
                                                            <x-icon name="users" class="w-4 h-4 text-gray-400" />
                                                            <span class="ml-1 text-base text-gray-600 dark:text-gray-300">{{ $event['attendees'] }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Upcoming</h4>
                                                <div class="flex justify-between">
                                                    @foreach($widget['preview']['upcoming'] as $day)
                                                        <div class="text-center">
                                                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $day['date'] }}</div>
                                                            <div class="mt-1 text-base text-emerald-600 dark:text-emerald-400">{{ $day['count'] }} events</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">{{ $widget['description'] }}</p>
                                <ul class="space-y-4">
                                    @foreach ($widget['features'] as $feature)
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400 mt-1 flex-shrink-0"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span
                                                class="ml-3 text-lg text-gray-600 dark:text-gray-300">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-8">
                                    <a href="{{ route('tenant.tv.widget', ['category' => $widget['category']->value]) }}"
                                        class="inline-flex items-center text-lg text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300">
                                        View Demo
                                        <svg class="ml-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="p-4 h-full overflow-y-auto">
    @if(!empty($data['articles']))
        <div class="space-y-4">
            @foreach(array_slice($data['articles'], 0, $data['settings']['max_articles'] ?? 5) as $index => $article)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden
                            {{ $index === 0 ? 'border-l-4 border-l-blue-500' : '' }}">
                    @if($index === 0 && !empty($article['image']))
                        <!-- Featured Article with Image -->
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100 dark:bg-gray-700">
                            <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
                                 class="w-full h-32 object-cover">
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                @if(!empty($article['category']))
                                    <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full mb-2">
                                        {{ ucfirst($article['category']) }}
                                    </span>
                                @endif
                                
                                <h3 class="font-semibold text-gray-900 dark:text-white text-sm leading-tight
                                           {{ $index === 0 ? 'text-base' : 'text-sm' }}">
                                    {{ $article['title'] }}
                                </h3>
                            </div>
                            
                            @if(!empty($article['source']))
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 flex-shrink-0">
                                    {{ $article['source'] }}
                                </span>
                            @endif
                        </div>
                        
                        @if(!empty($article['description']) && ($index === 0 || strlen($article['description']) < 100))
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                {{ $article['description'] }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <div class="flex items-center space-x-3">
                                @if(!empty($article['published_at']))
                                    <span class="flex items-center">
                                        <x-heroicon-o-clock class="w-3 h-3 mr-1" />
                                        {{ $article['published_at'] }}
                                    </span>
                                @endif
                                
                                @if(!empty($article['author']))
                                    <span class="flex items-center">
                                        <x-heroicon-o-user class="w-3 h-3 mr-1" />
                                        {{ $article['author'] }}
                                    </span>
                                @endif
                            </div>
                            
                            @if(!empty($article['url']))
                                <span class="flex items-center text-blue-600 dark:text-blue-400">
                                    <x-heroicon-o-arrow-top-right-on-square class="w-3 h-3 mr-1" />
                                    Read more
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- News Footer -->
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <div class="flex items-center space-x-4">
                    <span class="flex items-center">
                        <x-heroicon-o-newspaper class="w-3 h-3 mr-1" />
                        {{ count($data['articles']) }} articles
                    </span>
                    
                    @if(!empty($data['settings']['source']))
                        <span class="flex items-center">
                            <x-heroicon-o-rss class="w-3 h-3 mr-1" />
                            {{ ucfirst($data['settings']['source']) }}
                        </span>
                    @endif
                </div>
                
                <span class="flex items-center">
                    <x-heroicon-o-arrow-path class="w-3 h-3 mr-1" />
                    Updates every {{ ($data['settings']['refresh_interval'] ?? 300) / 60 }}min
                </span>
            </div>
        </div>
    @else
        <!-- No Articles State -->
        <div class="flex items-center justify-center h-full">
            <div class="text-center">
                <x-heroicon-o-newspaper class="mx-auto h-12 w-12 text-gray-400" />
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No news articles available</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Configure news source and category to see articles</p>
                
                @if(!empty($data['settings']))
                    <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">
                        <p>Source: {{ ucfirst($data['settings']['source'] ?? 'general') }}</p>
                        <p>Category: {{ ucfirst($data['settings']['category'] ?? 'general') }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
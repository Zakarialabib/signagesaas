<div>
    <div wire:poll.{{ $refreshInterval }}s="fetchFeed"
    class="w-full h-full bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-4 overflow-y-auto">
    <h3 class="text-xl font-semibold mb-3 border-b pb-2 border-gray-300 dark:border-gray-700">
        Latest News
        @if (str_contains($feedUrl, 'bbc'))
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/BBC_News_2022_%28Boxed%29.svg/240px-BBC_News_2022_%28Boxed%29.svg.png" alt="BBC News" class="inline h-6 ml-2">
        @endif
    </h3>

    @if (!empty($error))
        <div class="text-red-500 bg-red-100 dark:bg-red-900 dark:text-red-300 p-3 rounded">
            <p><strong>Error:</strong> {{ $error }}</p>
            <p class="text-sm mt-1">Feed URL: {{ $feedUrl }}</p>
        </div>
    @elseif (empty($feedItems))
        <p class="text-gray-500 dark:text-gray-400">No items to display, or feed is loading...</p>
    @else
        <ul class="space-y-3">
            @foreach ($feedItems as $item)
                <li class="pb-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer"
                        class="text-lg font-medium text-blue-600 dark:text-blue-400 hover:underline">
                        {{ $item['title'] }}
                    </a>
                    @if (!empty($item['description']))
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 leading-snug">
                            {{ Str::limit($item['description'], 150) }}
                        </p>
                    @endif
                    @if (!empty($item['pubDate']))
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($item['pubDate'])->diffForHumans() }}
                             ({{ \Carbon\Carbon::parse($item['pubDate'])->format('M d, Y H:i') }})
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
</div>

<div>
    <div class="h-full bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Social Feed</h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">#trending</span>
        </div>
        
        <div class="space-y-4">
            @foreach($posts as $post)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="flex items-start">
                        <div class="text-2xl mr-3">{{ $post['avatar'] }}</div>
                        <div class="flex-1">
                            <div class="flex items-center mb-1">
                                <span class="font-medium text-gray-800 dark:text-white mr-2">@{{ $post['user'] }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post['time'] }}</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 mb-2">{{ $post['content'] }}</p>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span class="mr-3">❤️ {{ $post['likes'] }} likes</span>
                                <span>💬 {{ $post['comments'] }} comments</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </div>
</div>
<x-base-widget>

@section('content')
    <div class="space-y-4">
        @foreach($newsItems as $item)
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                    {{ $item['title'] }}
                </h3>
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium">{{ $item['source'] }}</span>
                    <span class="mx-2">&bull;</span>
                    <span>{{ $item['time'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
        Last updated {{ $lastUpdated }}
    </div>

    @if($error)
        <div class="mt-4 p-4 bg-red-50 dark:bg-red-900 rounded-lg">
            <p class="text-sm text-red-600 dark:text-red-200">{{ $error }}</p>
        </div>
    @endif
@endsection

@section('settings')
    <div class="space-y-4">
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
            <select id="category" wire:model="category" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <option value="general">General</option>
                <option value="business">Business</option>
                <option value="technology">Technology</option>
                <option value="sports">Sports</option>
                <option value="entertainment">Entertainment</option>
                <option value="health">Health</option>
                <option value="science">Science</option>
            </select>
        </div>

        <div>
            <label for="maxItems" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Items</label>
            <input type="number" id="maxItems" wire:model="maxItems" min="1" max="10" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
        </div>

        <div>
            <label for="refreshInterval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refresh Interval (seconds)</label>
            <input type="number" id="refreshInterval" wire:model="refreshInterval" min="60" max="3600" step="60" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
        </div>
    </div>
@endsection
</x-base-widget>
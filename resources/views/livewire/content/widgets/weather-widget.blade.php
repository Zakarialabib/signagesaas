<x-base-widget>
    @section('content')
        <div class="flex items-center justify-center">
            <div class="text-center">
                <div class="text-5xl mb-4">
                    @switch(true)
                        @case(str_contains(strtolower($weather), 'sunny'))
                            ☀️
                        @break

                        @case(str_contains(strtolower($weather), 'cloudy'))
                            ⛅
                        @break

                        @case(str_contains(strtolower($weather), 'rain'))
                            🌧️
                        @break

                        @case(str_contains(strtolower($weather), 'snow'))
                            🌨️
                        @break

                        @default
                            🌡️
                    @endswitch
                </div>
                <div class="text-2xl font-medium text-gray-900 dark:text-white">{{ $weather }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $location }}</div>
            </div>
        </div>
    @endsection

    @section('settings')
        <div class="space-y-4">
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                <input type="text" id="location" wire:model.live="location"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
            <div>
                <label for="apiKey" class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Key</label>
                <input type="password" id="apiKey" wire:model.live="apiKey"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
            <div>
                <label for="refreshInterval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refresh
                    Interval (seconds)</label>
                <input type="number" id="refreshInterval" wire:model.live="refreshInterval" min="60" step="60"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
        </div>
    @endsection

</x-base-widget>

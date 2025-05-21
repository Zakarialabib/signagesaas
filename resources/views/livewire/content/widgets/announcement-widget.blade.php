<x-base-widget>
    @section('content')
        <div x-data="{
            currentIndex: 0,
            announcements: @js($announcements),
            scrollDirection: @js($scrollDirection),
            scrollSpeed: @js($scrollSpeed),
            enableScrolling: @js($enableScrolling),
            init() {
                if (this.enableScrolling && this.announcements.length > 1) {
                    this.startScrolling();
                }
            },
            startScrolling() {
                setInterval(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.announcements.length;
                }, this.scrollSpeed * 1000);
            }
        }" class="relative overflow-hidden"
            :class="{
                'h-[300px]': scrollDirection === 'vertical',
                'h-[100px]': scrollDirection === 'horizontal'
            }">
            <div class="transition-transform duration-1000 ease-in-out"
                :class="{
                    'space-y-4': scrollDirection === 'vertical',
                    'flex space-x-8': scrollDirection === 'horizontal'
                }"
                :style="scrollDirection === 'vertical'
                    ?
                    `transform: translateY(-${currentIndex * 100}%)` :
                    `transform: translateX(-${currentIndex * 100}%)`">
                @foreach ($announcements as $announcement)
                    <div class="p-4 rounded-lg"
                        :class="{
                            'bg-yellow-50 dark:bg-yellow-900': '{{ $announcement['type'] }}'
                            === 'warning',
                            'bg-blue-50 dark:bg-blue-900': '{{ $announcement['type'] }}'
                            === 'info',
                            'bg-gray-50 dark:bg-gray-900': '{{ $announcement['type'] }}'
                            === 'notice'
                        }">
                        <div class="flex items-center mb-2">
                            <div class="flex-shrink-0">
                                @switch($announcement['type'])
                                    @case('warning')
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @break

                                    @case('info')
                                        <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @break

                                    @default
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                @endswitch
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium"
                                    :class="{
                                        'text-yellow-800 dark:text-yellow-200': '{{ $announcement['type'] }}'
                                        === 'warning',
                                        'text-blue-800 dark:text-blue-200': '{{ $announcement['type'] }}'
                                        === 'info',
                                        'text-gray-800 dark:text-gray-200': '{{ $announcement['type'] }}'
                                        === 'notice'
                                    }">
                                    {{ $announcement['title'] }}
                                </h3>
                                <div class="mt-1">
                                    <p class="text-sm"
                                        :class="{
                                            'text-yellow-700 dark:text-yellow-300': '{{ $announcement['type'] }}'
                                            === 'warning',
                                            'text-blue-700 dark:text-blue-300': '{{ $announcement['type'] }}'
                                            === 'info',
                                            'text-gray-700 dark:text-gray-300': '{{ $announcement['type'] }}'
                                            === 'notice'
                                        }">
                                        {{ $announcement['content'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            Last updated {{ $lastUpdated }}
        </div>
    @endsection

    @section('settings')
        <div class="space-y-4">
            <div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="enableScrolling" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600">
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Enable Scrolling</span>
                </label>
            </div>

            <div>
                <label for="scrollDirection" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Scroll
                    Direction</label>
                <select id="scrollDirection" wire:model="scrollDirection"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="vertical">Vertical</option>
                    <option value="horizontal">Horizontal</option>
                </select>
            </div>

            <div>
                <label for="scrollSpeed" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Scroll Speed
                    (seconds)</label>
                <input type="number" id="scrollSpeed" wire:model="scrollSpeed" min="1" max="30"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>

            <div>
                <label for="maxItems" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Items</label>
                <input type="number" id="maxItems" wire:model="maxItems" min="1" max="10"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>

            <div>
                <label for="refreshInterval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refresh
                    Interval (seconds)</label>
                <input type="number" id="refreshInterval" wire:model="refreshInterval" min="60" max="3600"
                    step="60"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
        </div>
    @endsection
</x-base-widget>

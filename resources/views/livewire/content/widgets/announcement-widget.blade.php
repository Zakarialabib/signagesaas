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
                    <div class="p-6 rounded-xl shadow-md"
                        :class="{
                            'bg-yellow-100 dark:bg-yellow-800 border-l-4 border-yellow-500': '{{ $announcement['type'] }}'
                            === 'warning',
                            'bg-blue-100 dark:bg-blue-800 border-l-4 border-blue-500': '{{ $announcement['type'] }}'
                            === 'info',
                            'bg-gray-100 dark:bg-gray-800 border-l-4 border-gray-500': '{{ $announcement['type'] }}'
                            === 'notice',
                            'bg-green-100 dark:bg-green-800 border-l-4 border-green-500': '{{ $announcement['type'] }}'
                            === 'meeting' // Added meeting type styling
                        }">
                        <div class="flex items-start mb-3">
                            <div class="flex-shrink-0">
                                @switch($announcement['type'])
                                    @case('warning')
                                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @break

                                    @case('info')
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @break

                                    @default
                                        <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                @endswitch
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold leading-6"
                                    :class="{
                                        'text-yellow-900 dark:text-yellow-100': '{{ $announcement['type'] }}'
                                        === 'warning',
                                        'text-blue-900 dark:text-blue-100': '{{ $announcement['type'] }}'
                                        === 'info',
                                        'text-gray-900 dark:text-gray-100': '{{ $announcement['type'] }}'
                                        === 'notice',
                                        'text-green-900 dark:text-green-100': '{{ $announcement['type'] }}'
                                        === 'meeting' // Added meeting type styling
                                    }">
                                    {{ $announcement['title'] }}
                                </h3>
                                <div class="mt-2 text-sm"
                                    :class="{
                                        'text-yellow-800 dark:text-yellow-200': '{{ $announcement['type'] }}'
                                        === 'warning',
                                        'text-blue-800 dark:text-blue-200': '{{ $announcement['type'] }}'
                                        === 'info',
                                        'text-gray-800 dark:text-gray-200': '{{ $announcement['type'] }}'
                                        === 'notice',
                                        'text-green-800 dark:text-green-200': '{{ $announcement['type'] }}'
                                        === 'meeting' // Added meeting type styling
                                    }">
                                    @if (isset($announcement['description']))
                                        <p>{{ $announcement['description'] }}</p>
                                    @endif
                                    @if (isset($announcement['content']))
                                        <p>{{ $announcement['content'] }}</p>
                                    @endif
                                    @if (isset($announcement['time']))
                                        <p class="mt-1 font-medium">Time: {{ $announcement['time'] }}</p>
                                    @endif
                                    @if (isset($announcement['location']))
                                        <p class="mt-1 font-medium">Location: {{ $announcement['location'] }}</p>
                                    @endif
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

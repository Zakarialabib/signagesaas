{{-- Zone Content Scheduler Component --}}
<div class="zone-scheduler">
    <div x-data="{ showScheduler: false }">
        {{-- Schedule Overview --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Content Schedule</h3>
                <div class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($schedule as $index => $item)
                        <div class="py-4 flex items-center justify-between">
                            <div>
                                @php
                                    $content = $this->availableContent->firstWhere('id', $item['content_id']);
                                @endphp
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $content?->name ?? 'Unknown Content' }}
                                </p>
                                <div class="mt-1 flex items-center space-x-2 text-xs text-gray-500">
                                    <span>{{ $item['start_time'] }} - {{ $item['end_time'] }}</span>
                                    <span>•</span>
                                    <span>{{ implode(', ', array_map(fn($day) => ucfirst($day), $item['days'])) }}</span>
                                </div>
                            </div>
                            <button type="button" wire:click="removeScheduleItem({{ $index }})"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                <x-heroicon-m-trash class="h-5 w-5" />
                            </button>
                        </div>
                    @empty
                        <div class="py-4 text-center text-sm text-gray-500">
                            No scheduled content
                        </div>
                    @endforelse
                </div>

                {{-- Add Schedule Button --}}
                <div class="mt-4">
                    <button type="button" @click="showScheduler = true"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <x-heroicon-m-calendar-days class="h-4 w-4 mr-1.5" />
                        Schedule Content
                    </button>
                </div>
            </div>
        </div>

        {{-- Schedule Form Modal --}}
        <div x-show="showScheduler" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showScheduler" @click="showScheduler = false" 
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div x-show="showScheduler"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Schedule Content</h3>
                        <div class="mt-4 space-y-4">
                            {{-- Content Selection --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                                <select wire:model="selectedContentId"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                    <option value="">Select content...</option>
                                    @foreach($this->availableContent as $content)
                                        <option value="{{ $content->id }}">{{ $content->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Time Range --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                                    <input type="time" wire:model="startTime"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                                    <input type="time" wire:model="endTime"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                </div>
                            </div>

                            {{-- Days Selection --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Days</label>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="days" value="{{ $day }}"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ ucfirst(substr($day, 0, 3)) }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="button" wire:click="addScheduleItem"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                            Add Schedule
                        </button>
                        <button type="button" @click="showScheduler = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

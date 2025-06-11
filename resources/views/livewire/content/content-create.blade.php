<div>
    <x-modal wire:model="createContentModal" id="create-content-modal" title="Create New Content" maxWidth="4xl"
        x-on:keydown.escape.window="$wire.closeModal()">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Create New Content</h1>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <form wire:submit="save" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3"> <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content
                                Name</label>
                            <div class="mt-1">
                                <input type="text" wire:model="name" id="name"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="screen_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Screen</label>
                            <div class="mt-1">
                                <select wire:model="screen_id" id="screen_id"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                                    <option value="">Select Screen</option>
                                    @foreach ($screens as $screen)
                                        <option value="{{ $screen->id }}">{{ $screen->name }}
                                            ({{ $screen->device->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('screen_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="zone_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Zone (Optional)</label>
                            <div class="mt-1">
                                <select wire:model="zone_id" id="zone_id"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                                    <option value="">Select Zone</option>
                                    @foreach ($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('zone_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <div class="mt-1">
                                <textarea wire:model="description" id="description" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md"></textarea>
                            </div>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content
                                Type</label>
                            <div class="mt-1">
                                <select wire:model.live="type" id="type"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                                    @foreach ($contentTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <div class="mt-1">
                                <select wire:model="status" id="status"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration
                                (seconds)</label>
                            <div class="mt-1">
                                <input type="number" wire:model="duration" id="duration" min="5" max="300"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                            </div>
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display
                                Order</label>
                            <div class="mt-1">
                                <input type="number" wire:model="order" id="order" min="0"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                            </div>
                            @error('order')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                                Date</label>
                            <div class="mt-1">
                                <input type="date" wire:model="start_date" id="start_date"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                            </div>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                            <div class="mt-1">
                                <input type="date" wire:model="end_date" id="end_date"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                            </div>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content type specific fields -->
                        <div class="sm:col-span-6">
                            <div class="mt-1 border-t border-gray-200 dark:border-gray-700 pt-5">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Content Details</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Please provide the content details based on the selected type.</p>
                            </div>

                            @if ($type === \App\Enums\ContentType::IMAGE->value)
                                <div class="mt-4">
                                    <label for="image_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image File</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                                <label for="image_file" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                    <span>Upload a file</span>
                                                    <input id="image_file" wire:model="image_file" type="file" class="sr-only">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, WEBP up to 10MB</p>
                                        </div>
                                    </div>
                                    @error('image_file') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                            @elseif ($type === \App\Enums\ContentType::VIDEO->value || $type === \App\Enums\ContentType::URL->value)
                                <div class="mt-4">
                                    <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL</label>
                                    <div class="mt-1">
                                        <input type="text" wire:model="url" id="url"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md"
                                            placeholder="https://example.com/video.mp4">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        @if ($type === \App\Enums\ContentType::VIDEO->value)
                                            Enter the URL to a video file (MP4, WebM) or a video streaming service (YouTube, Vimeo)
                                        @else
                                            Enter the URL to be displayed in an iframe
                                        @endif
                                    </p>
                                    @error('url') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                            @elseif ($type === \App\Enums\ContentType::HTML->value || $type === \App\Enums\ContentType::CUSTOM->value)
                                <div class="mt-4">
                                    <label for="html_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">HTML Content</label>
                                    <div class="mt-1">
                                        <textarea wire:model="html_content" id="html_content" rows="10"
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md font-mono"
                                            placeholder="<div>Your HTML content here</div>"></textarea>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the HTML code to be rendered on the screen</p>
                                    @error('html_content') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                            @elseif ($type === \App\Enums\ContentType::RSS->value)
                                 <div class="mt-4">
                                    <label for="feed_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">RSS Feed URL</label>
                                    <input type="text" wire:model="feed_url" id="feed_url" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100" placeholder="https://example.com/feed.xml">
                                    @error('feed_url') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            @elseif ($type === \App\Enums\ContentType::WEATHER->value)
                                <div class="mt-4">
                                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                    <input type="text" wire:model="location" id="location" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100" placeholder="e.g., London, UK">
                                    @error('location') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            @elseif ($type === \App\Enums\ContentType::PRODUCT_LIST->value || $type === \App\Enums\ContentType::MENU->value)
                                <div class="sm:col-span-6 mt-4 py-4 border-t border-gray-200 dark:border-gray-700">
                                    {{-- Pass content ID if editing, null if creating --}}
                                    <livewire:content.product-list-editor :contentId="null" />
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                            Cancel
                        </button>
                        @if ($type !== \App\Enums\ContentType::PRODUCT_LIST->value && $type !== \App\Enums\ContentType::MENU->value)
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Content
                            </button>
                        @else
                             <button type="button" disabled
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-400 cursor-not-allowed focus:outline-none">
                                (Save via Product List Editor Above)
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </x-modal>
</div>

<div>
    <x-modal wire:model="editContentModal" id="edit-content-modal" title="Edit Content" maxWidth="4xl">
        @if ($content)
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit Content:
                        {{ $content->name }}</h1>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form wire:submit="updateContent" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content
                                    Name</label>
                                <div class="mt-1">
                                    <input type="text" wire:model="name" id="name"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
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
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                        <option value="">Select Screen</option>
                                        @foreach ($screens as $screen)
                                            <option value="{{ $screen->id }}">{{ $screen->name }}
                                                ({{ $screen->device->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('screen_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-6">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <div class="mt-1">
                                    <textarea wire:model="description" id="description" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300"></textarea>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="type"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content
                                    Type</label>
                                <div class="mt-1">
                                    <select wire:model.live="type" id="type"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
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
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <div class="mt-1">
                                    <select wire:model="status" id="status"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
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
                                <label for="duration"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration
                                    (seconds)</label>
                                <div class="mt-1">
                                    <input type="number" wire:model="duration" id="duration" min="5"
                                        max="300"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                @error('duration')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="order"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display
                                    Order</label>
                                <div class="mt-1">
                                    <input type="number" wire:model="order" id="order" min="0"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="start_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                                    Date</label>
                                <div class="mt-1">
                                    <input type="datetime-local" wire:model="start_date" id="start_date"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="end_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                                    Date</label>
                                <div class="mt-1">
                                    <input type="datetime-local" wire:model="end_date" id="end_date"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Content type specific fields -->
                            <div class="sm:col-span-6">
                                <div class="mt-1 border-t border-gray-200 dark:border-gray-700 pt-5">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Content
                                        Details</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update the content details
                                        based on the
                                        selected type.</p>
                                </div>

                                @if ($type === 'image')
                                    <div class="mt-4">
                                        <label for="image_file"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image
                                            File</label>

                                        @if (isset($content_data['url']))
                                            <div class="mt-2 mb-4">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Current image:</p>
                                                <img src="{{ $content_data['url'] }}"
                                                    class="mt-2 max-h-48 object-contain rounded-md border border-gray-300"
                                                    alt="{{ $name }}">
                                            </div>
                                        @endif

                                        <div
                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                    fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="image_file"
                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                        <span>Upload a new file</span>
                                                        <input id="image_file" wire:model="image_file" type="file"
                                                            class="sr-only">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                        </div>
                                        @error('image_file')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif($type === 'video' || $type === 'url')
                                    <div class="mt-4">
                                        <label for="url"
                                            class="block text-sm font-medium text-gray-700">URL</label>
                                        <div class="mt-1">
                                            <input type="text" wire:model="url" id="url"
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                placeholder="https://example.com/video.mp4">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">
                                            @if ($type === 'video')
                                                Enter the URL to a video file (MP4, WebM) or a video streaming
                                                service
                                                (YouTube, Vimeo)
                                            @else
                                                Enter the URL to be displayed in an iframe
                                            @endif
                                        </p>
                                        @error('url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif($type === 'html')
                                    <div class="mt-4">
                                        <label for="html_content" class="block text-sm font-medium text-gray-700">HTML
                                            Content</label>
                                        <div class="mt-1">
                                            <textarea wire:model="html_content" id="html_content" rows="10"
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono"
                                                placeholder="<div>Your HTML content here</div>"></textarea>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Enter the HTML code to be rendered on
                                            the
                                            screen</p>
                                        @error('html_content')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div> <!-- Advanced Settings Section -->
                            <div class="sm:col-span-6" x-data="{ showAdvancedSettings: false }">
                                <div class="mt-1 border-t border-gray-200 pt-5">
                                    <button type="button" @click="showAdvancedSettings = !showAdvancedSettings"
                                        class="flex items-center text-indigo-600 hover:text-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span
                                            x-text="showAdvancedSettings ? 'Hide Advanced Settings' : 'Show Advanced Settings'"></span>
                                    </button>
                                </div>

                                <div x-show="showAdvancedSettings"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2">
                                    <div class="mt-4 p-4 bg-gray-50 rounded-md">
                                        <h4 class="text-md font-medium text-gray-700 mb-2">Advanced Settings</h4>
                                        <!-- Transition settings would go here -->
                                        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4">
                                            <div>
                                                <label for="transition_in"
                                                    class="block text-sm font-medium text-gray-700">Transition
                                                    In</label>
                                                <select id="transition_in" wire:model="settings.transition_in"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                    <option value="fade">Fade</option>
                                                    <option value="slide">Slide</option>
                                                    <option value="zoom">Zoom</option>
                                                    <option value="none">None</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="transition_out"
                                                    class="block text-sm font-medium text-gray-700">Transition
                                                    Out</label>
                                                <select id="transition_out" wire:model="settings.transition_out"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                    <option value="fade">Fade</option>
                                                    <option value="slide">Slide</option>
                                                    <option value="zoom">Zoom</option>
                                                    <option value="none">None</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="transition_duration"
                                                    class="block text-sm font-medium text-gray-700">Transition
                                                    Duration (ms)</label>
                                                <input type="number" id="transition_duration"
                                                    wire:model="settings.transition_duration" min="100"
                                                    max="2000" step="100"
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" wire:click="closeModal"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="py-12 text-center">
                <p class="text-gray-500">No content selected or content not found.</p>
                <button type="button" wire:click="closeModal"
                    class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Close
                </button>
            </div>
        @endif
    </x-modal>
</div>

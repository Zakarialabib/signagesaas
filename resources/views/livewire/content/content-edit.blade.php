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
                                <label for="edit-name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content
                                    Name</label>
                                <div class="mt-1">
                                    <input type="text" wire:model="name" id="edit-name"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="edit-screen_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Screen</label>
                                <div class="mt-1">
                                    <select wire:model="screen_id" id="edit-screen_id"
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
                                <label for="edit-description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <div class="mt-1">
                                    <textarea wire:model="description" id="edit-description" rows="3"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300"></textarea>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="edit-type"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content
                                    Type</label>
                                <div class="mt-1">
                                    <select wire:model.live="type" id="edit-type"
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
                                <label for="edit-status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <div class="mt-1">
                                    <select wire:model="status" id="edit-status"
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
                                <label for="edit-duration"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration
                                    (seconds)</label>
                                <div class="mt-1">
                                    <input type="number" wire:model="duration" id="edit-duration" min="5"
                                        max="300"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                @error('duration')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="edit-order"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display
                                    Order</label>
                                <div class="mt-1">
                                    <input type="number" wire:model="order" id="edit-order" min="0"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="edit-start_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                                    Date</label>
                                <div class="mt-1">
                                    <input type="date" wire:model="start_date" id="edit-start_date" 
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="edit-end_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                                    Date</label>
                                <div class="mt-1">
                                    <input type="date" wire:model="end_date" id="edit-end_date"
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

                                @if ($type === \App\Enums\ContentType::IMAGE->value)
                                    <div class="mt-4">
                                        <label for="edit-image_file"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image
                                            File</label>

                                        @if (isset($content_data['url']))
                                            <div class="mt-2 mb-4">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Current image:</p>
                                                <img src="{{ $content_data['url'] }}"
                                                    class="mt-2 max-h-48 object-contain rounded-md border border-gray-300 dark:border-gray-600"
                                                    alt="{{ $name }}">
                                            </div>
                                        @endif

                                        <div
                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                    fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                                    <label for="edit-image_file"
                                                        class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                        <span>Upload a new file (optional)</span>
                                                        <input id="edit-image_file" wire:model="image_file" type="file"
                                                            class="sr-only">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, WEBP up to 10MB</p>
                                            </div>
                                        </div>
                                        @error('image_file')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif($type === \App\Enums\ContentType::VIDEO->value || $type === \App\Enums\ContentType::URL->value)
                                    <div class="mt-4">
                                        <label for="edit-url"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL</label>
                                        <div class="mt-1">
                                            <input type="text" wire:model="url" id="edit-url"
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300"
                                                placeholder="https://example.com/video.mp4">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            @if ($type === \App\Enums\ContentType::VIDEO->value)
                                                Enter the URL to a video file (MP4, WebM) or a video streaming
                                                service
                                                (YouTube, Vimeo)
                                            @else
                                                Enter the URL to be displayed in an iframe
                                            @endif
                                        </p>
                                        @error('url')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif($type === \App\Enums\ContentType::HTML->value || $type === \App\Enums\ContentType::CUSTOM->value)
                                    <div class="mt-4">
                                        <label for="edit-html_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">HTML
                                            Content</label>
                                        <div class="mt-1">
                                            <textarea wire:model="html_content" id="edit-html_content" rows="10"
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md font-mono dark:bg-gray-700 dark:text-gray-300"
                                                placeholder="<div>Your HTML content here</div>"></textarea>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the HTML code to be rendered on
                                            the
                                            screen</p>
                                        @error('html_content')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($type === \App\Enums\ContentType::RSS->value)
                                    <div class="mt-4">
                                        <label for="edit-feed_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">RSS Feed URL</label>
                                        <input type="text" wire:model="feed_url" id="edit-feed_url" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-300" placeholder="https://example.com/feed.xml">
                                        @error('feed_url') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                @elseif ($type === \App\Enums\ContentType::WEATHER->value)
                                    <div class="mt-4">
                                        <label for="edit-location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                        <input type="text" wire:model="location" id="edit-location" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-300" placeholder="e.g., London, UK">
                                        @error('location') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                @elseif ($type === \App\Enums\ContentType::PRODUCT_LIST->value || $type === \App\Enums\ContentType::MENU->value)
                                    <div class="sm:col-span-6 mt-4 py-4 border-t border-gray-200 dark:border-gray-700">
                                        @if ($content) {{-- Ensure content is loaded before rendering editor --}}
                                            <livewire:content.product-list-editor :contentId="$content->id" :key="'pli-'. $content->id . '-' . $type" />
                                        @endif
                                    </div>
                                @endif
                            </div> <!-- Advanced Settings Section -->
                            <div class="sm:col-span-6" x-data="{ showAdvancedSettings: @entangle('showAdvancedSettings') }">
                                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <button type="button" @click="showAdvancedSettings = !showAdvancedSettings"
                                        class="flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <x-heroicon-s-cog class="h-5 w-5 mr-2" />
                                        <span x-text="showAdvancedSettings ? 'Hide Advanced Settings' : 'Show Advanced Settings'"></span>
                                    </button>
                                </div>
                                <div x-show="showAdvancedSettings" x-collapse class="mt-4 space-y-6">
                                     <!-- Settings specific to content type can be added here -->
                                     <div>
                                        <label for="edit-settings-custom_css" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custom CSS</label>
                                        <textarea wire:model="settings.custom_css" id="edit-settings-custom_css" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-300 font-mono" placeholder=".custom-class { color: red; }"></textarea>
                                        @error('settings.custom_css') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="edit-settings-custom_js" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custom JavaScript</label>
                                        <textarea wire:model="settings.custom_js" id="edit-settings-custom_js" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-300 font-mono" placeholder="console.log('Hello from content');"></textarea>
                                        @error('settings.custom_js') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                </div>
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
                                    Update Content
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
        @else
            <div class="p-6 text-center">
                <p class="text-gray-500 dark:text-gray-400">Loading content...</p>
            </div>
        @endif
    </x-modal>
</div>

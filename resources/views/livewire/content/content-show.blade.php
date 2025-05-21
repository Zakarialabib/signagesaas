<div>    <x-modal wire:model="showContentModal" id="show-content-modal" title="Content Details" maxWidth="3xl" x-on:keydown.escape.window="$wire.closeModal()">
        @if ($this->content)
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Content: {{ $content->name }}</h1>
                    <div class="flex space-x-2">
                        <button wire:click="refreshContent"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh
                        </button>                        <button wire:click="toggleStatus"
                            class="inline-flex items-center px-3 py-2 border {{ $content->status->value === 'active' ? 'border-red-300 dark:border-red-600 text-red-700 dark:text-red-400' : 'border-green-300 dark:border-green-600 text-green-700 dark:text-green-400' }} shadow-sm text-sm font-medium rounded-md bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            @if ($content->status->value === 'active')
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-red-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Deactivate
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-green-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Activate
                            @endif
                        </button>

                        <button x-data
                            x-on:click="if (confirm('Are you sure you want to delete this content? This action cannot be undone.')) { $wire.deleteContent() }"
                            class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-red-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 flex justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Content Details</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Information about this content.</p>
                        </div>
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $content->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($content->status->value) }}
                        </span>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Content name</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $content->name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $content->description ?? 'No description' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Screen</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $content->screen->name }}
                                    <span class="text-gray-500"> - Device: </span>
                                    {{ $content->screen->device->name }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($content->type->value) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Display parameters</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <span class="font-medium">Duration:</span> {{ $content->duration ?? 10 }}
                                            seconds
                                        </div>
                                        <div>
                                            <span class="font-medium">Order:</span> {{ $content->order ?? 0 }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Is Active:</span>
                                            <span
                                                class="{{ $content->isActive() ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $content->isActive() ? 'Yes' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Schedule</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="font-medium">Start Date:</span>
                                            {{ $content->start_date ? $content->start_date->format('Y-m-d H:i') : 'No start date' }}
                                        </div>
                                        <div>
                                            <span class="font-medium">End Date:</span>
                                            {{ $content->end_date ? $content->end_date->format('Y-m-d H:i') : 'No end date' }}
                                        </div>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Content Preview</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Preview how this content looks on the screen.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 p-6">
                        <div
                            class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden border border-gray-300">
                            <div class="p-4" x-data="{
                                initPreview() {
                                    if ('{{ $content->type->value }}' === 'video') {
                                        const video = this.$el.querySelector('video');
                                        if (video) {
                                            video.addEventListener('ended', function() {
                                                this.currentTime = 0;
                                                this.play();
                                            });
                                        }
                                    }
                                }
                            }" x-init="initPreview()">
                                @if ($this->isImage())
                                    <img src="{{ $content->content_data['url'] ?? '' }}"
                                        class="w-full h-full object-contain" alt="{{ $content->name }}">
                                @elseif($this->isVideo())
                                    <video class="w-full h-full object-contain" controls autoplay>
                                        <source src="{{ $content->content_data['url'] ?? '' }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif($this->isHtml())
                                    <div class="w-full h-full bg-white p-4 overflow-auto">
                                        {!! $content->content_data['html'] ?? '' !!}
                                    </div>
                                @elseif($this->isUrl())
                                    <iframe class="w-full h-full" src="{{ $content->content_data['url'] ?? '' }}"
                                        frameborder="0" allowfullscreen></iframe>
                                @endif
                            </div>
                        </div>

                        @if ($this->isImage() || $this->isVideo() || $this->isUrl())
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-500">URL</h4>
                                <div class="mt-1 flex items-center">
                                    <p class="text-sm text-gray-900 break-all grow pr-2">
                                        {{ $content->content_data['url'] ?? 'N/A' }}</p>
                                    <a href="{{ $content->content_data['url'] ?? '#' }}" target="_blank"
                                        class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Open
                                    </a>
                                </div>
                            </div>
                        @elseif($this->isHtml())
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-500">HTML</h4>
                                <pre class="mt-1 text-xs text-gray-900 bg-gray-50 p-4 rounded-md overflow-x-auto">{{ $content->content_data['html'] ?? 'N/A' }}</pre>
                            </div>
                        @endif

                        @if (!empty($content->settings))
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-500">Settings</h4>
                                <div class="mt-1 text-sm text-gray-900">
                                    <ul class="list-disc list-inside pl-4">
                                        @foreach ($content->settings as $key => $value)
                                            <li><span
                                                    class="font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                {{ is_array($value) ? json_encode($value) : $value }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </x-modal>
</div>

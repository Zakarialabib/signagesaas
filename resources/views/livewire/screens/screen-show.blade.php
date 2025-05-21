<div>    <x-modal wire:model="showScreenModal" id="show-screen-modal" title="Screen Details" maxWidth="3xl" x-on:keydown.escape.window="$wire.closeModal()">

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Screen: {{ $screen?->name }}</h1>
                <div class="flex space-x-2">
                    <button wire:click="refreshScreen"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                    <a href="{{ $screen ? $screen->getPreviewUrl() : '#' }}" target="_blank"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview
                    </a>
                    <a href="{{ $screen ? $screen->getDisplayUrl() : '#' }}" target="_blank"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Display
                    </a>
                </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 flex justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Screen Details</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Information about this screen.</p>
                    </div>
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $screen?->isActive() ? 'bg-green-100 text-green-800' : 
                          ($screen?->isInMaintenance() ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $screen?->status?->value }}
                    </span>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Screen name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $screen?->name }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $screen?->description ?? 'No description' }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Device</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $screen?->device?->name ?? 'N/A' }}
                                @if($screen?->device)
                                    <span class="px-2 ml-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $screen->device->isOnline() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $screen->device->isOnline() ? 'Online' : 'Offline' }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Resolution</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $screen ? $this->getFormattedResolution() : 'N/A' }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Orientation</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $screen?->orientation?->value }}
                                <span class="text-gray-400">
                                    ({{ $screen?->isLandscape() ? 'Landscape' : 'Portrait' }})
                                </span>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if ($screen?->location)
                                    <div>
                                        @if(isset($screen->location['name']))
                                            <div><span class="font-medium">Name:</span> {{ $screen->location['name'] }}</div>
                                        @endif
                                        
                                        @if(isset($screen->location['address']))
                                            <div><span class="font-medium">Address:</span> {{ $screen->location['address'] }}</div>
                                        @endif
                                        
                                        @if(isset($screen->location['zone']))
                                            <div><span class="font-medium">Zone:</span> {{ $screen->location['zone'] }}</div>
                                        @endif
                                        
                                        @if(isset($screen->location['floor']))
                                            <div><span class="font-medium">Floor:</span> {{ $screen->location['floor'] }}</div>
                                        @endif
                                    </div>
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Settings</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if ($screen?->settings)
                                    <div>
                                        @if(isset($screen->settings['refresh_rate']))
                                            <div><span class="font-medium">Refresh Rate:</span> {{ $screen->settings['refresh_rate'] }}Hz</div>
                                        @endif
                                        
                                        @if(isset($screen->settings['transition_effect']))
                                            <div><span class="font-medium">Transition Effect:</span> {{ ucfirst($screen->settings['transition_effect']) }}</div>
                                        @endif
                                        
                                        @if(isset($screen->settings['transition_duration']))
                                            <div><span class="font-medium">Transition Duration:</span> {{ $screen->settings['transition_duration'] }}ms</div>
                                        @endif
                                        
                                        @if(isset($screen->settings['brightness']))
                                            <div><span class="font-medium">Brightness:</span> {{ $screen->settings['brightness'] }}%</div>
                                        @endif
                                        
                                        @if(isset($screen->settings['volume']))
                                            <div><span class="font-medium">Volume:</span> {{ $screen->settings['volume'] }}%</div>
                                        @endif
                                        
                                        @if(isset($screen->settings['enable_touch']))
                                            <div><span class="font-medium">Touch Enabled:</span> {{ $screen->settings['enable_touch'] ? 'Yes' : 'No' }}</div>
                                        @endif
                                        
                                        @if(isset($screen->settings['enable_sensors']))
                                            <div><span class="font-medium">Sensors Enabled:</span> {{ $screen->settings['enable_sensors'] ? 'Yes' : 'No' }}</div>
                                        @endif
                                    </div>
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Content</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Content items displayed on this screen.</p>
                    </div>
                </div>

                @if ($screen && $screen->contents->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Duration</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($screen->contents->sortBy('order') as $content)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $content->order }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $content->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($content->description, 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $content->type->value }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button wire:click="toggleContentStatus('{{ $content->id }}')" type="button"
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $content->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $content->status->value }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $content->duration ?? 10 }}s
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button wire:click="$dispatch('editContent', { id: '{{ $content->id }}' })" 
                                            class="text-indigo-600 hover:text-indigo-900 mr-2">
                                            Edit
                                        </button>
                                        <button wire:click="$dispatch('preview-content', { id: '{{ $content->id }}' })" 
                                            class="text-blue-600 hover:text-blue-900">
                                            Preview
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12 border-t border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No content</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding content to this screen.</p>
                        <div class="mt-6">
                            <button wire:click="$dispatch('createContent', { screen_id: '{{ $screen?->id }}' })"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Content
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-modal>
</div>

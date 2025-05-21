{{-- Template Configurator View --}}
<div>
    <div class="template-configurator">
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Template Configuration</h2>
            <p class="mt-1 text-sm text-gray-500">Configure zones and assign content for this template.</p>
        </div>

        <!-- Grid Controls -->
        <div class="mb-6 flex items-center space-x-4">
            <label class="flex items-center">
                <input type="checkbox" wire:model.live="snapToGrid"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Snap to Grid</span>
            </label>
            @if($snapToGrid)
                <div class="flex items-center space-x-2">
                    <label for="gridSize" class="text-sm text-gray-700 dark:text-gray-300">Grid Size:</label>
                    <select wire:model.live="gridSize" id="gridSize"
                        class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach([5, 10, 15, 20] as $size)
                            <option value="{{ $size }}">{{ $size }}%</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <!-- Live Preview -->
        <div class="mb-6">
            <div class="relative" style="aspect-ratio: 16/9;">
                <div class="absolute inset-0 bg-gray-100 dark:bg-gray-900 rounded-lg">
                    @foreach ($zones as $zoneId => $zone)
                        <div class="absolute cursor-move bg-white dark:bg-gray-800 shadow-sm rounded-lg border-2 border-transparent hover:border-indigo-500"
                            x-data="{ 
                                isDragging: false,
                                startX: 0,
                                startY: 0,
                                startLeft: 0,
                                startTop: 0
                            }"
                            x-on:mousedown="
                                isDragging = true;
                                startX = $event.pageX;
                                startY = $event.pageY;
                                startLeft = $el.offsetLeft;
                                startTop = $el.offsetTop;
                            "
                            x-on:mousemove.window="
                                if (isDragging) {
                                    const dx = $event.pageX - startX;
                                    const dy = $event.pageY - startY;
                                    const container = $el.parentElement;
                                    const x = ((startLeft + dx) / container.offsetWidth) * 100;
                                    const y = ((startTop + dy) / container.offsetHeight) * 100;
                                    $wire.updateZonePosition('{{ $zoneId }}', x, y, {{ $zone['width'] }}, {{ $zone['height'] }});
                                }
                            "
                            x-on:mouseup.window="isDragging = false"
                            style="left: {{ $zone['x'] }}%; top: {{ $zone['y'] }}%; width: {{ $zone['width'] }}%; height: {{ $zone['height'] }}%;"
                        >
                            <div class="p-2">
                                <div class="text-xs font-medium text-gray-900 dark:text-gray-100">{{ $zone['name'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Zones Configuration -->
        <div class="space-y-6">
            @foreach ($zones as $zoneId => $zone)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <!-- Zone Header -->
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            {{ $zone['name'] }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            {{ ucfirst($zone['type']) }} Zone
                        </p>
                    </div>

                    <!-- Zone Content -->
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Content Assignment -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Content</h4>
                                @if (isset($zoneContent[$zoneId]))
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                @if ($zoneContent[$zoneId]->type->value === 'image')
                                                    <img src="{{ $zoneContent[$zoneId]->content_data['url'] }}"
                                                        alt="{{ $zoneContent[$zoneId]->name }}"
                                                        class="h-12 w-12 rounded-lg object-cover">
                                                @else
                                                    <div class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                        <x-heroicon-o-document-text class="h-6 w-6 text-gray-400" />
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $zoneContent[$zoneId]->name }}
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    {{ $zoneContent[$zoneId]->type->label() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center bg-gray-50 dark:bg-gray-900 rounded-lg p-8">
                                        {{-- <x-heroicon-o-square-plus class="mx-auto h-12 w-12 text-gray-400" /> --}}
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No content assigned</h3>
                                        <p class="mt-1 text-sm text-gray-500">Assign content to this zone to get started.</p>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <livewire:content.templates.components.zone-content-selector 
                                        :key="'content-selector-'.$zoneId"
                                        :zone-id="$zoneId" 
                                        :allowed-types="$this->getZoneContentTypes($zoneId)" />
                                </div>
                            </div>

                            <!-- Zone Settings -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Settings</h4>
                                <div class="space-y-4">
                                    <!-- Duration -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Duration (seconds)
                                        </label>
                                        <input type="number" wire:model.blur="zoneSettings.{{ $zoneId }}.duration"
                                            min="1" max="3600"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-700">
                                    </div>

                                    <!-- Background Color -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Background Color
                                        </label>
                                        <input type="color" wire:model.live="zoneSettings.{{ $zoneId }}.background"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <!-- Padding -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Padding
                                        </label>
                                        <input type="text" wire:model.blur="zoneSettings.{{ $zoneId }}.padding"
                                            placeholder="0px or 0px 0px 0px 0px"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-700">
                                    </div>

                                    <!-- Border Radius -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Border Radius
                                        </label>
                                        <input type="text" wire:model.blur="zoneSettings.{{ $zoneId }}.border-radius"
                                            placeholder="0px or 0px 0px 0px 0px"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-700">
                                    </div>

                                    {{-- Widget Specific Settings --}}
                                    @php
                                        // Determine if this zone is a weather-type zone.
                                        // This logic might need refinement based on how zone types are precisely defined.
                                        // For now, we check if its 'type' in layout is 'weather' or if its assigned content's category is weather.
                                        $isWeatherZone = false;
                                        if (isset($zone['type']) && $zone['type'] === \App\Enums\ContentType::WEATHER->value) {
                                            $isWeatherZone = true;
                                        } elseif (isset($zoneContent[$zoneId]) && $zoneContent[$zoneId]->template_category === \App\Enums\TemplateCategory::WEATHER) {
                                            // This check might be more for content driven widgets rather than zone type itself.
                                            // $isWeatherZone = true; // Decide if this is also a valid condition
                                        }
                                        // A more direct way could be to check the zone's intended purpose if stored in $zone['settings']['widget_type']
                                        if (isset($zone['settings']['widget_type']) && $zone['settings']['widget_type'] === \App\Enums\TemplateCategory::WEATHER->value) {
                                           $isWeatherZone = true;
                                        }
                                    @endphp

                                    @if ($isWeatherZone)
                                        <div>
                                            <label for="zone_settings_{{ $zoneId }}_weather_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Weather API Key
                                            </label>
                                            <input type="text"
                                                   wire:model.defer="zoneSettings.{{ $zoneId }}.weather_api_key"
                                                   id="zone_settings_{{ $zoneId }}_weather_api_key"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                                   placeholder="Enter Weather API Key">
                                        </div>
                                        <div>
                                            <label for="zone_settings_{{ $zoneId }}_weather_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Weather Location
                                            </label>
                                            <input type="text"
                                                   wire:model.defer="zoneSettings.{{ $zoneId }}.weather_location"
                                                   id="zone_settings_{{ $zoneId }}_weather_location"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                                   placeholder="e.g., London or New York">
                                        </div>
                                    @endif
                                    {{-- End Widget Specific Settings --}}
                                </div>

                                <!-- Update Settings Button -->
                                <div class="mt-4 flex justify-between">
                                    <button wire:click="updateZoneSettings('{{ $zoneId }}')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Update Settings
                                    </button>
                                    <button wire:click="deleteZone('{{ $zoneId }}')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete Zone
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add Zone Button -->
        <div class="mt-6">
            <button wire:click="addZone"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <x-heroicon-s-plus class="h-5 w-5 mr-2" />
                Add Zone
            </button>
        </div>
    </div>
</div>

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
                                    $wire.updateZonePosition('{{ $zoneId }}', x, y, {{ $zone['width_percentage'] ?? 30 }}, {{ $zone['height_percentage'] ?? 20 }});
                                }
                            "
                            x-on:mouseup.window="isDragging = false"
                            style="left: {{ $zone['x_percentage'] ?? 0 }}%; top: {{ $zone['y_percentage'] ?? 0 }}%; width: {{ $zone['width_percentage'] ?? 30 }}%; height: {{ $zone['height_percentage'] ?? 20 }}%;"
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
                                <div class="mt-4">
                                    <button wire:click="initiateContentSelection('{{ $zoneId }}')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ isset($zoneContent[$zoneId]) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <x-heroicon-o-pencil-square class="h-5 w-5 mr-2" />
                                        {{ isset($zoneContent[$zoneId]) ? 'Change Content' : 'Assign Content' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Zone Settings -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Settings</h4>
                                <div class="space-y-4">
                                    {{-- Zone Type and Widget Type Selection --}}
                                    <div>
                                        <label for="zone_type_{{ $zoneId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Zone Type</label>
                                        <select id="zone_type_{{ $zoneId }}" 
                                                wire:change="updateZoneTypeProperties('{{ $zoneId }}', $event.target.value, document.getElementById('widget_type_select_{{ $zoneId }}') ? document.getElementById('widget_type_select_{{ $zoneId }}').value : null)"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100">
                                            <option value="content" {{ ($zone['type'] ?? 'content') === 'content' ? 'selected' : '' }}>Generic Content</option>
                                            <option value="image" {{ ($zone['type'] ?? '') === 'image' ? 'selected' : '' }}>Image (Fixed Type)</option>
                                            <option value="video" {{ ($zone['type'] ?? '') === 'video' ? 'selected' : '' }}>Video (Fixed Type)</option>
                                            {{-- Add other simple types as needed --}}
                                            <option value="widget" {{ ($zone['type'] ?? '') === 'widget' ? 'selected' : '' }}>Widget</option>
                                        </select>
                                    </div>

                                    @if(isset($zone['type']) && $zone['type'] === 'widget')
                                    <div class="mt-4">
                                        <label for="widget_type_select_{{ $zoneId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Specific Widget Type</label>
                                        <select id="widget_type_select_{{ $zoneId }}" 
                                                wire:change="updateZoneTypeProperties('{{ $zoneId }}', 'widget', $event.target.value)"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100">
                                            <option value="">-- Select Widget --</option>
                                            @foreach($this->availableWidgetTypesForZones as $widgetKey => $widgetName)
                                                <option value="{{ $widgetKey }}" {{ ($zone['widget_type'] ?? '') === $widgetKey ? 'selected' : '' }}>{{ $widgetName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif

                                    <!-- Duration -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Duration (seconds)
                                        </label>
                                        <input type="number" wire:model.blur="zoneSettings.{{ $zoneId }}.duration"
                                            min="1" max="3600"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100">
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
                                            placeholder="e.g., 10px or 1rem"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100">
                                    </div>

                                    <!-- Border Radius -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Border Radius
                                        </label>
                                        <input type="text" wire:model.blur="zoneSettings.{{ $zoneId }}.border-radius"
                                            placeholder="e.g., 5px or 0.5rem"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100">
                                    </div>

                                    {{-- Widget Specific Settings from Template Definition --}}
                                    @if(isset($zone['widget_type']) && !empty($zone['widget_type']))
                                        {{-- Example for a 'WeatherWidget' --}}
                                        @if($zone['widget_type'] === 'WeatherWidget')
                                            <div>
                                                <label for="zone_settings_{{ $zoneId }}_weather_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Location (for Weather)
                                                </label>
                                                <input type="text"
                                                       wire:model="zoneSettings.{{ $zoneId }}.weather_location"
                                                       id="zone_settings_{{ $zoneId }}_weather_location"
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                                       placeholder="e.g., London, UK">
                                            </div>
                                        @endif
                                        {{-- Example for a 'MenuWidget' (using settings from TemplateSeeder) --}}
                                        @if($zone['widget_type'] === 'MenuWidget')
                                            <div>
                                                <label for="zone_settings_{{ $zoneId }}_menu_style" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Menu Style
                                                </label>
                                                <select wire:model="zoneSettings.{{ $zoneId }}.menu_style"
                                                        id="zone_settings_{{ $zoneId }}_menu_style"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                                    <option value="classic">Classic</option>
                                                    <option value="modern">Modern</option>
                                                    <option value="compact">Compact</option>
                                                </select>
                                            </div>
                                        @endif
                                        {{-- Add more widget-specific settings here based on $zone['widget_type'] --}}
                                    @endif
                                </div>

                                <!-- Update Settings Button -->
                                <div class="mt-6 flex justify-between">
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
        <div class="mt-8">
            <button wire:click="addZone"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <x-heroicon-s-plus-circle class="h-5 w-5 mr-2" />
                Add New Zone
            </button>
        </div>
    </div>

    <!-- Widget Data Info Modal -->
    <x-modal wire:model="showWidgetDataInfoModal" maxWidth="lg">
        <x-slot name="title">
            Widget Zone Information
        </x-slot>

        <x-slot name="content">
            @if($currentZoneIdForWidgetInfo && $currentWidgetTypeForInfo)
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    The zone <strong class="font-semibold text-gray-900 dark:text-gray-100">{{ $zones[$currentZoneIdForWidgetInfo]['name'] ?? $currentZoneIdForWidgetInfo }}</strong> 
                    is configured as a <strong class="font-semibold text-gray-900 dark:text-gray-100">{{ $currentWidgetTypeForInfo }}</strong> widget.
                </p>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    A specialized form for inputting or selecting data for this widget type will be implemented in a future update. 
                    For now, if this widget requires specific data, you would typically create a "Content" item of type "Custom" or "JSON", 
                    and manually structure its 'content_data' field according to the widget's requirements. This Content item would then be assigned to this zone.
                </p>
                 <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    No generic content can be assigned to this zone directly through the configurator at this moment as it expects widget-specific data.
                </p>
            @else
                <p>Loading widget information...</p>
            @endif
        </x-slot>

        <x-slot name="footer">
            <button wire:click="closeWidgetDataInfoModal" type="button"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Got it
            </button>
        </x-slot>
    </x-modal>

    <!-- Generic Content Selector Modal -->
    <x-modal wire:model="showGenericContentSelectorModal" maxWidth="3xl">
        <x-slot name="title">
            Select Content for Zone
        </x-slot>

        <x-slot name="content">
            @if($currentZoneIdForGenericSelector)
                <p class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                    Assigning content to zone: <strong class="font-semibold text-gray-900 dark:text-gray-100">{{ $zones[$currentZoneIdForGenericSelector]['name'] ?? $currentZoneIdForGenericSelector }}</strong>
                </p>
                <livewire:content.templates.components.zone-content-selector 
                    :key="'generic-content-selector-'.$currentZoneIdForGenericSelector"
                    :zone-id="$currentZoneIdForGenericSelector" 
                    :allowed-types="$this->getZoneContentTypes($currentZoneIdForGenericSelector)" 
                />
            @else
                <p>Loading content selector...</p>
            @endif
        </x-slot>

        <x-slot name="footer">
            <button wire:click="closeGenericContentSelectorModal" type="button"
                class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                Cancel
            </button>
        </x-slot>
    </x-modal>
</div>

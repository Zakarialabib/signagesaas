{{-- Template Configurator View --}}
<div>
    <livewire:dashboard.onboarding-widget :contextStepKey="App\Enums\OnboardingStep::WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE->value" />
    <div class="template-configurator">
        <livewire:dashboard.onboarding-widget :contextStepKey="'widget_content_assigned_to_template'" />
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Template Configuration</h2>
            <p class="mt-1 text-sm text-gray-500">Configure zones and assign content for this template.</p>
        </div>

        <!-- Grid Controls -->
        <div class="mb-6 flex items-center space-x-4">
            <label class="flex items-center">
                <input type="checkbox" wire:model.live="snapToGrid"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Snap to Grid</span>
            </label>
            @if($snapToGrid)
                <div class="flex items-center space-x-2">
                    <label for="gridSize" class="text-sm text-gray-700 dark:text-gray-300">Grid Size:</label>
                    <select wire:model.live="gridSize" id="gridSize"
                        class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
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
                                startTop: 0,
                                zoneWidth: {{ $zone['width_percentage'] ?? 30 }},
                                zoneHeight: {{ $zone['height_percentage'] ?? 20 }}
                            }"
                            x-on:mousedown.self="
                                isDragging = true;
                                startX = $event.pageX;
                                startY = $event.pageY;
                                startLeft = $el.offsetLeft;
                                startTop = $el.offsetTop;
                                $event.stopPropagation();
                            "
                            x-on:mousemove.window="
                                if (isDragging) {
                                    const dx = $event.pageX - startX;
                                    const dy = $event.pageY - startY;
                                    const container = $el.parentElement;
                                    const x = ((startLeft + dx) / container.offsetWidth) * 100;
                                    const y = ((startTop + dy) / container.offsetHeight) * 100;
                                    $wire.updateZonePosition('{{ $zoneId }}', x, y, zoneWidth, zoneHeight);
                                }
                            "
                            x-on:mouseup.window="isDragging = false"
                            style="left: {{ $zone['x_percentage'] ?? 0 }}%; top: {{ $zone['y_percentage'] ?? 0 }}%; width: {{ $zone['width_percentage'] ?? 30 }}%; height: {{ $zone['height_percentage'] ?? 20 }}%;"
                        >
                            <div class="p-2 h-full flex flex-col justify-between">
                                <div class="text-xs font-medium text-gray-900 dark:text-gray-100 truncate">{{ $zone['name'] }}</div>
                                {{-- Placeholder for content preview or type icon --}}
                                <div class="text-center text-xs text-gray-400">
                                    ({{ $zone['width_percentage'] ?? 0 }}% x {{ $zone['height_percentage'] ?? 0 }}%)
                                </div>
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
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                {{ $zone['name'] }}
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                {{ ucfirst($zone['type']) }} Zone @if($zone['widget_type']) (Widget: {{ $zone['widget_type'] }}) @endif
                            </p>
                        </div>
                        @if($zone['widget_type'])
                            <button type="button" wire:click="openWidgetInfoModal('{{ $zoneId }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 text-sm font-medium">
                                <x-heroicon-o-information-circle class="h-5 w-5" />
                            </button>
                        @endif
                    </div>

                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Content</h4>
                                @if (isset($zoneContent[$zoneId]))
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                @if ($zoneContent[$zoneId]->type->value === 'image' && isset($zoneContent[$zoneId]->content_data['url']))
                                                    <img src="{{ $zoneContent[$zoneId]->content_data['url'] }}"
                                                        alt="{{ $zoneContent[$zoneId]->name }}"
                                                        class="h-12 w-12 rounded-lg object-cover">
                                                @else
                                                    <div class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                        <x-heroicon-s-document-text class="h-6 w-6 text-gray-400" />
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $zoneContent[$zoneId]->name }}
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $zoneContent[$zoneId]->type->label() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center bg-gray-50 dark:bg-gray-900 rounded-lg p-8">
                                        <x-heroicon-o-document-plus class="mx-auto h-12 w-12 text-gray-400" />
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No content assigned</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Assign content or configure widget.</p>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <button wire:click="initiateContentSelection('{{ $zoneId }}')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ (isset($zoneContent[$zoneId]) || $zone['widget_type']) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <x-heroicon-s-pencil-square class="h-5 w-5 mr-2" />
                                        @if ($zone['widget_type'])
                                            Configure Widget
                                        @elseif (isset($zoneContent[$zoneId]))
                                            Change Content
                                        @else
                                            Assign Content
                                        @endif
                                    </button>
                                </div>

                                {{-- Widget Preview Section --}}
                                @if (!empty($zone['widget_type']))
                                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <h5 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Widget Live Preview</h5>
                                        <div class="mb-2">
                                            <button type="button" wire:click="loadAvailablePreviewContent('{{ $zoneId }}')"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <x-heroicon-o-eye class="h-4 w-4 mr-1.5" />
                                                Select Content for Preview
                                            </button>
                                            @if (!empty($availablePreviewContent[$zoneId]))
                                                <button type="button" wire:click="setPreviewData('{{ $zoneId }}', null)"
                                                    class="ml-2 inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <x-heroicon-o-x-circle class="h-4 w-4 mr-1.5" />
                                                    Clear Preview
                                                </button>
                                            @endif
                                        </div>

                                        @if (!empty($availablePreviewContent[$zoneId]))
                                            <div class="mt-2 mb-3">
                                                <label for="preview_content_{{ $zoneId }}" class="sr-only">Select content for preview</label>
                                                <select id="preview_content_{{ $zoneId }}" wire:change="setPreviewData('{{ $zoneId }}', $event.target.value)"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-xs dark:bg-gray-700 dark:text-gray-200">
                                                    <option value="">-- Choose Content --</option>
                                                    @foreach ($availablePreviewContent[$zoneId] as $contentItem)
                                                        <option value="{{ $contentItem['id'] }}">{{ $contentItem['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        
                                        {{-- Actual Widget Rendering for Preview --}}
                                        <div class="mt-2 p-2 border border-dashed border-gray-300 dark:border-gray-600 rounded-md min-h-[100px] bg-gray-50 dark:bg-gray-700/30">
                                            @php
                                                // Attempt to resolve widget component name.
                                                // This mapping should ideally be more robust, e.g., from a config file or service.
                                                $widgetComponentMap = [
                                                    'RetailProductWidget' => 'App.Livewire.Content.Widgets.RetailProductWidget',
                                                    'MenuWidget' => 'App.Livewire.Content.Widgets.MenuWidget',
                                                    // Add other mappings here as needed
                                                ];
                                                $widgetToRender = $widgetComponentMap[$zone['widget_type']] ?? null;
                                                
                                                $currentPreviewData = $zonePreviewContentData[$zoneId] ?? null;
                                                $assignedContentId = $zone['content_id'] ?? null;
                                                // Unique key incorporating preview status and content ID
                                                $widgetKey = 'widget-preview-' . $zoneId . '-' . ($currentPreviewData ? 'preview-' . md5(json_encode($currentPreviewData)) : 'assigned-' . $assignedContentId);
                                            @endphp

                                            @if ($widgetToRender)
                                                @livewire($widgetToRender, [
                                                    'settings' => $zone['settings'] ?? [],
                                                    'title' => $zone['name'] ?? $zone['widget_type'], // BaseWidget title
                                                    'category' => $zone['widget_type'],             // BaseWidget category
                                                    'icon' => 'heroicon-o-puzzle-piece',        // BaseWidget icon
                                                    'contentId' => $assignedContentId,              // Assigned content for fallback or if no preview
                                                    'previewContentData' => $currentPreviewData,    // The actual preview data
                                                    // Ensure all required parameters for BaseWidget's mount are present
                                                    // 'initialData' might be needed if previewContentData is null and contentId is also null
                                                ], key($widgetKey))
                                            @else
                                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">
                                                    @if(empty($zone['widget_type']))
                                                        No widget type selected for this zone.
                                                    @else
                                                        Preview for '{{ $zone['widget_type'] }}' not available here or component not mapped.
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

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
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (seconds)</label>
                                        <input type="number" wire:model.blur="zoneSettings.{{ $zoneId }}.duration" min="1" max="3600"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Background Color</label>
                                        <input type="color" wire:model.live="zoneSettings.{{ $zoneId }}.background"
                                            class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Padding</label>
                                        <input type="text" wire:model.blur="zoneSettings.{{ $zoneId }}.padding" placeholder="e.g., 10px or 0px"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Border Radius</label>
                                        <input type="text" wire:model.blur="zoneSettings.{{ $zoneId }}.border-radius" placeholder="e.g., 5px or 0px"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                    </div>

                                    @if(isset($zone['widget_type']) && !empty($zone['widget_type']))
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
                                    @endif
                                </div>

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

        <div class="mt-8">
            <button wire:click="addZone"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <x-heroicon-s-plus-circle class="h-5 w-5 mr-2" />
                Add New Zone
            </button>
        </div>
    </div>

    {{-- Generic Content Selector Modal --}}
    @if($showGenericContentSelectorModal && $currentZoneIdForGenericSelector)
        <livewire:content.templates.components.generic-content-selector-modal 
            :zone-id="$currentZoneIdForGenericSelector" 
            :allowed-content-types="$this->getZoneContentTypes($currentZoneIdForGenericSelector)" />
    @endif

    {{-- Widget Data Editor Modal (placeholder, actual modal is separate Livewire component) --}}
    {{-- This is invoked via event, not direct wire:model --}}
    <livewire:content.widgets.widget-data-editor-modal />

    {{-- Widget Data Info Modal --}}
    <x-modal wire:model="showWidgetDataInfoModal" maxWidth="lg">
        <x-slot name="title">
            Widget Zone Information
        </x-slot>

        <x-slot name="content">
            @if($currentZoneIdForWidgetInfoModal && isset($zones[$currentZoneIdForWidgetInfoModal]))
                @php $infoZone = $zones[$currentZoneIdForWidgetInfoModal]; @endphp
                <div class="space-y-4">
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200">Zone Name:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $infoZone['name'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200">Widget Type:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $infoZone['widget_type'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200">Assigned Content ID:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $infoZone['content_id'] ?? 'None' }}</p>
                        @if(isset($zoneContent[$currentZoneIdForWidgetInfoModal]))
                            <p class="text-xs text-gray-500 dark:text-gray-500">({{ $zoneContent[$currentZoneIdForWidgetInfoModal]->name }} - {{ $zoneContent[$currentZoneIdForWidgetInfoModal]->type->label() }})</p>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200">Current Settings:</h4>
                        <pre class="text-xs p-2 bg-gray-100 dark:bg-gray-900 rounded overflow-x-auto">{{ json_encode($zoneSettings[$currentZoneIdForWidgetInfoModal] ?? [], JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400">No zone selected or zone data not found.</p>
            @endif
        </x-slot>

        <x-slot name="footer">
            <button type="button" wire:click="closeWidgetInfoModal()"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600">
                Close
            </button>
        </x-slot>
    </x-modal>
</div>

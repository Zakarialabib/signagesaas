<div>
    <!-- Header -->
    <div class="sm:flex sm:items-center mb-8">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $template ? 'Edit Template' : 'Create Template' }}
            </h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                {{ $template ? 'Update the template details below.' : 'Create a new template for your digital signage content.' }}
            </p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-300 dark:ring-gray-700 sm:rounded-lg">
            <!-- Basic Information -->
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Name
                        </label>
                        <div class="mt-2">
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="Enter template name"
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="description" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Description
                        </label>
                        <div class="mt-2">
                            <textarea
                                id="description"
                                wire:model="description"
                                rows="3"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="Enter template description"
                            ></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="category" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Category
                        </label>
                        <div class="mt-2">
                            <select
                                id="category"
                                wire:model="category"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                                <option value="">Select a category</option>
                                @foreach($this->categories as $category)
                                    <option value="{{ $category['value'] }}">{{ $category['label'] }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Status
                        </label>
                        <div class="mt-2">
                            <select
                                id="status"
                                wire:model="status"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                                @foreach($this->statuses as $status)
                                    <option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="default_duration" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Default Duration (seconds)
                        </label>
                        <div class="mt-2">
                            <input
                                type="number"
                                id="default_duration"
                                wire:model="default_duration"
                                min="1"
                                max="3600"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                            @error('default_duration')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="preview_image" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Preview Image
                        </label>
                        <div class="mt-2 flex items-center gap-x-3">
                            @if($template?->preview_image)
                                <div class="relative">
                                    <img
                                        src="{{ $template->getPreviewImageUrl() }}"
                                        alt="{{ $template->name }}"
                                        class="h-32 w-32 object-cover rounded-lg"
                                    >
                                    <button
                                        type="button"
                                        wire:click="confirmDeletePreview"
                                        class="absolute -top-2 -right-2 rounded-full bg-red-600 p-1 text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                                    >
                                        <x-heroicon-m-x-mark class="h-4 w-4" />
                                    </button>
                                </div>
                            @endif

                            <input
                                type="file"
                                id="preview_image"
                                wire:model="preview_image"
                                class="block w-full text-sm text-gray-900 dark:text-gray-100"
                                accept="image/*"
                            >
                            @error('preview_image')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Upload a preview image (max 5MB). Recommended size: 1920x1080px.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Layout and Styles -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-6 space-y-6">
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                    <div>
                        <label for="layout" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Layout
                        </label>
                        <div class="mt-2">
                            <textarea
                                id="layout"
                                wire:model="layout"
                                rows="6"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-mono"
                                placeholder="Enter layout JSON"
                            ></textarea>
                            @error('layout')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="styles" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Styles
                        </label>
                        <div class="mt-2">
                            <textarea
                                id="styles"
                                wire:model="styles"
                                rows="6"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-mono"
                                placeholder="Enter styles JSON"
                            ></textarea>
                            @error('styles')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="settings" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Settings
                        </label>
                        <div class="mt-2">
                            <textarea
                                id="settings"
                                wire:model="settings"
                                rows="6"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-mono"
                                placeholder="Enter settings JSON"
                            ></textarea>
                            @error('settings')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zone Management -->
            <div class="mt-8">
                <style>
                    .resize-handle {
                        @apply bg-white dark:bg-gray-800 border-2 border-indigo-500 dark:border-indigo-400 rounded-full shadow-sm opacity-0 transition-opacity duration-200;
                    }
                    
                    .zone-container:hover .resize-handle {
                        @apply opacity-100;
                    }
                    
                    .zone-container {
                        @apply transition-all duration-200 ease-in-out;
                    }
                    
                    .zone-container:hover {
                        @apply shadow-lg;
                    }
                    
                    .zone-container.dragging {
                        @apply shadow-xl opacity-75 cursor-move;
                    }
                    
                    .zone-container.resizing {
                        @apply shadow-xl;
                    }
                </style>

                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Zones</h3>
                        <div class="flex items-center space-x-2">
                            <button type="button"
                                wire:click="toggleGridSnap"
                                class="inline-flex items-center px-2 py-1 text-sm rounded-md"
                                :class="{ 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300': snapToGrid, 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300': !snapToGrid }"
                                x-data="{ snapToGrid: @entangle('snapToGrid') }">
                                <x-heroicon-s-viewfinder class="h-4 w-4 mr-1" />
                                Snap to Grid
                            </button>
                            <select wire:model.live="gridSize" 
                                class="text-sm rounded-md border-0 py-1 pl-2 pr-8 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-indigo-600 dark:bg-gray-800 dark:text-gray-100">
                                <option value="5">5% Grid</option>
                                <option value="10">10% Grid</option>
                                <option value="20">20% Grid</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" wire:click="addZone"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <x-heroicon-s-plus class="h-4 w-4 mr-1" />
                        Add Zone
                    </button>
                </div>

                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <!-- Grid Background -->
                    <div class="absolute inset-0 pointer-events-none"
                        x-show="$wire.snapToGrid"
                        x-data
                        style="background-size: {{ $gridSize }}% {{ $gridSize }}%;
                               background-image: linear-gradient(to right, rgba(156, 163, 175, 0.1) 1px, transparent 1px),
                                              linear-gradient(to bottom, rgba(156, 163, 175, 0.1) 1px, transparent 1px);">
                    </div>

                    <div class="p-4" style="aspect-ratio: 16/9;">
                        @if($layout && isset($layout['zones']))
                            @foreach($layout['zones'] as $index => $zone)
                                <div class="absolute border-2 border-dashed rounded-lg p-2 zone-container"
                                    style="left: {{ $zone['x'] }}%; top: {{ $zone['y'] }}%; width: {{ $zone['width'] }}%; height: {{ $zone['height'] }}%;"
                                    x-data="{ 
                                        dragging: false,
                                        resizing: false,
                                        resizeHandle: null,
                                        startX: 0,
                                        startY: 0,
                                        startWidth: {{ $zone['width'] }},
                                        startHeight: {{ $zone['height'] }},
                                        startLeft: {{ $zone['x'] }},
                                        startTop: {{ $zone['y'] }},
                                        nudgeZone(direction, event) {
                                            const shift = event.shiftKey ? 10 : 1;
                                            const changes = {
                                                ArrowLeft: { x: -shift },
                                                ArrowRight: { x: shift },
                                                ArrowUp: { y: -shift },
                                                ArrowDown: { y: shift }
                                            }[direction];
                                            
                                            if (changes) {
                                                event.preventDefault();
                                                $wire.updateZone({{ $index }}, changes);
                                            }
                                        }
                                    }"
                                    tabindex="0"
                                    @keydown="nudgeZone($event.key, $event)"
                                    @mousedown.prevent="
                                        if ($event.target === $el) {
                                            dragging = true;
                                            startX = $event.clientX;
                                            startY = $event.clientY;
                                            $el.focus();
                                        }
                                    "
                                    @mousemove.window="
                                        if (dragging) {
                                            const dx = ($event.clientX - startX) / $el.parentElement.offsetWidth * 100;
                                            const dy = ($event.clientY - startY) / $el.parentElement.offsetHeight * 100;
                                            startX = $event.clientX;
                                            startY = $event.clientY;
                                            
                                            const newX = Math.max(0, Math.min(100 - {{ $zone['width'] }}, {{ $zone['x'] }} + dx));
                                            const newY = Math.max(0, Math.min(100 - {{ $zone['height'] }}, {{ $zone['y'] }} + dy));
                                            
                                            $wire.updateZone({{ $index }}, { x: newX, y: newY });
                                        }
                                        if (resizing) {
                                            const parentRect = $el.parentElement.getBoundingClientRect();
                                            const dx = ($event.clientX - startX) / parentRect.width * 100;
                                            const dy = ($event.clientY - startY) / parentRect.height * 100;
                                            
                                            if (resizeHandle.includes('e')) {
                                                const newWidth = Math.max(10, Math.min(100 - startLeft, startWidth + dx));
                                                $wire.updateZone({{ $index }}, { width: newWidth });
                                            }
                                            if (resizeHandle.includes('s')) {
                                                const newHeight = Math.max(10, Math.min(100 - startTop, startHeight + dy));
                                                $wire.updateZone({{ $index }}, { height: newHeight });
                                            }
                                            if (resizeHandle.includes('w')) {
                                                const maxDx = startWidth - 10;
                                                const newDx = Math.max(-startLeft, Math.min(maxDx, dx));
                                                const newWidth = startWidth - newDx;
                                                const newX = startLeft + newDx;
                                                $wire.updateZone({{ $index }}, { width: newWidth, x: newX });
                                            }
                                            if (resizeHandle.includes('n')) {
                                                const maxDy = startHeight - 10;
                                                const newDy = Math.max(-startTop, Math.min(maxDy, dy));
                                                const newHeight = startHeight - newDy;
                                                const newY = startTop + newDy;
                                                $wire.updateZone({{ $index }}, { height: newHeight, y: newY });
                                            }
                                        }
                                    "
                                    @mouseup.window="dragging = false; resizing = false;"
                                    @class([
                                        'border-blue-300 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20' => $zone['type'] === 'content',
                                        'border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20' => $zone['type'] === 'widget',
                                        'border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20' => $zone['type'] === 'ticker',
                                        'border-purple-300 dark:border-purple-700 bg-purple-50 dark:bg-purple-900/20' => $zone['type'] === 'media',
                                    ])>
                                    <!-- Zone Content -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <input type="text" class="text-sm bg-transparent border-0 p-0 w-32"
                                                wire:model.blur="layout.zones.{{ $index }}.name"
                                                placeholder="Zone Name">
                                            
                                            <div class="flex items-center space-x-1">
                                                <button type="button" 
                                                    wire:click="moveZoneUp({{ $index }})"
                                                    @class([
                                                        'p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700',
                                                        'opacity-50 cursor-not-allowed' => $index === 0
                                                    ])
                                                    @disabled="$index === 0">
                                                    <x-heroicon-s-chevron-up class="h-3 w-3" />
                                                </button>
                                                <button type="button" 
                                                    wire:click="moveZoneDown({{ $index }})"
                                                    @class([
                                                        'p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700',
                                                        'opacity-50 cursor-not-allowed' => $index === (count($layout['zones'] ?? []) - 1)
                                                    ])
                                                    @disabled="$index === (count($layout['zones'] ?? []) - 1)">
                                                    <x-heroicon-s-chevron-down class="h-3 w-3" />
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <select class="text-xs bg-transparent border-0 p-0"
                                                wire:model.live="layout.zones.{{ $index }}.type">
                                                <option value="content">Content</option>
                                                <option value="widget">Widget</option>
                                                <option value="ticker">Ticker</option>
                                                <option value="media">Media</option>
                                            </select>

                                            <div x-data="{ open: false }" class="relative">
                                                <button type="button"
                                                    @click="open = !open"
                                                    class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <x-heroicon-s-ellipsis-vertical class="h-4 w-4" />
                                                </button>
                                                
                                                <div x-show="open"
                                                    @click.away="open = false"
                                                    class="absolute right-0 z-10 mt-1 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                    role="menu">
                                                    <div class="py-1" role="none">
                                                        <button type="button"
                                                            wire:click="bringToFront({{ $index }})"
                                                            class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                                            role="menuitem">
                                                            Bring to Front
                                                        </button>
                                                        <button type="button"
                                                            wire:click="sendToBack({{ $index }})"
                                                            class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                                            role="menuitem">
                                                            Send to Back
                                                        </button>
                                                        <button type="button"
                                                            wire:click="removeZone({{ $index }})"
                                                            class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                                                            role="menuitem">
                                                            Delete Zone
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Resize Handles -->
                                    <div class="absolute inset-0 pointer-events-none">
                                        <div class="absolute -top-1 -left-1 w-3 h-3 cursor-nw-resize pointer-events-auto resize-handle"
                                            @mousedown.prevent="resizing = true; resizeHandle = 'nw'; startX = $event.clientX; startY = $event.clientY;"></div>
                                        <div class="absolute -top-1 -right-1 w-3 h-3 cursor-ne-resize pointer-events-auto resize-handle"
                                            @mousedown.prevent="resizing = true; resizeHandle = 'ne'; startX = $event.clientX; startY = $event.clientY;"></div>
                                        <div class="absolute -bottom-1 -left-1 w-3 h-3 cursor-sw-resize pointer-events-auto resize-handle"
                                            @mousedown.prevent="resizing = true; resizeHandle = 'sw'; startX = $event.clientX; startY = $event.clientY;"></div>
                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 cursor-se-resize pointer-events-auto resize-handle"
                                            @mousedown.prevent="resizing = true; resizeHandle = 'se'; startX = $event.clientX; startY = $event.clientY;"></div>
                                        
                                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -mt-1 w-3 h-3 cursor-n-resize pointer-events-auto resize-handle"
                                            @mousedown.prevent="resizing = true; resizeHandle = 'n'; startX = $event.clientX; startY = $event.clientY;"></div>
                                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 -mb-1 w-3 h-3 cursor-s-resize pointer-events-auto resize-handle"
                                            @mousedown.prevent="resizing = true; resizeHandle = 's'; startX = $event.clientX; startY = $event.clientY;"></div>
                                        <div class="absolute left-0 top-1/2 -translate-y-1/2 -ml-1 w-3 h-3 cursor-w-resize pointer-events-auto resize-handle"
                                            @mousedown.prevent="resizing = true; resizeHandle = 'w'; startX = $event.clientX; startY = $event.clientY;"></div>
                                        <div class="absolute right-0 top-1/2 -translate-y-1/2 -mr-1 w-3 h-3 cursor-e-resize pointer-events-auto resize-handle"
                                            @mousedown.prevent="resizing = true; resizeHandle = 'e'; startX = $event.clientX; startY = $event.clientY;"></div>
                                    </div>

                                    <!-- Zone Size Display -->
                                    <div class="absolute bottom-1 right-1 text-xs text-gray-500">
                                        {{ number_format($zone['width'], 0) }}% × {{ number_format($zone['height'], 0) }}%
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center justify-center h-full">
                                <p class="text-gray-500 dark:text-gray-400">Add zones to customize your template layout</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4 sm:px-6 flex justify-end space-x-3">
                <button
                    type="button"
                    wire:click="$dispatch('close-modal')"
                    class="inline-flex justify-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    {{ $template ? 'Update Template' : 'Create Template' }}
                </button>
            </div>
        </div>
    </form>

    <!-- Delete Preview Modal -->
    <x-modal wire:model="showDeletePreviewModal" maxWidth="sm" id="delete-preview-modal" title="Delete Preview Image"> 
        <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-red-600" />
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">
                    Delete Preview Image
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete the preview image? This action cannot be undone.
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button
                wire:click="deletePreview"
                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
            >
                Delete
            </button>
            <button
                wire:click="cancelDeletePreview"
                class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto"
            >
                Cancel
            </button>
        </div>
    </x-modal>
</div>
<div>
    <div x-data="zoneEditor({
        zones: @js($layout['zones'] ?? []),
        width: {{ $layout['width'] ?? 1920 }},
        height: {{ $layout['height'] ?? 1080 }},
        isFullscreen: false,
        allContent: @js($allContent ?? []),
    })" class="relative w-full"
        :class="isFullscreen ? 'fixed inset-0 z-50 bg-black bg-opacity-80 flex items-center justify-center' : ''"
        style="aspect-ratio: 16/9;">
        <!-- Controls -->
        <div class="absolute top-4 right-4 z-20 flex space-x-2">
            <button @click="isFullscreen = !isFullscreen"
                class="rounded-full bg-gray-800/50 p-2 text-white hover:bg-gray-800/75 focus:outline-none">
                <template x-if="isFullscreen">
                    <x-heroicon-s-arrows-pointing-in class="h-5 w-5" />
                </template>
                <template x-if="!isFullscreen">
                    <x-heroicon-s-arrows-pointing-out class="h-5 w-5" />
                </template>
            </button>
            <button @click="saveZones"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Save Layout
            </button>
        </div>

        <!-- Preview Container -->
        <div class="relative w-full h-full bg-gray-900 rounded-lg overflow-hidden" style="aspect-ratio: 16/9;">
            <template x-for="(zone, zoneId) in zones" :key="zoneId">
                <div class="absolute bg-white dark:bg-gray-800 border border-indigo-500 rounded shadow cursor-move group"
                    :style="`left: ${zone.x}%; top: ${zone.y}%; width: ${zone.width}%; height: ${zone.height}%;`"
                    @mousedown="startDrag($event, zoneId)" @touchstart="startDrag($event, zoneId)">
                    <!-- Content Preview -->
                    <div
                        class="w-full h-full flex flex-col items-center justify-center text-xs text-gray-700 dark:text-gray-200">
                        <span x-text="zone.content?.name || `Zone ${zoneId}`"></span>
                        <button class="mt-2 px-2 py-1 rounded bg-indigo-500 text-white text-xs hover:bg-indigo-600"
                            @click.stop="openContentModal(zoneId)" type="button">Assign Content</button>
                    </div>
                    <!-- Resize Handle -->
                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-indigo-500 rounded-br cursor-se-resize z-10"
                        @mousedown.stop="startResize($event, zoneId)" @touchstart.stop="startResize($event, zoneId)">
                    </div>
                </div>
            </template>
        </div>

        <!-- Content Assignment Modal -->
        <div x-show="showContentModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;"
            @keydown.escape.window="closeContentModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Assign Content</h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    <template x-for="content in allContent" :key="content.id">
                        <button class="w-full text-left px-3 py-2 rounded hover:bg-indigo-100 dark:hover:bg-indigo-900"
                            @click="assignContentToZone(selectedZoneId, content)" type="button">
                            <span class="font-medium" x-text="content.name"></span>
                            <span class="ml-2 text-xs text-gray-500" x-text="content.type"></span>
                        </button>
                    </template>
                </div>
                <div class="mt-4 flex justify-end">
                    <button @click="closeContentModal"
                        class="rounded bg-gray-200 dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    @php use App\Tenant\Models\Zone; @endphp
    <div x-data="{ dragging: null, offset: { x: 0, y: 0 } }" class="space-y-8">
        <!-- Page Header -->
        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Screen Layouts & Zones</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Manage layouts and zones for your digital signage screens. Drag and resize zones to customize your layout.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex sm:space-x-3">
                <button wire:click="addZone" type="button"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Add Zone
                </button>
                <button wire:click="saveLayout" type="button"
                    class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                    Save Layout
                </button>
            </div>
        </div>
        <!-- Layout Selection as Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            @foreach ($layouts as $layout)
                <div 
                    wire:click="selectLayout('{{ $layout->id }}')"
                    @class([
                        'cursor-pointer rounded-lg border p-4 transition-all',
                        'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' => $selectedLayout && $selectedLayout->id === $layout->id,
                        'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700' => !$selectedLayout || $selectedLayout->id !== $layout->id,
                    ])
                >
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $layout->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $layout->description }}</p>
                    <div class="mt-4 text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-medium">Zones:</span> {{ $layout->zones->count() }}
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-2/3 order-2 md:order-1">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Layout Preview</h2>
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg min-h-[400px] overflow-hidden"
                    style="aspect-ratio: 16/9;">
                    <template x-for="(zone, idx) in $wire.zones" :key="idx">
                        <div :style="{
                            position: 'absolute',
                            left: zone.x + '%',
                            top: zone.y + '%',
                            width: zone.width + '%',
                            height: zone.height + '%',
                            zIndex: 10 + idx,
                        }"
                            :class="{
                                'rounded-lg border-2 border-dashed p-2 flex flex-col justify-between cursor-move': true,
                                'border-blue-300 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20': zone.type === 'content',
                                'border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20': zone.type === 'widget',
                                'border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20': zone.type === 'ticker',
                                'border-purple-300 dark:border-purple-700 bg-purple-50 dark:bg-purple-900/20': zone.type === 'media',
                            }"
                            tabindex="0" role="region" aria-label="Zone: "
                            @mousedown.prevent="dragging = idx; offset.x = $event.offsetX; offset.y = $event.offsetY"
                            @mouseup="dragging = null"
                            @mousemove.window="if (dragging !== null) { $wire.call('updateZone', dragging, { x: Math.max(0, Math.min(100, ($event.clientX - $el.parentElement.getBoundingClientRect().left - offset.x) / $el.parentElement.offsetWidth * 100)), y: Math.max(0, Math.min(100, ($event.clientY - $el.parentElement.getBoundingClientRect().top - offset.y) / $el.parentElement.offsetHeight * 100)) }); }">
                            <div class="flex items-center justify-between mb-1">
                                <input type="text"
                                    class="bg-transparent border-0 text-sm font-medium text-gray-900 dark:text-gray-100 w-1/2"
                                    x-model="zone.name" @change="$wire.call('updateZone', idx, { name: zone.name })">
                                <select
                                    class="text-xs rounded-md border-0 py-1 pl-2 pr-6 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-indigo-600 dark:bg-gray-700 dark:text-gray-100"
                                    x-model="zone.type" @change="$wire.call('updateZone', idx, { type: zone.type })">
                                    <option value="content">Content</option>
                                    <option value="widget">Widget</option>
                                    <option value="ticker">Ticker</option>
                                    <option value="media">Media</option>
                                </select>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ zone . width }}% x {{ zone . height }}%</div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="w-full md:w-1/3 order-1 md:order-2">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Selected Layout</h2>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $selectedLayout ? $selectedLayout->name : 'No layout selected' }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Zones: {{ $zones ? count($zones) : 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $selectedLayout ? $selectedLayout->description : '' }}</div>
                </div>
            </div>
        </div>
        <!-- Zone Type Legend -->
        <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-300 dark:ring-gray-700 rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Zone Types</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-lg border-2 border-dashed border-blue-300 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20 p-4">
                        <span class="font-medium text-gray-900 dark:text-gray-100">Content Zone</span>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Primary content display areas</p>
                    </div>
                    <div class="rounded-lg border-2 border-dashed border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20 p-4">
                        <span class="font-medium text-gray-900 dark:text-gray-100">Widget Zone</span>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Interactive or dynamic elements</p>
                    </div>
                    <div class="rounded-lg border-2 border-dashed border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20 p-4">
                        <span class="font-medium text-gray-900 dark:text-gray-100">Ticker Zone</span>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Scrolling text or news feeds</p>
                    </div>
                    <div class="rounded-lg border-2 border-dashed border-purple-300 dark:border-purple-700 bg-purple-50 dark:bg-purple-900/20 p-4">
                        <span class="font-medium text-gray-900 dark:text-gray-100">Media Zone</span>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Video and image content</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
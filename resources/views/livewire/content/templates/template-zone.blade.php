<div>
    <div class="relative h-full w-full border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden"
        x-data="{ showControls: false }" @mouseover="showControls = true" @mouseleave="showControls = false">

        {{-- Zone Content Preview --}}
        <div class="h-full w-full p-4 flex items-center justify-center">
            @if ($content)
                <div class="w-full h-full">
                    @if ($content->type === 'image')
                        <img src="{{ $content->image_url }}" alt="{{ $content->name }}"
                            class="w-full h-full object-contain">
                    @elseif($content->type === 'video')
                        <video src="{{ $content->video_url }}" class="w-full h-full object-contain" controls></video>
                    @elseif($content->type === 'widget')
                        @livewire($content->widget_type, ['settings' => $content->widget_data], key('widget-' . $content->id))
                    @else
                        <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                            <span>{{ $content->name }}</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-photo class="mx-auto h-12 w-12 mb-2" />
                    <p>No content assigned</p>
                    <p class="text-sm">Click to assign content</p>
                </div>
            @endif
        </div>

        {{-- Zone Controls --}}
        <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity duration-200" x-show="showControls"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="absolute inset-0 flex items-center justify-center space-x-2">
                <button wire:click="$dispatch('select-content', { zoneId: '{{ $zone['id'] }}' })"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <x-heroicon-s-plus class="-ml-1 mr-2 h-5 w-5" />
                    {{ $content ? 'Change Content' : 'Add Content' }}
                </button>

                @if ($content)
                    <button wire:click="$dispatch('remove-content', { zoneId: '{{ $zone['id'] }}' })"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <x-heroicon-s-trash class="-ml-1 mr-2 h-5 w-5" />
                        Remove
                    </button>
                @endif
            </div>

            {{-- Zone Label --}}
            <div class="absolute top-2 left-2 px-2 py-1 bg-black bg-opacity-75 rounded text-white text-sm">
                {{ $zone['name'] ?? 'Unnamed Zone' }}
            </div>
        </div>
    </div>
</div>

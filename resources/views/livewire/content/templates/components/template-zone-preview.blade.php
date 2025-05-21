{{-- Template Zone Preview Component --}}
<div class="template-zone-preview relative"
    x-data="{
        previewMode: false,
        togglePreview() {
            this.previewMode = !this.previewMode;
        }
    }">
    {{-- Zone Header --}}
    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $zone['name'] }}</span>
            <span class="inline-flex items-center rounded-md bg-{{ $zone['type'] === 'content' ? 'blue' : ($zone['type'] === 'widget' ? 'green' : 'purple') }}-50 px-2 py-1 text-xs font-medium text-{{ $zone['type'] === 'content' ? 'blue' : ($zone['type'] === 'widget' ? 'green' : 'purple') }}-700 ring-1 ring-inset ring-{{ $zone['type'] === 'content' ? 'blue' : ($zone['type'] === 'widget' ? 'green' : 'purple') }}-700/10">
                {{ ucfirst($zone['type']) }}
            </span>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" @click="togglePreview()"
                class="inline-flex items-center p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <template x-if="!previewMode">
                    <x-heroicon-m-eye class="h-4 w-4" />
                </template>
                <template x-if="previewMode">
                    <x-heroicon-m-eye-slash class="h-4 w-4" />
                </template>
            </button>
        </div>
    </div>

    {{-- Zone Content --}}
    <div class="p-4" :class="{ 'bg-gray-100 dark:bg-gray-900': !previewMode }">
        <div class="aspect-w-16 aspect-h-9 overflow-hidden rounded-lg bg-white dark:bg-gray-800">
            <template x-if="!previewMode">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <x-heroicon-o-cube-transparent class="mx-auto h-12 w-12 text-gray-400" />
                        <p class="mt-2 text-sm text-gray-500">This is a placeholder for {{ $zone['type'] }} content</p>
                    </div>
                </div>
            </template>
            <template x-if="previewMode && $wire.hasContent">
                {{-- Content Preview Component goes here --}}
                @if($content)
                    @include('livewire.content.templates.components.content-preview', ['content' => $content])
                @endif
            </template>
        </div>
    </div>
</div>

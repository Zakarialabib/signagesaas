@props([
    'id',
    'title' => 'Content Preview',
    'maxWidth' => '4xl',
    'aspectRatio' => '16:9', // Default aspect ratio for digital signage
    'contentType' => null, // image, video, html, url
    'contentUrl' => null,
    'autoplay' => false,
    'controls' => true,
    'fullscreen' => true,
    'openInNewTab' => true,
])

<x-modal :id="$id" :title="$title" :maxWidth="$maxWidth">
    <div 
        x-data="{
            fullscreen: false,
            toggleFullscreen() {
                this.fullscreen = !this.fullscreen;
                if (this.fullscreen) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            },
            openInNewTab() {
                const url = '{{ $contentUrl }}';
                if (url) {
                    window.open(url, '_blank');
                }
            }
        }"
        :class="{ 
            'fixed inset-0 z-60 bg-black': fullscreen,
            'bg-gray-100 border border-gray-300 rounded-md p-4': !fullscreen
        }"
        x-on:close-modal.window="if ($event.detail === '{{ $id }}' && fullscreen) {
            fullscreen = false;
            document.body.classList.remove('overflow-hidden');
        }"
    >
        <div :class="{
            'aspect-w-16 aspect-h-9 bg-white rounded-md shadow-sm overflow-hidden': !fullscreen,
            'w-full h-full flex items-center justify-center': fullscreen
        }">
            @if($slot->isEmpty() && $contentType && $contentUrl)
                @if($contentType === 'image')
                    <img src="{{ $contentUrl }}" class="w-full h-full object-contain" alt="Content Preview">
                @elseif($contentType === 'video')
                    <video 
                        class="w-full h-full object-contain" 
                        @if($controls) controls @endif
                        @if($autoplay) autoplay @endif
                    >
                        <source src="{{ $contentUrl }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @elseif($contentType === 'html')
                    <div class="w-full h-full bg-white p-4 overflow-auto">
                        {!! $contentUrl !!}
                    </div>
                @elseif($contentType === 'url')
                    <iframe class="w-full h-full" src="{{ $contentUrl }}" frameborder="0" allowfullscreen></iframe>
                @endif
            @else
                {{ $slot }}
            @endif
        </div>
        
        @if($fullscreen || $openInNewTab)
        <div class="absolute top-2 right-2 flex space-x-2 z-61">
            @if($fullscreen)
            <button 
                x-on:click="toggleFullscreen()" 
                class="p-1 rounded-full bg-gray-800 bg-opacity-50 text-white hover:bg-opacity-70 focus:outline-none"
                :aria-label="fullscreen ? 'Exit fullscreen' : 'Enter fullscreen'"
            >
                <template x-if="!fullscreen">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 0h-4m4 0l-5-5" />
                    </svg>
                </template>
                <template x-if="fullscreen">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
            </button>
            @endif
            
            @if($openInNewTab && $contentUrl)
            <button 
                x-on:click="openInNewTab()" 
                class="p-1 rounded-full bg-gray-800 bg-opacity-50 text-white hover:bg-opacity-70 focus:outline-none"
                aria-label="Open in new tab"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </button>
            @endif
        </div>
        @endif
    </div>
    
    <x-slot name="footer">
        <div class="flex justify-end">
            <x-button 
                color="secondary" 
                x-on:click="$dispatch('close-modal', '{{ $id }}')"
            >
                Close
            </x-button>
        </div>
    </x-slot>
</x-modal> 
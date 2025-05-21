<div>
    @if ($content)        <!-- Content Preview Modal -->
        <x-modal wire:model="previewContentModal" id="preview-content-modal" 
            title="Content Preview: {{ $content->name }}"
            maxWidth="{{ $content->screen->orientation === 'landscape' ? '4xl' : '2xl' }}"
            x-on:keydown.escape.window="$wire.closeModal()">
            <div x-data="{
                initContent() {
                        // For videos, handle autoplay and controls
                        if ('{{ $content->type->value }}' === 'video') {
                            const video = this.$el.querySelector('video');
                            if (video) {
                                video.addEventListener('ended', function() {
                                    // Loop the video
                                    this.currentTime = 0;
                                    this.play();
                                });
                            }
                        }
                    },
                    cleanup() {
                        // Properly clean up resources
                        if ('{{ $content->type->value }}' === 'video') {
                            const video = this.$el.querySelector('video');
                            if (video) {
                                video.pause();
                            }
                        }
                        // Handle any iframes
                        if ('{{ $content->type->value }}' === 'url') {
                            const iframe = this.$el.querySelector('iframe');
                            if (iframe) {
                                iframe.src = iframe.src; // Reload/reset iframe
                            }
                        }
                    },
                    openInNewTab() {
                        @if ($content->type->value === 'image' || $content->type->value === 'video') window.open('{{ $content->content_data['url'] ?? '' }}', '_blank');
                @elseif($content->type->value === 'url')
                    window.open('{{ $content->content_data['url'] ?? '' }}', '_blank'); @endif
                    }
            }" x-init="initContent()" x-on:close-modal.window="cleanup()"
                class="w-full h-full relative">
                @if ($content->type->value === 'image')
                    <img src="{{ $content->content_data['url'] ?? '' }}" class="w-full h-full object-contain"
                        alt="{{ $content->name }}">
                @elseif($content->type->value === 'video')
                    <video class="w-full h-full object-contain" controls>
                        <source src="{{ $content->content_data['url'] ?? '' }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>                @elseif($content->type->value === 'html')
                    <div class="w-full h-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-4 overflow-auto">
                        {{-- {!! $content->content_data['html'] ?? '' !!} --}}
                        <iframe class="w-full h-full bg-white dark:bg-gray-800" src="{{ asset('html-example.html') }}" frameborder="0"
                            allowfullscreen></iframe>
                    </div>
                @elseif($content->type->value === 'url')                    
                <iframe class="w-full h-full bg-white dark:bg-gray-800" src="{{ $content->content_data['url'] ?? '' }}" frameborder="0"
                        allowfullscreen></iframe>
                @endif

                @if ($content->type->value === 'image' || $content->type->value === 'video' || $content->type->value === 'url')
                    <div class="absolute top-2 right-2">
                        <button x-on:click="openInNewTab()"
                            class="p-1 rounded-full bg-gray-800 dark:bg-gray-700 bg-opacity-50 text-white hover:bg-opacity-70 dark:hover:bg-opacity-90 focus:outline-none"
                            title="Open in new tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </x-modal>
    @endif
</div>

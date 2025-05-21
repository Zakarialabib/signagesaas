<div>
    <div
        x-data="{
            currentIndex: 0,
            contents: @js($screen?->contents ?? []),
            autoplayInterval: null,
            transition: @js($screen?->settings['transition_effect'] ?? 'fade'),
            duration: @js(($screen?->settings['transition_duration'] ?? 1000) / 1000),
            contentDuration: @js($screen?->settings['content_duration'] ?? 10),
            isPlaying: true,
            isTransitioning: false,
            
            init() {
                if (this.contents.length > 0) {
                    this.startAutoplay();
                    // Listen for updates from server
                    window.addEventListener('screen-updated', () => {
                        this.handleUpdate();
                    });
                }
                
                // Handle fullscreen behavior
                this.setupFullscreen();
            },
            
            setupFullscreen() {
                // Auto fullscreen after 2 seconds
                setTimeout(() => {
                    if (document.documentElement.requestFullscreen && !document.fullscreenElement) {
                        // document.documentElement.requestFullscreen().catch(err => {
                        //     console.error(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                        // });
                    }
                }, 2000);
                
                // Handle ESC key to exit fullscreen
                document.addEventListener('fullscreenchange', () => {
                    if (!document.fullscreenElement) {
                        // Add a return button when exiting fullscreen
                        // this.showExitButton = true; // Assuming showExitButton is defined if needed
                    }
                });
            },
            
            startAutoplay() {
                this.isPlaying = true;
                this.rotateContent(); // Start the rotation process
            },
            
            rotateContent() {
                if (!this.isPlaying || this.contents.length <= 1) return;
                
                clearTimeout(this.autoplayInterval);
                
                let currentContentAlpine = this.contents.length > 0 ? this.contents[this.currentIndex] : null;
                let displayTime = this.contentDuration; // Default duration from screen settings
                
                if (currentContentAlpine?.type === 'video') {
                    const videoElement = document.querySelector('#content-video');
                    if (videoElement && videoElement.duration && !isNaN(videoElement.duration)) {
                        displayTime = videoElement.duration;
                    } else {
                        // Fallback if video duration is not available, use default or a specific video duration setting
                        displayTime = currentContentAlpine?.pivot?.duration || this.contentDuration;
                    }
                } else if (currentContentAlpine?.pivot?.duration) {
                    // Use content-specific duration if set in pivot
                    displayTime = currentContentAlpine.pivot.duration;
                }

                this.autoplayInterval = setTimeout(() => {
                    this.isTransitioning = true;
                    setTimeout(() => {
                        this.currentIndex = (this.currentIndex + 1) % this.contents.length;
                        // Call Livewire to update its active content properties
                        $wire.updateActiveContent(this.currentIndex).then(() => {
                            this.isTransitioning = false;
                            this.rotateContent(); // Continue rotation with the new content
                        });
                    }, this.duration * 1000); // Transition duration
                }, displayTime * 1000); // Content display duration
            },
            
            pauseAutoplay() {
                this.isPlaying = false;
                clearTimeout(this.autoplayInterval);
            },
            
            handleUpdate() {
                // Reload the component from server
                $wire.loadScreen().then(() => {
                    // Update local Alpine data after Livewire has finished loading
                    this.contents = @js($screen?->contents->toArray() ?? []); // Ensure it's an array for Alpine
                    this.transition = @js($screen?->settings['transition_effect'] ?? 'fade');
                    this.duration = @js(($screen?->settings['transition_duration'] ?? 1000) / 1000);
                    this.contentDuration = @js($screen?->settings['content_duration'] ?? 10);

                    // Reset currentIndex and update Livewire's active content
                    this.currentIndex = 0;
                    if (this.contents.length > 0) {
                        $wire.updateActiveContent(this.currentIndex).then(() => {
                            this.startAutoplay();
                        });
                    } else {
                        this.startAutoplay(); // Or handle empty content state
                    }
                });
            },

            // getCurrentContent() is no longer needed as Livewire controls active content display
            // If needed for debug UI or other client-side logic, it can be kept.
            getCurrentContentForDebug() {
                return this.contents.length > 0 ? this.contents[this.currentIndex] : null;
            }
        }"
        x-init="init()"
        class="w-full h-screen overflow-hidden bg-black relative"
        {{-- Removed wire:poll as Alpine now drives content rotation and calls $wire methods --}}
        {{-- wire:poll.{{ $refreshInterval }}s="loadScreen" --}} 
        tabindex="0"
    >
        @if($screen && $activeContent)
            <!-- Content display area -->
            <div
                class="absolute inset-0 w-full h-full transition-opacity duration-1000"
                :class="{'opacity-0': isTransitioning, 'opacity-100': !isTransitioning}"
                wire:key="active-content-{{ $activeContentId }}"
            >
                @if($activeContent->type->value === 'image')
                    <img src="{{ $activeContent->content_data['url'] ?? '' }}" class="w-full h-full object-contain" alt="Screen Content" />
                @elseif($activeContent->type->value === 'video')
                    <video id="content-video" class="w-full h-full object-contain" src="{{ $activeContent->content_data['url'] ?? '' }}" autoplay loop x-on:loadedmetadata="$el.muted = true" x-on:ended="rotateContent()"></video>
                @elseif($activeContent->type->value === 'html')
                    <div class="w-full h-full bg-white p-4 overflow-auto">{!! $activeContent->content_data['html'] ?? '' !!}</div>
                @elseif($activeContent->type->value === 'url')
                    <iframe class="w-full h-full border-0" src="{{ $activeContent->content_data['url'] ?? '' }}" frameborder="0" allowfullscreen></iframe>
                @elseif($activeContent->type->value === 'custom' && $activeWidgetType)
                    <div class="w-full h-full">
                        @if($activeWidgetType === 'MenuWidget')
                            @livewire('content.widgets.menu-widget', ['contentId' => $activeContentId, 'settings' => $screen->settings['widgets']['MenuWidget'] ?? []], key('menu-widget-' . $activeContentId))
                        @elseif($activeWidgetType === 'RetailProductWidget')
                            @livewire('content.widgets.retail-product-widget', ['contentId' => $activeContentId, 'settings' => $screen->settings['widgets']['RetailProductWidget'] ?? []], key('retail-product-widget-' . $activeContentId))
                        @else
                            <div class="p-4 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200 flex flex-col items-center justify-center h-full">
                                <h3 class="font-semibold text-lg">Unsupported Custom Widget</h3>
                                <p class="mt-2 text-sm">Widget Type: {{ $activeWidgetType }}</p>
                                <p class="mt-1 text-sm">Content ID: {{ $activeContentId }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-4 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200 flex flex-col items-center justify-center h-full">
                        <h3 class="font-semibold text-lg">Unsupported Content Type</h3>
                        <p class="mt-2 text-sm">Type: {{ $activeContent->type->value }}</p>
                        <p class="mt-1 text-sm">Content ID: {{ $activeContentId }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Content Navigation/Debug UI (only visible in dev mode if needed) -->
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 flex justify-between items-center"
                x-show="!document.fullscreenElement && contents.length > 0"
            >
                <div class="text-sm" x-text="`Screen: ${@js($screen->name)} | Content Index: ${currentIndex} / ${contents.length -1} | ID: ${@js($activeContentId)}`"></div>
                <div class="flex space-x-2">
                    <button @click="pauseAutoplay()" x-show="isPlaying" class="p-1 bg-red-600 rounded text-xs">Pause</button>
                    <button @click="startAutoplay()" x-show="!isPlaying" class="p-1 bg-green-600 rounded text-xs">Play</button>
                    <button @click="currentIndex = (currentIndex - 1 + contents.length) % contents.length; $wire.updateActiveContent(currentIndex);" class="p-1 bg-blue-600 rounded text-xs">Prev</button>
                    <button @click="currentIndex = (currentIndex + 1) % contents.length; $wire.updateActiveContent(currentIndex);" class="p-1 bg-blue-600 rounded text-xs">Next</button>
                </div>
            </div>
        @else
            <!-- Error or No Content (Screen loaded but no active content, or screen itself failed to load) -->
            <div class="w-full h-full flex items-center justify-center text-white bg-gray-900">
                <div class="text-center p-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="mt-4 text-xl font-bold">Screen Not Available</h2>
                    <p class="mt-2 text-gray-400">The requested screen could not be loaded or has no content to display.</p>
                    <a href="/" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
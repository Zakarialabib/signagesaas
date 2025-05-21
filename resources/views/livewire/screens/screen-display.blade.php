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
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                }
            }, 2000);
            
            // Handle ESC key to exit fullscreen
            document.addEventListener('fullscreenchange', () => {
                if (!document.fullscreenElement) {
                    // Add a return button when exiting fullscreen
                    this.showExitButton = true;
                }
            });
        },
        
        startAutoplay() {
            this.isPlaying = true;
            this.rotateContent();
        },
        
        rotateContent() {
            if (!this.isPlaying || this.contents.length <= 1) return;
            
            clearTimeout(this.autoplayInterval);
            
            // Calculate duration based on content type
            let currentContent = this.getCurrentContent();
            let displayTime = this.contentDuration;
            
            // Adjust timing for video content
            if (currentContent?.type === 'video') {
                // For videos, try to use the video duration
                const videoElement = document.querySelector('#content-video');
                if (videoElement && videoElement.duration) {
                    displayTime = videoElement.duration;
                }
            }
            
            this.autoplayInterval = setTimeout(() => {
                this.isTransitioning = true;
                
                setTimeout(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.contents.length;
                    this.isTransitioning = false;
                    this.rotateContent();
                }, this.duration * 1000);
            }, displayTime * 1000);
        },
        
        pauseAutoplay() {
            this.isPlaying = false;
            clearTimeout(this.autoplayInterval);
        },
        
        handleUpdate() {
            // Reload the component from server
            $wire.loadScreen();
            
            // Update local data
            this.contents = @js($screen?->contents ?? []);
            this.transition = @js($screen?->settings['transition_effect'] ?? 'fade');
            this.duration = @js(($screen?->settings['transition_duration'] ?? 1000) / 1000);
            this.contentDuration = @js($screen?->settings['content_duration'] ?? 10);
            
            // Restart autoplay
            this.startAutoplay();
        },
        
        getCurrentContent() {
            return this.contents.length > 0 ? this.contents[this.currentIndex] : null;
        }
    }"
    x-init="init()"
    class="w-full h-screen overflow-hidden bg-black relative"
    wire:poll.{{ $refreshInterval }}s="loadScreen"
    tabindex="0"
>
    @if($screen)
        <!-- Content display area -->
        <div 
            :class="{
                'absolute inset-0 w-full h-full transition-opacity duration-1000': true,
                'opacity-0': isTransitioning
            }"
        >
            <!-- Image Content -->
            <template x-if="getCurrentContent()?.type === 'image'">
                <img 
                    :src="getCurrentContent()?.content_data?.url" 
                    class="w-full h-full object-contain"
                    alt="Screen Content"
                />
            </template>

            <!-- Video Content -->
            <template x-if="getCurrentContent()?.type === 'video'">
                <video 
                    id="content-video"
                    class="w-full h-full object-contain" 
                    :src="getCurrentContent()?.content_data?.url"
                    autoplay
                    loop
                    x-on:ended="rotateContent()"
                ></video>
            </template>

            <!-- HTML Content -->
            <template x-if="getCurrentContent()?.type === 'html'">
                <div 
                    class="w-full h-full bg-white p-4 overflow-auto"
                    x-html="getCurrentContent()?.content_data?.html"
                ></div>
            </template>

            <!-- URL/Iframe Content -->
            <template x-if="getCurrentContent()?.type === 'url'">
                <iframe 
                    class="w-full h-full border-0"
                    :src="getCurrentContent()?.content_data?.url" 
                    frameborder="0"
                    allowfullscreen
                ></iframe>
            </template>
        </div>
        
        <!-- Content Navigation/Debug UI (only visible in dev mode) -->
        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 flex justify-between items-center"
            x-show="!document.fullscreenElement"
        >
            <div class="text-sm" x-text="`Screen: ${@js($screen->name)} | Content: ${currentIndex + 1}/${contents.length}`"></div>
            <div class="flex space-x-2">
                <button @click="pauseAutoplay()" x-show="isPlaying" class="p-1 bg-red-600 rounded text-xs">Pause</button>
                <button @click="startAutoplay()" x-show="!isPlaying" class="p-1 bg-green-600 rounded text-xs">Play</button>
                <button @click="currentIndex = (currentIndex - 1 + contents.length) % contents.length" class="p-1 bg-blue-600 rounded text-xs">Prev</button>
                <button @click="currentIndex = (currentIndex + 1) % contents.length" class="p-1 bg-blue-600 rounded text-xs">Next</button>
            </div>
        </div>
    @else
        <!-- Error or No Content -->
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

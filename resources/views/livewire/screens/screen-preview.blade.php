<div>
    @if ($this->screen)
        <!-- Preview Modal -->
        <x-modal wire:model="showPreviewModal" id="preview-screen-modal-{{ $screen->id }}"
            title="Screen Preview: {{ $screen->name }}"
            maxWidth="{{ $screen->orientation === 'landscape' ? '5xl' : '3xl' }}"
            x-on:keydown.escape.window="$wire.closeModal()">
            <div x-data="{
                currentIndex: 0,
                contents: @js($this->hasContent() ? $screen->contents()->where('status', 'active')->orderBy('order')->get() : []),
                autoplayInterval: null,
                isPlaying: true,
                isTransitioning: false,
                transitionDuration: {{ $screen->settings['transition_duration'] ?? 1000 }},
                contentDuration: {{ $screen->settings['content_duration'] ?? 10000 }},
                
                init() {
                    if (this.contents.length > 0) {
                        this.startAutoplay();
                    }
                    
                    // Add event listener for cleanup
                    this.$watch('showPreviewModal', (value) => {
                        if (!value) this.cleanup();
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
                        const videoElement = this.$el.querySelector('video');
                        if (videoElement && videoElement.duration) {
                            displayTime = videoElement.duration * 1000;
                        }
                    }
                    
                    this.autoplayInterval = setTimeout(() => {
                        this.isTransitioning = true;
                        
                        setTimeout(() => {
                            this.currentIndex = (this.currentIndex + 1) % this.contents.length;
                            this.isTransitioning = false;
                            
                            // For videos, ensure they start playing
                            if (this.getCurrentContent()?.type === 'video') {
                                setTimeout(() => {
                                    const videoElement = this.$el.querySelector('video');
                                    if (videoElement) videoElement.play();
                                }, 100);
                            }
                            
                            this.rotateContent();
                        }, this.transitionDuration);
                    }, displayTime);
                },
                
                pauseAutoplay() {
                    this.isPlaying = false;
                    clearTimeout(this.autoplayInterval);
                    
                    // Pause video if currently playing
                    if (this.getCurrentContent()?.type === 'video') {
                        const videoElement = this.$el.querySelector('video');
                        if (videoElement) videoElement.pause();
                    }
                },
                
                toggleAutoplay() {
                    if (this.isPlaying) {
                        this.pauseAutoplay();
                    } else {
                        this.startAutoplay();
                    }
                },
                
                cleanup() {
                    clearTimeout(this.autoplayInterval);
                    const videoElement = this.$el.querySelector('video');
                    if (videoElement) {
                        videoElement.pause();
                    }
                },
                
                getCurrentContent() {
                    return this.contents.length > 0 ? this.contents[this.currentIndex] : null;
                }
            }" x-init="init()"
                class="w-full h-full">
                <template x-if="contents.length > 0">
                    <div class="w-full h-full flex flex-col">
                        <div class="flex-1 relative overflow-hidden bg-black">
                            <!-- Image Content -->
                            <div 
                                x-show="getCurrentContent()?.type === 'image'"
                                x-transition:enter="transition-opacity ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition-opacity ease-in duration-300"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute inset-0"
                            >
                                <img :src="getCurrentContent()?.content_data?.url"
                                    class="w-full h-full object-contain" alt="Content Preview">
                            </div>

                            <!-- Video Content -->
                            <div 
                                x-show="getCurrentContent()?.type === 'video'"
                                x-transition:enter="transition-opacity ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition-opacity ease-in duration-300"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute inset-0"
                            >
                                <video class="w-full h-full object-contain" controls>
                                    <source :src="getCurrentContent()?.content_data?.url" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>

                            <!-- HTML Content -->
                            <div 
                                x-show="getCurrentContent()?.type === 'html'"
                                x-transition:enter="transition-opacity ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition-opacity ease-in duration-300"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute inset-0 bg-white p-4 overflow-auto"
                                x-html="getCurrentContent()?.content_data?.html">
                            </div>

                            <!-- URL/Iframe Content -->
                            <div 
                                x-show="getCurrentContent()?.type === 'url'"
                                x-transition:enter="transition-opacity ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition-opacity ease-in duration-300"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute inset-0"
                            >
                                <iframe class="w-full h-full"
                                    :src="getCurrentContent()?.content_data?.url" frameborder="0"
                                    allowfullscreen></iframe>
                            </div>
                        </div>

                        <!-- Content Navigation -->
                        <div class="bg-gray-100 dark:bg-gray-800 p-2 flex justify-between items-center">
                            <div class="text-sm text-gray-700 dark:text-gray-300 flex items-center">
                                <span class="font-medium mr-2" x-text="`Item ${currentIndex + 1} of ${contents.length}`"></span>
                                <span class="truncate max-w-md" x-text="getCurrentContent()?.name"></span>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="toggleAutoplay()" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                                    :title="isPlaying ? 'Pause' : 'Play'">
                                    <template x-if="isPlaying">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </template>
                                    <template x-if="!isPlaying">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </template>
                                </button>
                                <button @click="currentIndex = (currentIndex - 1 + contents.length) % contents.length"
                                    class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click="currentIndex = (currentIndex + 1) % contents.length"
                                    class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                                <button
                                    @click="window.open('/screens/' + getCurrentContent()?.screen_id + '/preview', '_blank')"
                                    class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400" 
                                    title="Open in new tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- No Content Message -->
                <template x-if="contents.length === 0">
                    <div class="w-full h-full flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No
                                content available</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This screen has
                                no active content to display.</p>
                        </div>
                    </div>
                </template>
            </div>
        </x-modal>
    @endif
</div>

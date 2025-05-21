{{-- Template Live Preview Component --}}
<div class="template-live-preview"    x-data="{ 
        playing: @entangle('isPlaying'),
        quickPreview: @entangle('isQuickPreview'),
        currentProgress: 0,
        timeLeft: 10,
        currentZoneIndex: 0,
        zoneTimers: {},

        initZones() {
            this.setupZoneTransitions();
            this.setupKeyboardControls();
            this.setupProgressTracking();
        },

        setupProgressTracking() {
            this.$watch('playing', value => {
                if (value) {
                    this.startZoneTimer();
                } else {
                    this.clearZoneTimers();
                }
            });
        },

        startZoneTimer() {
            this.clearZoneTimers();
            const currentZone = this.$wire.playback.currentZone;
            if (!currentZone) return;

            const duration = this.$wire.zoneContents[currentZone]?.settings?.duration || 10;
            this.timeLeft = duration;
            
            this.zoneTimers.progress = setInterval(() => {
                this.currentProgress = (duration - this.timeLeft) / duration;
            }, 50);

            this.zoneTimers.main = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    this.advanceToNextZone();
                }
            }, 1000);
        },

        clearZoneTimers() {
            Object.values(this.zoneTimers).forEach(timer => clearInterval(timer));
            this.zoneTimers = {};
        },

        advanceToNextZone() {
            this.$wire.advanceZone();
        },
        setupZoneTransitions() {
            document.querySelectorAll('[data-zone-transition]').forEach(zone => {
                const settings = JSON.parse(zone.dataset.zoneSettings || '{}');
                if (settings.transition) {
                    zone.style.transition = `all ${settings.transition_duration || 500}ms ease-in-out`;
                }
            });
        },
        setupKeyboardControls() {
            window.addEventListener('keydown', (e) => {
                if (e.key === 'f') this.$wire.toggleFullscreen();
                if (e.key === ' ') this.$wire.togglePlayback();
                if (e.key === 'q') this.$wire.toggleQuickPreview();
            });
        }
    }"
    x-init="initZones()"
    @content-updated.window="setupZoneTransitions()"
    :class="{ 'fixed inset-0 z-50 bg-black': isFullscreen }">
    
    {{-- Preview Header --}}
    <div class="flex items-center justify-between mb-4" :class="{ 'p-4 bg-gray-900': isFullscreen }">
        <h3 class="text-lg font-medium" :class="{ 'text-white': isFullscreen, 'text-gray-900 dark:text-gray-100': !isFullscreen }">
            Live Preview
        </h3>
        <div class="flex items-center space-x-2">
            <button type="button" @click="playing = !playing"
                class="inline-flex items-center p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <template x-if="!playing">
                    <x-heroicon-m-play class="h-5 w-5" />
                </template>
                <template x-if="playing">
                    <x-heroicon-m-pause class="h-5 w-5" />
                </template>
            </button>
            <button type="button" wire:click="toggleFullscreen"
                class="inline-flex items-center p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <template x-if="!@entangle('isFullscreen')">
                    <x-heroicon-m-arrows-pointing-out class="h-5 w-5" />
                </template>
                <template x-if="@entangle('isFullscreen')">
                    <x-heroicon-m-arrows-pointing-in class="h-5 w-5" />
                </template>
            </button>
        </div>
    </div>

    {{-- Preview Canvas --}}
    <div class="relative bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden"
        :class="{ 'h-screen': isFullscreen, 'aspect-video': !isFullscreen }"
        :style="{ background: '{{ $template->layout['background'] ?? '#ffffff' }}' }">
        
        @foreach($template->layout['zones'] ?? [] as $zoneId => $zone)
            <div class="absolute overflow-hidden"
                style="{{ $this->getZoneStyle($zone) }}"
                data-zone-id="{{ $zoneId }}"
                data-zone-transition="{{ $zone['settings']['transition'] ?? 'none' }}"
                data-zone-settings="{{ json_encode($zone['settings'] ?? []) }}">
                
                @if(isset($zoneContents[$zoneId]))
                    <div class="w-full h-full" x-show="playing">
                        {!! $zoneContents[$zoneId]['content']->getRenderedHtml() !!}
                    </div>
                    <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-800" x-show="!playing">
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Press play to view content</p>
                        </div>
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-800">
                        <div class="text-center">
                            <x-heroicon-o-photo class="mx-auto h-8 w-8 text-gray-400" />
                            <p class="mt-1 text-sm text-gray-500">No content assigned</p>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>    

    {{-- Preview Controls --}}
    <div class="mt-4 space-y-4" x-show="!isFullscreen && showControls">
        <div class="flex flex-wrap items-center justify-between gap-4">
            {{-- Playback Controls --}}
            <div class="flex items-center space-x-4">
                <button type="button" @click="$wire.togglePlayback()"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none">
                    <template x-if="!playing">
                        <x-heroicon-s-play class="h-5 w-5 mr-2" />
                    </template>
                    <template x-if="playing">
                        <x-heroicon-s-pause class="h-5 w-5 mr-2" />
                    </template>
                    <span x-text="playing ? 'Pause' : 'Play'"></span>
                </button>
                
                <button type="button" @click="$wire.toggleQuickPreview()"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none"
                    :class="{'bg-indigo-50 dark:bg-indigo-900 border-indigo-500': quickPreview}">
                    <x-heroicon-s-eye class="h-5 w-5 mr-2" />
                    Quick Preview
                </button>
            </div>

            {{-- Preview Settings --}}
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-700 dark:text-gray-300">Transition Speed</label>
                    <select wire:model.live="previewSettings.transitionSpeed" 
                        class="block w-24 text-sm border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800">
                        <option value="200">Fast</option>
                        <option value="500">Normal</option>
                        <option value="1000">Slow</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-700 dark:text-gray-300">Auto Advance</label>
                    <input type="checkbox" wire:model.live="previewSettings.autoAdvance"
                        class="rounded text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600">
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-700 dark:text-gray-300">Loop</label>
                    <input type="checkbox" wire:model.live="previewSettings.loopPlayback"
                        class="rounded text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600">
                </div>
            </div>

            {{-- View Controls --}}
            <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-500">
                <span x-text="playing ? 'Playing' : 'Paused'"></span>
            </div>
            <div class="text-sm text-gray-500">
                Template Resolution: {{ $template->layout['width'] ?? 1920 }}x{{ $template->layout['height'] ?? 1080 }}
            </div>
        </div>

        {{-- Zone Info --}}
        <div class="grid grid-cols-2 gap-4">
            @foreach($template->layout['zones'] ?? [] as $zoneId => $zone)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $zone['name'] }}</h4>
                    @if(isset($zoneContents[$zoneId]))
                        <p class="mt-1 text-sm text-gray-500">
                            Currently playing: {{ $zoneContents[$zoneId]['content']->name }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400">
                            Duration: {{ $zoneContents[$zoneId]['settings']['duration'] ?? 10 }}s
                        </p>
                    @else
                        <p class="mt-1 text-sm text-gray-500">No content assigned</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Content Timeline --}}
    <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
        <div class="flex items-center justify-between mb-2">
            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Content Timeline</h4>
            <span class="text-xs text-gray-500" x-show="playing">
                Next transition in: <span x-text="timeLeft">0</span>s
            </span>
        </div>
        
        <div class="relative">
            {{-- Timeline Progress Bar --}}
            <div class="absolute h-1 bg-indigo-600 transition-all duration-1000" 
                :style="{ width: (currentProgress * 100) + '%' }"
                x-show="playing">
            </div>
            
            {{-- Content Thumbnails --}}
            <div class="flex space-x-4 overflow-x-auto pb-4 relative">
                @foreach($template->layout['zones'] ?? [] as $zoneId => $zone)
                    @if(isset($zoneContents[$zoneId]))
                        <div class="shrink-0 relative" 
                            :class="{ 'ring-2 ring-indigo-500 rounded-lg': playback.currentZone === '{{ $zoneId }}' }">
                            <livewire:content.templates.components.preview-thumbnail 
                                :content="$zoneContents[$zoneId]['content']"
                                :overlay="$zone['name']"
                                size="sm"
                                :show-details="false"
                                :key="'thumb-'.$zoneId" />
                            
                            {{-- Duration Badge --}}
                            <div class="absolute -bottom-2 right-0 bg-gray-900 text-white text-xs px-2 py-0.5 rounded-full">
                                {{ $zoneContents[$zoneId]['settings']['duration'] ?? 10 }}s
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Keyboard Shortcuts Help --}}
    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Keyboard Shortcuts</h4>
        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-center space-x-2">
                <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">Space</kbd>
                <span>Play/Pause</span>
            </div>
            <div class="flex items-center space-x-2">
                <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">F</kbd>
                <span>Toggle Fullscreen</span>
            </div>
            <div class="flex items-center space-x-2">
                <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">Q</kbd>
                <span>Quick Preview Mode</span>
            </div>
        </div>
    </div>
</div>

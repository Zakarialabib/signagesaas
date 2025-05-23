<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 text-slate-100 flex flex-col"
        x-data="{
            theme: 'dark',
            currentTime: '{{ $currentTime }}',
            currentDate: '{{ $currentDate }}',
            gridColumns: {{ $layoutConfig['grid']['columns'] }},
            gridRows: {{ $layoutConfig['grid']['rows'] }},
            gridGap: '{{ $layoutConfig['grid']['gap'] }}',
            animationQueue: [],
            init() {
                this.updateTime();
                setInterval(() => {
                    this.updateTime();
                }, 1000);
            },
            updateTime() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                this.currentDate = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            },
            triggerAnimation(widgetId) {
                if (!this.animationQueue.includes(widgetId)) {
                    this.animationQueue.push(widgetId);
                    setTimeout(() => {
                        this.animationQueue = this.animationQueue.filter(id => id !== widgetId);
                    }, 1500);
                }
            }
        }"
        x-init="init">

        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/90 to-slate-900/70 backdrop-blur-lg rounded-xl shadow-xl m-3 sm:m-4 p-4 sm:p-5 flex justify-between items-center border-b border-slate-700/50">
            <div class="flex items-center space-x-3">
                <!-- Logo -->
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Dynamic Display Pro</h1>
            </div>
            
            <div class="text-right">
                <div class="text-sm text-slate-400 font-medium" x-text="currentDate"></div>
                <div class="text-2xl sm:text-3xl font-bold tracking-wider" 
                    x-text="currentTime"
                    :class="animationQueue.includes('clock') ? 'animate-pulse' : ''"
                    @livewire.emit("widgetUpdated")="triggerAnimation('clock')">
                </div>
            </div>
        </header>

        <!-- Main Content Grid -->
        <div class="flex-grow grid"
            :style="{
                'grid-template-columns': `repeat(${gridColumns}, minmax(0, 1fr))`,
                'grid-template-rows': `repeat(${gridRows}, minmax(0, 1fr))`,
                'gap': `${gridGap}px`
            }"
            class="m-3 sm:m-4">

            @foreach ($layoutConfig['widgets'] as $widget)
                <div 
                    class="relative bg-slate-800/70 backdrop-blur-md rounded-xl shadow-lg overflow-hidden transform transition-all duration-500 hover:shadow-xl hover:scale-[1.01]"
                    :class="{
                        'col-span-{{ $widget['position']['col'][1] - $widget['position']['col'][0] }}': true,
                        'row-span-{{ $widget['position']['row'][1] - $widget['position']['row'][0] }}': true,
                        'border-2 border-blue-500/30': isEditMode,
                        'animate-fade-in': '{{ $widget['settings']['animation'] ?? 'fade' }}' === 'fade',
                        'animate-slide-up': '{{ $widget['settings']['animation'] ?? 'fade' }}' === 'slide-up',
                        'animate-slide-left': '{{ $widget['settings']['animation'] ?? 'fade' }}' === 'slide-left',
                        'animate-zoom': '{{ $widget['settings']['animation'] ?? 'fade' }}' === 'zoom'
                    }"
                    @livewire.emit("widgetUpdated")="triggerAnimation('{{ $widget['id'] }}')"
                    x-intersect="$el.classList.add('opacity-0', 'translate-y-4', 'animate-fade-in-up')">
                    
                    <!-- Widget Title Bar -->
                    <div class="absolute top-0 left-0 right-0 h-8 bg-slate-700/50 backdrop-blur-sm flex items-center px-3 text-xs font-medium text-slate-300 z-10">
                        <span>{{ $widget['id'] }}</span>
                        @if ($isEditMode)
                            <button class="ml-auto text-slate-400 hover:text-white" @click="removeWidget('{{ $widget['id'] }}')">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                    
                    <!-- Widget Content -->
                    <div class="h-full p-4 sm:p-6">
                        @livewire($widget['component'], $widget['settings'], key($widget['id']))
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer Controls -->
        <footer class="bg-slate-800/70 backdrop-blur-lg rounded-xl shadow-xl m-3 sm:m-4 p-3 flex justify-between items-center border-t border-slate-700/50">
            <div class="flex items-center space-x-2">
                <button @click="theme = theme === 'dark' ? 'light' : 'dark'" 
                    class="p-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white transition-colors">
                    <svg x-show="theme === 'dark'" class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3V4M12 20V21M4 12H3M6.31 6.31L5.6 5.6M17.69 6.31L18.4 5.6M6.31 17.69L5.6 18.4M17.69 17.69L18.4 18.4M21 12H20M16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12Z" 
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <svg x-show="theme === 'light'" class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.3542 15.3542C19.3176 15.7708 18.1856 16.0001 17 16.0001C12.0294 16.0001 8 11.9706 8 7.00006C8 5.81449 8.22924 4.68246 8.64581 3.64587C5.33648 4.9758 3 8.21507 3 12.0001C3 16.9706 7.02944 21.0001 12 21.0001C15.785 21.0001 19.0243 18.6636 20.3542 15.3542Z" 
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                
                <button @click="isEditMode = !isEditMode"
                    class="p-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white transition-colors"
                    :class="{ 'bg-blue-600/50 text-blue-300': isEditMode }">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 4H7C5.89543 4 5 4.89543 5 6V20C5 21.1046 5.89543 22 7 22H17C18.1046 22 19 21.1046 19 20V16M16 3L21 8M16 3V8H21" 
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            
            <div class="text-sm text-slate-400">
                <span>Last updated: </span>
                <span class="font-medium" x-text="new Date().toLocaleTimeString()"></span>
            </div>
        </footer>
    </div>

</div>

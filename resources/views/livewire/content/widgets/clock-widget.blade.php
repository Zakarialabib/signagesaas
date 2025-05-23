<div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 antialiased">
    <div wire:poll.1s="updateTime"
        class="w-full max-w-md flex flex-col items-center justify-center p-6 bg-slate-800/50 backdrop-blur-md shadow-2xl rounded-xl border border-slate-700/50">
        
        <!-- Analog Clock Face -->
        <div class="relative w-48 h-48 sm:w-56 sm:h-56 md:w-64 md:h-64 mb-8">
            <div class="absolute inset-0 rounded-full bg-slate-700/30 shadow-inner">
                <!-- Markings -->
                @for ($i = 0; $i < 12; $i++)
                    <div class="absolute top-1/2 left-1/2 w-0.5 h-full transform -translate-x-1/2 -translate-y-1/2 origin-center"
                         style="transform: rotate({{ $i * 30 }}deg);">
                        <div class="absolute top-1 {{ $i % 3 == 0 ? 'h-3 w-1 bg-sky-400' : 'h-2 w-0.5 bg-slate-500' }} rounded-full"></div>
                    </div>
                @endfor
            </div>
            
            <!-- Hour Hand -->
            <div class="absolute top-1/2 left-1/2 w-1.5 h-[28%] bg-sky-300 rounded-t-full transform -translate-x-1/2 -translate-y-full origin-bottom shadow-md transition-transform duration-500 ease-in-out"
                :style="{ transform: 'translateX(-50%) translateY(-100%) rotate(' + ({{ now()->format('H') }} * 30 + {{ now()->format('i') }} / 2) + 'deg)' }">
            </div>
            
            <!-- Minute Hand -->
            <div class="absolute top-1/2 left-1/2 w-1 h-[38%] bg-slate-300 rounded-t-full transform -translate-x-1/2 -translate-y-full origin-bottom shadow-md transition-transform duration-500 ease-in-out"
                :style="{ transform: 'translateX(-50%) translateY(-100%) rotate(' + ({{ now()->format('i') }} * 6) + 'deg)' }">
            </div>
            
            <!-- Second Hand -->
            <div class="absolute top-1/2 left-1/2 w-0.5 h-[42%] bg-red-500 transform -translate-x-1/2 -translate-y-full origin-bottom shadow-md transition-transform duration-1000 ease-linear"
                :style="{ transform: 'translateX(-50%) translateY(-100%) rotate(' + ({{ now()->format('s') }} * 6) + 'deg)' }">
            </div>
            
            <!-- Center Dot -->
            <div class="absolute top-1/2 left-1/2 w-3 h-3 bg-slate-100 rounded-full transform -translate-x-1/2 -translate-y-1/2 border-2 border-slate-800 shadow-lg"></div>
        </div>
        
        <!-- Digital Time -->
        <div class="text-5xl sm:text-6xl font-mono font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-300 tabular-nums">
            {{ $currentTime }}
        </div>
        
        <!-- Date -->
        <div class=

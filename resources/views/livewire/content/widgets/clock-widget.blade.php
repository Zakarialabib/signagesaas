<div>
    <div x-data="{
        currentTime: '',
        currentDate: '',
        init() {
            this.updateTime();
            setInterval(() => {
                this.updateTime();
            }, 1000);
        },
        updateTime() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('{{ app()->getLocale() }}', { hour: '2-digit', minute: '2-digit', second: '{{ $showSeconds ? '2-digit' : undefined }}', hour12: {{ strpos(strtolower($format), 'h') !== false && strpos(strtolower($format), 'hh') === false ? 'true' : 'false' }} });
            @if($showDate)
            this.currentDate = now.toLocaleDateString('{{ app()->getLocale() }}', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            @endif
        }
    }" x-init="init()"
        class="bg-gradient-to-br from-slate-900 to-slate-800 text-white min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 antialiased">
        <div
            class="w-full max-w-md flex flex-col items-center justify-center p-6 bg-slate-800/50 backdrop-blur-md shadow-2xl rounded-xl border border-slate-700/50">

            <!-- Analog Clock Face -->
            <div class="relative w-48 h-48 sm:w-56 sm:h-56 md:w-64 md:h-64 mb-8" x-data="{
                hourRotation: 0,
                minuteRotation: 0,
                secondRotation: 0,
                updateHands() {
                    const now = new Date();
                    this.secondRotation = now.getSeconds() * 6;
                    this.minuteRotation = now.getMinutes() * 6 + now.getSeconds() / 10;
                    this.hourRotation = (now.getHours() % 12) * 30 + now.getMinutes() / 2;
                },
                initClockHands() {
                    this.updateHands();
                    setInterval(() => {
                        this.updateHands();
                    }, 1000);
                }
            }"
                x-init="initClockHands()">
                <div class="absolute inset-0 rounded-full bg-slate-700/30 shadow-inner">
                    <!-- Markings -->
                    @for ($i = 0; $i < 12; $i++)
                        <div class="absolute top-1/2 left-1/2 w-0.5 h-full transform -translate-x-1/2 -translate-y-1/2 origin-center"
                            style="transform: rotate({{ $i * 30 }}deg);">
                            <div
                                class="absolute top-1 {{ $i % 3 == 0 ? 'h-3 w-1 bg-sky-400' : 'h-2 w-0.5 bg-slate-500' }} rounded-full">
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Hour Hand -->
                <div class="absolute top-1/2 left-1/2 w-1.5 h-[28%] bg-sky-300 rounded-t-full transform -translate-x-1/2 -translate-y-full origin-bottom shadow-md transition-transform duration-500 ease-in-out"
                    :style="{ transform: 'translateX(-50%) translateY(-100%) rotate(' + hourRotation + 'deg)' }">
                </div>

                <!-- Minute Hand -->
                <div class="absolute top-1/2 left-1/2 w-1 h-[38%] bg-slate-300 rounded-t-full transform -translate-x-1/2 -translate-y-full origin-bottom shadow-md transition-transform duration-500 ease-in-out"
                    :style="{ transform: 'translateX(-50%) translateY(-100%) rotate(' + minuteRotation + 'deg)' }">
                </div>

                <!-- Second Hand -->
                <div class="absolute top-1/2 left-1/2 w-0.5 h-[42%] bg-red-500 transform -translate-x-1/2 -translate-y-full origin-bottom shadow-md transition-transform duration-1000 ease-linear"
                    :style="{ transform: 'translateX(-50%) translateY(-100%) rotate(' + secondRotation + 'deg)' }">
                </div>

                <!-- Center Dot -->
                <div
                    class="absolute top-1/2 left-1/2 w-3 h-3 bg-slate-100 rounded-full transform -translate-x-1/2 -translate-y-1/2 border-2 border-slate-800 shadow-lg">
                </div>
            </div>

            <!-- Digital Time -->
            <div x-text="currentTime"
                class="text-5xl sm:text-6xl font-mono font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-300 tabular-nums">
            </div>

            <!-- Date -->
            @if ($showDate)
                <div x-text="currentDate" class="mt-3 text-lg sm:text-xl font-medium text-slate-400 tracking-wide">
                </div>
            @endif
        </div>
    </div>
</div>

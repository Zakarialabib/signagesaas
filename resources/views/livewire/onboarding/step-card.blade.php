<div>
    <div
        class="relative bg-gray-850 rounded-2xl overflow-hidden border border-gray-800/50 transition-all duration-300 hover:shadow-soft-xl hover:border-gray-700/50">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold mb-2">
                        <span
                            class="bg-clip-text text-transparent bg-linear-to-r from-purple-300 to-pink-300 drop-shadow-glow transition-all duration-300 group-hover:from-purple-200 group-hover:to-pink-200">
                            {{ $step->getTitle() }}
                        </span>
                    </h3>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ $step->getDescription() }}</p>
                </div>
                @if ($completed)
                    <span
                        class="px-3 py-1.5 rounded-full text-xs font-semibold bg-success-900/30 text-success-300 border border-success-500/20 shadow-glow-success animate-pulse-slow">
                        Completed
                    </span>
                @else
                    <span
                        class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $this->isNextStep()
                            ? 'bg-info-900/30 text-info-300 border border-info-500/20 shadow-glow-info animate-pulse-slow'
                            : 'bg-warning-900/30 text-warning-300 border border-warning-500/20' }}">
                        {{ $this->isNextStep() ? 'Next Step' : 'Pending' }}
                    </span>
                @endif
            </div>

            <div
                class="relative p-4 rounded-xl mb-4 overflow-hidden backdrop-blur-sm group-hover:shadow-inner-glow transition-all duration-500">
                <div
                    class="absolute inset-0 bg-linear-to-br from-purple-900/40 via-fuchsia-900/30 to-pink-900/40 group-hover:opacity-75 transition-opacity duration-500">
                </div>
                <div class="relative flex items-center justify-center">

                    <div class="relative animate-float py-4">
                        {{-- Glow effect background --}}
                        <div
                            class="absolute inset-0 rounded-full bg-linear-to-r from-purple-500/20 to-pink-500/20 blur-xl group-hover:from-purple-500/30 group-hover:to-pink-500/30 transition-colors duration-500">
                        </div>

                        {{-- Icon --}}
                        <div
                            class="relative text-5xl {{ $completed ? 'text-success-400' : 'text-purple-400' }} transition-colors duration-300 transform group-hover:scale-110">
                            @switch($step->value)
                                @case('profile_completed')
                                    <x-heroicon-o-user-circle class="w-16 h-16 drop-shadow-glow" />
                                @break

                                @case('first_device_registered')
                                    <x-heroicon-o-device-tablet class="w-16 h-16 drop-shadow-glow" />
                                @break

                                @case('first_content_uploaded')
                                    <x-heroicon-o-photo class="w-16 h-16 drop-shadow-glow" />
                                @break

                                @case('first_screen_created')
                                    <x-heroicon-o-computer-desktop class="w-16 h-16 drop-shadow-glow" />
                                @break

                                @case('first_schedule_created')
                                    <x-heroicon-o-calendar class="w-16 h-16 drop-shadow-glow" />
                                @break

                                @case('first_user_invited')
                                    <x-heroicon-o-users class="w-16 h-16 drop-shadow-glow" />
                                @break

                                @case('subscription_setup')
                                    <x-heroicon-o-credit-card class="w-16 h-16 drop-shadow-glow" />
                                @break

                                @case('viewed_analytics')
                                    <x-heroicon-o-chart-bar class="w-16 h-16 drop-shadow-glow" />
                                @break
                            @endswitch
                        </div>
                    </div>
                </div>
                <ul class="space-y-3 text-sm text-gray-300 mb-6">
                    @if (!empty($this->cardData['features']))
                        @foreach ($this->cardData['features'] as $feature)
                            <li class="flex items-start group/item transition-all duration-300 hover:translate-x-1">
                                @if ($completed)
                                    <x-heroicon-m-check-circle
                                        class="w-4 h-4 mt-0.5 mr-3 text-success-400 drop-shadow-glow-success shrink-0 transition-transform duration-300 group-hover/item:scale-110" />
                                @else
                                    <x-heroicon-m-information-circle
                                        class="w-4 h-4 mt-0.5 mr-3 text-info-400 drop-shadow-glow-info shrink-0 transition-transform duration-300 group-hover/item:scale-110" />
                                @endif
                                <span class="text-gray-300 group-hover/item:text-white transition-colors duration-300">
                                    {{ $feature }}
                                </span>
                            </li>
                        @endforeach
                    @endif
                </ul>

                @if (!empty($this->cardData['tips']))
                    <div class="relative px-4 py-3 bg-gray-900/50 rounded-lg border border-gray-800/50 mb-6">
                        <div class="flex items-start">
                            <x-heroicon-m-light-bulb class="w-4 h-4 mt-0.5 mr-2 text-warning-400 shrink-0" />
                            <p class="text-xs text-gray-400">{{ $this->cardData['tips'][0] }}</p>
                        </div>
                    </div>
                    @endif @if ($route)
                        <button onclick="window.location.href='{{ route($route) }}'" class="relative w-full group/btn">
                            {{-- Button background with gradient border effect --}}
                            <div
                                class="absolute -inset-0.5 rounded-lg {{ $completed || !$this->isNextStep()
                                    ? 'bg-linear-to-r from-gray-600/50 to-gray-500/50 group-hover/btn:from-gray-500/50 group-hover/btn:to-gray-400/50'
                                    : 'bg-linear-to-r from-purple-500 to-pink-500 group-hover/btn:from-purple-400 group-hover/btn:to-pink-400 animate-pulse-slow' }} 
                        opacity-75 blur transition duration-500">
                            </div>

                            {{-- Button content --}}
                            <div
                                class="relative flex items-center justify-center px-6 py-3 rounded-lg font-medium transition-all duration-300
                        {{ $completed || !$this->isNextStep()
                            ? 'bg-gray-800 group-hover/btn:bg-gray-800/90'
                            : 'bg-linear-to-r from-purple-600 to-pink-600 group-hover/btn:from-purple-500 group-hover/btn:to-pink-500' }}">

                                @if ($completed)
                                    <x-heroicon-m-arrow-path
                                        class="w-4 h-4 mr-2 text-gray-400 group-hover/btn:text-gray-300" />
                                    <span class="text-gray-100 group-hover/btn:text-white">Review
                                        {{ $step->getTitle() }}</span>
                                @else
                                    @if ($this->isNextStep())
                                        <x-heroicon-m-arrow-right
                                            class="w-4 h-4 mr-2 text-pink-300 group-hover/btn:text-pink-200" />
                                    @endif
                                    <span class="text-white">{{ $this->getActionText() }}</span>
                                @endif
                            </div>
                        </button>
                    @endif
            </div>
        </div>
    </div>
</div>

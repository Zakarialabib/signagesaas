@props(['title', 'category', 'icon', 'error' => null, 'isLoading' => false])

<div 
    class="h-full bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden"
    x-data="{ showSettings: false }"
    wire:poll.{{ $refreshInterval }}s
>
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <div class="h-8 w-8 flex items-center justify-center bg-purple-100 dark:bg-purple-900 rounded-lg mr-3">
                    {!! $icon !!}
                </div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $title }}</h2>
            </div>
            <div class="flex items-center space-x-2">
                @if($error)
                    <div class="text-red-500 dark:text-red-400" title="{{ $error }}">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                @endif
                <button 
                    type="button"
                    class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400"
                    @click="showSettings = !showSettings"
                    aria-label="Widget Settings"
                >
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
        </div>

        @if($isLoading)
            <div class="flex items-center justify-center py-12">
                <div class="text-gray-400 dark:text-gray-500">
                    <svg class="animate-spin h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        @else
            {{ $slot }}
        @endif
    </div>

    <!-- Settings Panel -->
    <div 
        x-show="showSettings"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="absolute inset-0 z-10 bg-white dark:bg-gray-800 p-6"
        @click.away="showSettings = false"
    >
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Widget Settings</h3>
            <button 
                type="button"
                class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400"
                @click="showSettings = false"
            >
                <span class="sr-only">Close settings</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Settings Content -->
        <div class="space-y-6">
            {{ $settings ?? '' }}
        </div>
    </div>
</div> 
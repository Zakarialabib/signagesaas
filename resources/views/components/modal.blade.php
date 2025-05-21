@props([
    'id' => null,
    'maxWidth' => '2xl',
    'title' => '',
    'closeButton' => true,
    'static' => false,
    'initialFocus' => null,
    'zIndex' => 50,
    'footer' => null,
    'confirmText' => null,
    'cancelText' => null,
    'confirmColor' => 'danger',
    'cancelColor' => 'secondary',
    'action' => null,
    'icon' => null,
    'iconColor' => null,
])

@php
    $maxWidthClass = match ($maxWidth) {
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
        'full' => 'sm:max-w-full',
        default => 'sm:max-w-2xl',
    };

    $modalModel = $attributes->whereStartsWith('wire:model')->first();
    $modalId = $id ?? str_replace(['wire:model', '.live', '.defer'], '', $modalModel ?? '');
@endphp

<div 
    x-data="{
        open: false,
        init() {
            this.$watch('open', value => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                    this.$nextTick(() => this.focusInitialElement());
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
            
            if (this.$wire && '{{ $modalModel }}') {
                this.$watch('$wire.{{ $modalModel }}', value => {
                    this.open = value;
                });
                
                this.$watch('open', value => {
                    if (this.$wire.{{ $modalModel }} !== value) {
                        this.$wire.set('{{ $modalModel }}', value);
                    }
                });
            }
        },
        focusables() {
            const selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...this.$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'));
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        focusInitialElement() {
            if (this.initialFocus) {
                this.$refs[this.initialFocus]?.focus();
            } else {
                this.firstFocusable()?.focus();
            }
        }
    }"
    x-modelable="open"
    {{ $attributes->whereDoesntStartWith('wire:model') }}
    x-show="open"
    x-on:keydown.escape.window="!{{ $static }} ? (open = false) : null"
    x-on:click="!{{ $static }} ? (open = false) : null"
    x-trap.inert.noscroll="open"
    x-cloak
    id="{{ $modalId }}"
    class="fixed inset-0 z-{{ $zIndex }} flex items-center justify-center overflow-y-auto px-4 py-6 sm:px-0"
>
    <!-- Backdrop -->
    <div 
        x-show="open" 
        x-transition.opacity 
        class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"
    ></div>

    <!-- Modal Content -->
    <div 
        x-show="open" 
        x-on:click.stop 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative transform rounded-lg bg-white dark:bg-gray-800 shadow-xl transition-all sm:w-full {{ $maxWidthClass }} sm:mx-auto flex flex-col max-h-[90vh]"
        x-ref="content"
    >
        @if ($title || $closeButton)
            <div class="flex items-center justify-between px-4 py-3 border-b dark:border-gray-700">
                @if ($title)
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                        {{ $title }}
                    </h3>
                @endif

                @if ($closeButton)
                    <button 
                        type="button" 
                        class="text-gray-400 hover:text-gray-500 focus:outline-none"
                        x-on:click="open = false"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        @endif

        <!-- Optional Icon -->
        @if ($icon)
            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-{{ $iconColor ?? 'red' }}-100 sm:mx-0 sm:h-10 sm:w-10 mt-6 mb-2">
                {!! $icon !!}
            </div>
        @endif

        <!-- Scrollable Body -->
        <div class="px-4 py-4 sm:p-6 overflow-y-auto flex-1 max-h-[60vh]">
            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                {{ $slot }}
            </div>
        </div>

        @if ($footer)
            <div
                class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 dark:border-gray-600">
                {{ $footer }}
            </div>
        @elseif ($confirmText || $cancelText || $action)
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 dark:border-gray-600">
                <div class="flex justify-end space-x-3 w-full">
                    @if ($cancelText)
                        <x-button 
                            color="{{ $cancelColor }}" 
                            x-on:click="open = false"
                        >
                            {{ $cancelText }}
                        </x-button>
                    @endif

                    @if ($confirmText)
                        <x-button 
                            color="{{ $confirmColor }}" 
                            x-data="{ loading: false }"
                            x-on:click="loading = true; open = false; {{ $action ?? '' }}"
                            {{ $attributes->only(['wire:click']) }}
                        >
                            {{ $confirmText }}
                        </x-button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

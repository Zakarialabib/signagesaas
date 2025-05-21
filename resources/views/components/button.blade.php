@props([
    'type' => 'button',
    'color' => 'primary',
    'size' => 'md',
    'modalId' => null,
    'href' => null,
    'target' => null,
    'newTab' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'disabled' => false,
    'loading' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200';
    
    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        'xl' => 'px-6 py-3 text-base'
    ][$size] ?? 'px-4 py-2 text-sm';
    
    $colorClasses = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white border border-transparent focus:ring-indigo-500',
        'secondary' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 focus:ring-indigo-500',
        'success' => 'bg-green-600 hover:bg-green-700 text-white border border-transparent focus:ring-green-500',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white border border-transparent focus:ring-red-500',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white border border-transparent focus:ring-yellow-500',
        'info' => 'bg-blue-600 hover:bg-blue-700 text-white border border-transparent focus:ring-blue-500',
        'dark' => 'bg-gray-800 hover:bg-gray-900 text-white border border-transparent focus:ring-gray-500',
        'light' => 'bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-200 focus:ring-gray-300',
    ][$color] ?? 'bg-indigo-600 hover:bg-indigo-700 text-white border border-transparent focus:ring-indigo-500';
    
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
    $loadingClasses = $loading ? 'relative text-transparent!' : '';
    
    $classes = $baseClasses . ' ' . $sizeClasses . ' ' . $colorClasses . ' ' . $disabledClasses . ' ' . $loadingClasses;
    
    if ($modalId) {
        $attributes = $attributes->merge(['x-on:click' => "\$dispatch('open-modal', '{$modalId}')"]);
    }
    
    if ($href) {
        $target = $newTab ? '_blank' : $target;
    }
@endphp

@if($href)
    <a 
        href="{{ $href }}"
        @if($target) target="{{ $target }}" @endif
        @if($newTab) rel="noopener noreferrer" @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($icon && $iconPosition === 'left')
            <span class="-ml-1 mr-2">{{ $icon }}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="ml-2 -mr-1">{{ $icon }}</span>
        @endif
        
        @if($loading)
            <span class="absolute inset-0 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        @endif
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) disabled @endif
    >
        @if($icon && $iconPosition === 'left')
            <span class="-ml-1 mr-2">{{ $icon }}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="ml-2 -mr-1">{{ $icon }}</span>
        @endif
        
        @if($loading)
            <span class="absolute inset-0 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        @endif
    </button>
@endif 
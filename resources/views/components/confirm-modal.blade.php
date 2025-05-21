@props([
    'id',
    'title' => 'Confirm Action',
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
    'confirmColor' => 'danger',
    'cancelColor' => 'secondary',
    'action' => '',
    'wireAction' => '',
    'maxWidth' => 'md',
    'icon' => null,
    'iconColor' => null,
])

<x-modal 
    :id="$id" 
    :title="$title" 
    :maxWidth="$maxWidth"
    closeOnClickOutside="false"
>
    <div class="mt-2">
        <div class="text-center sm:text-left sm:flex">
            @if($icon)
                <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-{{ $iconColor ?? 'red' }}-100 sm:mx-0 sm:h-10 sm:w-10 mb-4 sm:mb-0">
                    {!! $icon !!}
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    {{ $slot }}
                </div>
            @else
                <div class="w-full">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
    
    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <x-button 
                color="{{ $cancelColor }}" 
                x-on:click="$dispatch('close-modal', '{{ $id }}')"
            >
                {{ $cancelText }}
            </x-button>
            
            @if($wireAction)
            <x-button 
                color="{{ $confirmColor }}" 
                wire:click="{{ $wireAction }}"
                x-on:click="$dispatch('close-modal', '{{ $id }}')"
                x-data="{ loading: false }"
                x-on:click="loading = true"

            >
                {{ $confirmText }}
            </x-button>
            @else
            <x-button 
                color="{{ $confirmColor }}" 
                x-data="{ loading: false }"
                x-on:click="loading = true; $dispatch('close-modal', '{{ $id }}'); {{ $action }}"

            >
                {{ $confirmText }}
            </x-button>
            @endif
        </div>
    </x-slot>
</x-modal> 
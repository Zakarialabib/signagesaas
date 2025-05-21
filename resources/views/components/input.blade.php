@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'value' => null,
    'label' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'placeholder' => null,
    'error' => null,
    'help' => null,
    'wireModel' => null,
    'class' => '',
])

@php
    $id = $id ?? $name;
    $hasError = $error !== null;
    
    $inputClasses = Arr::toCssClasses([
        'block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
        'ring-red-500 dark:ring-red-400' => $hasError,
        'opacity-50 cursor-not-allowed' => $disabled,
        $class,
    ]);
@endphp

<div x-data="{ hasValue: false, clearValue() { $wire?.set('{{ $wireModel }}', ''); this.hasValue = false; } }">
    @if ($label)
        <x-label :for="$id" :required="$required" class="mb-1">
            {{ $label }}
        </x-label>
    @endif

    <div class="relative">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ $value }}"
            @if($wireModel) wire:model="{{ $wireModel }}" @endif
            @required($required)
            @disabled($disabled)
            @readonly($readonly)
            placeholder="{{ $placeholder }}"
            class="{{ $inputClasses }}"
            x-on:input="hasValue = $event.target.value.length > 0"
            {{ $attributes->merge(['class' => $inputClasses]) }}
        >

        @if(in_array($type, ['search', 'text']))
            <div 
                x-show="hasValue"
                x-transition
                class="absolute inset-y-0 right-0 flex items-center pr-3"
            >
                <button 
                    type="button" 
                    x-on:click="clearValue"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                >
                    <x-heroicon-s-x-mark class="h-5 w-5" />
                </button>
            </div>
        @endif
    </div>

    @if ($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif

    @if ($help)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>

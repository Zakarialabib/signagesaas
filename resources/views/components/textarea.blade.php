@props([
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
    'wire' => true,
    'debounce' => '150ms',
    'rows' => 4,
    'class' => '',
])

@php
    $id = $id ?? $name;
    $wireModel = $wire ? "wire:model.live.debounce.{$debounce}" : '';
    $hasError = $error !== null;
    $textareaClasses = collect([
        'block w-full rounded-md shadow-sm',
        'border-gray-300 dark:border-gray-700',
        'focus:border-primary-500 focus:ring-primary-500',
        'dark:bg-gray-800 dark:text-gray-300',
        'disabled:opacity-50 disabled:cursor-not-allowed',
        $hasError ? 'border-red-300 dark:border-red-700' : '',
        $class,
    ])->filter()->join(' ');
@endphp

<div x-data="{ 
    focused: false,
    hasValue: @entangle($wire ? "{$name}" : 'false'),
    clearValue() {
        if (@entangle($wire ? "{$name}" : 'false')) {
            @entangle($wire ? "{$name}" : 'false') = '';
        }
    }
}">
    @if($label)
        <x-label :for="$id" :required="$required" class="mb-1">
            {{ $label }}
        </x-label>
    @endif

    <div class="relative">
        <textarea
            name="{{ $name }}"
            id="{{ $id }}"
            rows="{{ $rows }}"
            {{ $wireModel }}
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            {{ $attributes->merge(['class' => $textareaClasses]) }}
        >{{ $value }}</textarea>
    </div>

    @if($help)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif

    @if($error)
        <x-input-error :for="$name" />
    @endif
</div>

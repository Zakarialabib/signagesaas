@props([
    'for' => null,
    'required' => false,
    'class' => '',
])

<label
    {{ $attributes->merge([
        'class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 ' . $class,
        'for' => $for,
    ]) }}
>
    {{ $slot }}
    @if($required)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>
x   
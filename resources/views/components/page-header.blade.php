@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->class([
    'mb-8',
    'border-b',
    'border-gray-200',
    'pb-4',
]) }}>
    <div class="flex flex-col gap-2">
        @if($title)
            <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @endif
        @if($description)
            <p class="text-gray-600">{{ $description }}</p>
        @endif
        {{ $slot }}
    </div>
</div>

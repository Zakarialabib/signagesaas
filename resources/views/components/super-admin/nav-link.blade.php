@props([
    'active' => false,
    'href' => '#',
    'icon' => null,
])

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' => (
            'flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-base transition-all duration-200 ' .
            'backdrop-blur-md bg-white/60 dark:bg-gray-800/60 shadow-md shadow-indigo-100/20 dark:shadow-black/30 border border-white/20 dark:border-gray-700/30 ' .
            'hover:bg-white/80 dark:hover:bg-gray-700/80 active:scale-95 focus:outline-none focus:ring-2 focus:ring-indigo-400/40 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 ' .
            ($active ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300')
        )
    ]) }}
    tabindex="0"
    role="button"
    aria-current="{{ $active ? 'page' : 'false' }}">
    @if($icon)
        <span class="w-6 h-6 flex items-center justify-center">{!! $icon !!}</span>
    @endif
    <span class="truncate">{{ $slot }}</span>
</a> 
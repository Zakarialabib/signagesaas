<div
    x-data="{ open: false }"
    x-on:click.away="open = false"
    x-on:keydown.escape.window="open = false"
    {{ $attributes->merge(['class' => 'relative']) }}
>
    {{-- Trigger Button --}}
    <button
        type="button"
        x-on:click="open = !open"
        x-bind:aria-expanded="open"
        aria-haspopup="true"
        class="flex items-center justify-center w-full focus:outline-none"
    >
        {{ $attributes->get('trigger') ?? 'Menu' }}
    </button>

    {{-- Dropdown Menu --}}
    <div
        x-ref="menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        x-cloak
        class="absolute right-0 z-10 w-48 rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-gray-300 dark:ring-gray-700 focus:outline-none"
    >
        {{ $slot }}
    </div>
</div>

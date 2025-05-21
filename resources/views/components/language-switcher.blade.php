<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" type="button" class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100" :aria-expanded="open">
        <span>{{ strtoupper(app()->getLocale()) }}</span>
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">
        <div class="py-1" role="none">
            @foreach(['en' => 'English', 'ar' => 'العربية'] as $locale => $name)
                @if($locale !== app()->getLocale())
                    <a href="{{ route('language.switch', $locale) }}" class="text-gray-700 dark:text-gray-300 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem" tabindex="-1">
                        {{ $name }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div> 
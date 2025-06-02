@props(['active' => false])

<a {{ $attributes->merge([
    'class' => 'group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ' . 
               ($active 
                   ? 'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100' 
                   : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white')
]) }}>
    <x-heroicon-o-squares-2x2 class="mr-3 h-5 w-5 flex-shrink-0 {{ $active ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
    {{ __('Unified Content') }}
</a>
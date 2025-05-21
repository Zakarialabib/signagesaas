<div 
    x-data="{ 
        expanded: @entangle('isExpanded'),
        animating: false,
        init() {
            this.$watch('expanded', value => {
                if (value) {
                    this.animating = true;
                    setTimeout(() => this.animating = false, 300);
                }
            });
        }
    }" 
    class="relative bg-white dark:bg-gray-800 rounded-lg shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-800/5 mb-6"
    :class="{ 'ring-indigo-500 dark:ring-indigo-400': expanded || animating }"
>
    {{-- Header --}}
    <button 
        type="button"
        wire:click="toggle"
        class="w-full flex items-center justify-between px-4 py-3 text-left"
    >
        <div class="flex items-center space-x-3">
            {{-- Icon indicating new/viewed status --}}
            <div class="shrink-0">
                @if(!$hasBeenViewed)
                    <div class="h-2 w-2 bg-indigo-500 rounded-full animate-pulse"></div>
                @else
                    <div class="h-2 w-2 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                @endif
            </div>

            {{-- Title --}}
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ $step['title'] }}
            </h3>
        </div>

        {{-- Expand/Collapse Icon --}}
        <svg 
            class="h-5 w-5 text-gray-400 transform transition-transform duration-200" 
            :class="{ 'rotate-180': expanded }"
            xmlns="http://www.w3.org/2000/svg" 
            viewBox="0 0 20 20" 
            fill="currentColor"
        >
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Content Panel --}}
    <div 
        x-show="expanded"
        x-collapse
        x-cloak
        class="px-4 pb-4"
    >
        {{-- Description --}}
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            {{ $step['description'] }}
        </p>

        {{-- Examples --}}
        <div class="space-y-3">
            @foreach($step['examples'] as $example)
                <div class="flex items-start space-x-3">
                    <div class="shrink-0 mt-1">
                        <svg class="h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {!! $example !!}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Progress Indicator --}}
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500 dark:text-gray-400">
                    {{ $hasBeenViewed ? 'Content viewed' : 'Mark as viewed when expanded' }}
                </span>
                <div class="flex items-center space-x-1">
                    <div class="h-2 w-2 rounded-full {{ $hasBeenViewed ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ $this->getProgressPercentage() }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div> 
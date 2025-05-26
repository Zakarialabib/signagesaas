<div x-data="{
    expanded: @entangle('isExpanded'),
    animating: false,
    hovered: false,
    init() {
        this.$watch('expanded', value => {
            this.animating = true;
            setTimeout(() => this.animating = false, 300);
        });
    }
}"
    class="relative bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 ring-1 ring-gray-900/5 dark:ring-gray-700/50 mb-6 overflow-hidden"
    :class="{
        'ring-2 ring-indigo-500/80 dark:ring-indigo-400/80 shadow-md': expanded || animating,
        'ring-gray-900/10 dark:ring-gray-600/50': hovered && !expanded
    }"
    @mouseenter="hovered = true" @mouseleave="hovered = false">
    {{-- Header --}}
    <button type="button" wire:click="toggle"
        class="w-full flex items-center justify-between px-5 py-4 text-left group focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded-t-lg"
        :aria-expanded="expanded">
        <div class="flex items-center space-x-4">
            {{-- Status Indicator --}}
            <div class="shrink-0 relative">
                @if (!$hasBeenViewed)
                    <div class="absolute -top-1 -right-1 h-3 w-3 bg-indigo-500 rounded-full animate-ping opacity-75">
                    </div>
                    <div class="h-2.5 w-2.5 bg-indigo-600 dark:bg-indigo-400 rounded-full"></div>
                @else
                    <div
                        class="h-2.5 w-2.5 bg-gray-300 dark:bg-gray-600 rounded-full group-hover:bg-gray-400 dark:group-hover:bg-gray-500 transition-colors">
                    </div>
                @endif
            </div>

            {{-- Title --}}
            <div class="text-left">
                <h3
                    class="text-base font-semibold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                    {{ $step['title'] }}
                </h3>
                @if (isset($step['subtitle']))
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $step['subtitle'] }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Expand/Collapse Icon --}}
        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transform transition-all duration-200"
            :class="{ 'rotate-180': expanded, 'scale-110': hovered }" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Content Panel --}}
    <div x-show="expanded" x-collapse.duration.300ms x-cloak class="px-5 pb-5 pt-1">
        {{-- Description --}}
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-5 leading-relaxed">
            {{ $step['description'] }}
        </p>

        {{-- Examples --}}
        <div class="space-y-4">
            @foreach ($step['examples'] as $example)
                <div class="flex items-start space-x-3 group">
                    <div class="shrink-0 mt-0.5">
                        <div
                            class="h-5 w-5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800 transition-colors">
                            <svg class="h-3 w-3 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <p
                        class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-200 transition-colors">
                        {!! $example !!}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex space-x-3">
            @if (isset($step['action_url']))
                <a href="{{ $step['action_url'] }}"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    target="_blank">
                    Learn More
                </a>
            @endif
        </div>

        {{-- Progress Indicator --}}
        <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400 font-medium">
                    {{ $hasBeenViewed ? 'Completed' : 'In Progress' }}
                </span>
                <div class="flex items-center space-x-2">
                    <div
                        class="h-2.5 w-2.5 rounded-full {{ $hasBeenViewed ? 'bg-green-500 animate-pulse' : 'bg-amber-400 dark:bg-amber-500' }}">
                    </div>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">
                        {{ $this->getProgressPercentage() }}%
                    </span>
                </div>
            </div>
            <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-indigo-600 dark:bg-indigo-400 h-2 rounded-full transition-all duration-500 ease-out"
                    style="width: {{ $this->getProgressPercentage() }}%"></div>
            </div>
        </div>
    </div>
</div>

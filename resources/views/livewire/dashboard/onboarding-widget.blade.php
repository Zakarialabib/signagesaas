<div>
    {{-- Modal for Context Step --}}
    <div x-data="{ open: {{ !$dismissed && $this->getContextStep() && !$this->getContextStep()['completed'] ? 'true' : 'false' }} }">
        <template x-if="open">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full p-6 relative">
                    <button @click="open = false; $wire.dismiss()"
                        class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @if ($step = $this->getContextStep())
                        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 mb-2">
                            {{ $step['title'] }}
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $step['description'] }}</p>
                        <ul class="list-disc pl-5 text-sm text-gray-600 dark:text-gray-400 mb-4">
                            @foreach ($step['examples'] as $example)
                                <li class="mb-2">{!! $example !!}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route($step['route']) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Complete this step
                        </a>
                    @endif
                </div>
            </div>
        </template>
    </div>
    {{-- Main Dashboard Widget --}}
    <div class="bg-gray-950 shadow-xl rounded-2xl overflow-hidden p-6 mb-6">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-2xl font-bold text-white">Getting Started</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-indigo-900/30 text-indigo-400">
                    {{ $this->getProgressPercentage() }}% Complete
                </span>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-gray-400">
                    Complete these steps to set up your digital signage system
                </p>
                @if ($this->getProgressPercentage() < 100)
                    <span class="text-sm text-indigo-400">
                        {{ $this->getTotalSteps() - $this->getProgress() }} steps remaining
                    </span>
                @endif
            </div>
        </div>

        {{-- Progress Steps Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($steps as $step)
                <livewire:onboarding.step-card :step="App\Enums\OnboardingStep::from($step['key'])" :completed="$step['completed']" :route="$step['route']" />
            @endforeach
        </div>
        {{-- Footer with Help Text --}}
        <div class="mt-8 flex justify-between items-center pt-6 border-t border-gray-800">
            <div class="text-sm text-gray-400">
                Need help setting up your digital signage?
            </div>            <div class="flex space-x-4">
                <a href="#" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors inline-flex items-center">
                    <x-heroicon-m-book-open class="w-4 h-4 mr-1" /> Read Documentation
                </a>
                <a href="#" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors inline-flex items-center">
                    <x-heroicon-m-chat-bubble-left-right class="w-4 h-4 mr-1" /> Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

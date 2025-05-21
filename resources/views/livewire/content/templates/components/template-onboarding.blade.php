{{-- Template Onboarding Guide Component --}}
<div class="template-onboarding relative" x-data="{ hidden: false }" x-show="hidden!">
    {{-- Onboarding Modal --}}
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        x-show="$wire.showGuide" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            {{-- Modal Content --}}
            <div class="relative inline-block bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                {{-- Step Content --}}
                <div class="px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                                {{ $steps[$currentStep]['title'] }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $steps[$currentStep]['description'] }}
                                </p>
                                
                                {{-- Tips --}}
                                <div class="mt-4 space-y-2">
                                    @foreach($steps[$currentStep]['tips'] as $tip)
                                        <div class="flex items-start">
                                            <div class="shrink-0">
                                                <x-heroicon-m-light-bulb class="h-5 w-5 text-yellow-400" />
                                            </div>
                                            <p class="ml-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $tip }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if($currentStep < count($steps) - 1)
                        <button type="button" wire:click="nextStep"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Next
                        </button>
                    @else
                        <button type="button" wire:click="completeGuide"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Complete
                        </button>
                    @endif
                    @if($currentStep > 0)
                        <button type="button" wire:click="previousStep"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Previous
                        </button>
                    @endif
                    <button type="button" wire:click="skipGuide"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Skip
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Indicator --}}
    <div class="absolute bottom-4 right-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4" x-show="$wire.showGuide">
        <div class="flex items-center space-x-2">
            @foreach($steps as $index => $step)
                <div class="w-2 h-2 rounded-full {{ $index === $currentStep ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
            @endforeach
        </div>
    </div>
</div>

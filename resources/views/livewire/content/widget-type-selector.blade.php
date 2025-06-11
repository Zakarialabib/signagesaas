<div>
    <div class="p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Choose a Widget Type to Create Content</h2>
        
        @if(empty($availableWidgets))
            <p class="text-gray-500 dark:text-gray-400">No widget types available at the moment.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($availableWidgets as $widget)
                    <div class="flex"> {{-- Added flex to ensure cards in a row are same height --}}
                        <x-base-demos
                            :title="$widget['title']"
                            :iconSvgPath="$widget['iconSvgPath']"
                            :description="$widget['description']"
                            :features="$widget['features']"
                            :category="$widget['category']" {{-- This prop was used for the old demo link, might not be needed for the button directly --}}
                            :themeColor="$widget['themeColor']"
                            :gradientToColor="$widget['gradientToColor'] ?? null"
                            :industry="$widget['industry'] ?? null"
                            :useCases="$widget['useCases'] ?? []"
                            class="w-full" {{-- Ensure component takes full width of its grid cell --}}
                        >
                            {{-- Override the default slot or add a new slot for the button if base-demos supports it. --}}
                            {{-- For simplicity, we'll assume base-demos might need slight modification or we add HTML here. --}}
                            {{-- If base-demos doesn't have a clean way to replace its link, this part might need adjustment. --}}
                            {{-- Let's try to place a button at the bottom, replacing the default link functionality. --}}
                            <div class="mt-auto pt-4"> {{-- Push to bottom --}}
                                <button
                                    type="button"
                                    wire:click="selectWidgetType('{{ $widget['widgetTypeIdentifier'] }}')"
                                    class="inline-flex items-center justify-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Create {{ $widget['title'] }}
                                </button>
                            </div>
                        </x-base-demos>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

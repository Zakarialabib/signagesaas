<div>
    <div class="space-y-4">
        <div class="flex flex-col justify-between items-center">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold">Template Version Control</h3>
                <span class="text-sm text-gray-500">Current Version: {{ $template->version }}</span>
            </div>
            <div class="flex space-x-2">
                <x-button wire:click="$toggle('showVersionCreate')" color="secondary" size="sm">
                    Create New Version
                </x-button>
                <x-button wire:click="c" color="secondary" size="sm">
                    Create Variation
                </x-button>
                <x-button wire:click="$toggle('showVersionHistory')" color="secondary" size="sm">
                    Version History
                </x-button>
            </div>
        </div>

        {{-- Version Creation Form --}}
        <div x-show="$wire.showVersionCreate" x-transition class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <form wire:submit="createVersion" class="space-y-4">
                <div>
                    <x-label for="versionDescription" class="mb-1">Version Description</x-label>
                    <x-input wire:model.live.debounce.150ms="versionDescription" id="versionDescription" type="text"
                        class="w-full" placeholder="Describe the changes in this version..." :error="$errors->first('versionDescription')" />
                </div>
                <div class="flex justify-end space-x-2">
                    <x-button wire:click="$set('showVersionCreate', false)" color="secondary" size="sm">
                        Cancel
                    </x-button>
                    <x-button type="submit" color="primary" size="sm">
                        Create Version
                    </x-button>
                </div>
            </form>
        </div>

        {{-- Version History --}}
        <div x-show="$wire.showVersionHistory" x-transition class="space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-4">
                    <h4 class="font-semibold mb-4">Version History</h4>
                    <div class="divide-y dark:divide-gray-700">
                        @foreach ($this->versions as $version)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <span class="font-medium">Version {{ $version->version }}</span>
                                    <p class="text-sm text-gray-500">{{ $version->description }}</p>
                                    <span
                                        class="text-xs text-gray-400">{{ $version->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    @if ($version->id !== $template->id)
                                        <a href="{{ route('content.template.show', $version) }}"
                                            class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                            View
                                        </a>
                                    @else
                                        <span class="text-xs text-green-500 font-medium">Current</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($this->variations->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-4">
                        <h4 class="font-semibold mb-4">Variations</h4>
                        <div class="divide-y dark:divide-gray-700">
                            @foreach ($this->variations as $variation)
                                <div class="py-3 flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $variation->name }}</span>
                                        <p class="text-sm text-gray-500">Created
                                            {{ $variation->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('content.template.show', $variation) }}"
                                            class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Zone Content Selector Component --}}
<div class="zone-content-selector">
    <div x-data="{ 
        showContentBrowser: false,
        selectedType: @entangle('selectedContentType'),
        filterContent() {
            $wire.filterByType(this.selectedType)
        }
    }">
        {{-- Content Type Selector --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content Type</label>
            <select x-model="selectedType" @change="filterContent()"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800">
                <option value="">Select Type</option>
                @foreach($this->contentTypes as $type => $label)
                    <option value="{{ $type }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Content Browser --}}
        <div x-show="showContentBrowser" class="mt-4">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="grid grid-cols-2 gap-4 p-4">
                    @foreach($this->availableContent as $content)
                        <div wire:click="selectContent('{{ $content->id }}')"
                            class="cursor-pointer p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors">
                            <div class="aspect-w-16 aspect-h-9 mb-2 bg-gray-100 dark:bg-gray-900 rounded-md overflow-hidden">
                                @if($content->type->value === 'image')
                                    <img src="{{ $content->content_data['url'] }}" alt="{{ $content->name }}" class="object-cover">
                                @elseif($content->type->value === 'video')
                                    <div class="flex items-center justify-center">
                                        <x-heroicon-o-video-camera class="h-8 w-8 text-gray-400" />
                                    </div>
                                @else
                                    <div class="flex items-center justify-center">
                                        <x-heroicon-o-document-text class="h-8 w-8 text-gray-400" />
                                    </div>
                                @endif
                            </div>
                            <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $content->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $content->type->label() }}</p>
                        </div>
                    @endforeach
                </div>

                @if($this->availableContent->isEmpty())
                    <div class="p-8 text-center">
                        <x-heroicon-o-document class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No content found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating new content for this type.</p>
                        <div class="mt-6">
                            <button wire:click="$dispatch('createContent')" type="button"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                <x-heroicon-m-plus class="h-4 w-4 mr-1" />
                                New Content
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Selected Content Preview --}}
        @if($this->selectedContent)
            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $this->selectedContent->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $this->selectedContent->type->label() }}</p>
                    </div>
                    <button wire:click="clearSelection" type="button"
                        class="inline-flex items-center p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <x-heroicon-m-x-mark class="h-5 w-5" />
                    </button>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="mt-4 flex justify-end space-x-3">
            <button type="button" @click="showContentBrowser = false"
                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button wire:click="assignContent" type="button"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Assign Content
            </button>
        </div>
    </div>
</div>

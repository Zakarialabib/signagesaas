<div>
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Template Gallery</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Browse and use our pre-designed templates or create your own from scratch.
                </p>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-6 flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-heroicon-s-magnifying-glass class="h-5 w-5 text-gray-400" />
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="search" name="search" id="search"
                        class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 dark:text-gray-100 dark:bg-gray-800 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="Search templates...">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="sm:w-64">
                <select wire:model.live="categoryFilter"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option value="all">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->value }}">{{ $category->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Base Case Templates Section -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Base Case Templates</h3>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($baseTemplates as $key => $baseTemplate)
                    <div class="group relative bg-white dark:bg-gray-800 rounded-lg shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700">
                        <!-- Template Preview Image -->
                        <div class="aspect-h-9 aspect-w-16 relative overflow-hidden rounded-t-lg bg-gray-100 dark:bg-gray-900">
                            <img src="{{ $baseTemplate['preview'] ?? asset('images/templates/default.png') }}" 
                                alt="{{ $baseTemplate['name'] }}"
                                class="object-cover transition-transform duration-300 group-hover:scale-105">

                            <!-- Action Buttons Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-4">
                                <button wire:click="useBaseTemplate('{{ $key }}')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Use as Base
                                </button>
                            </div>
                        </div>

                        <!-- Template Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $baseTemplate['name'] }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $baseTemplate['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- User Templates Grid -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Your Templates</h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($templates as $template)
                        <div class="group relative bg-white dark:bg-gray-800 rounded-lg shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700">
                            <!-- Template Preview -->
                            <div class="aspect-h-9 aspect-w-16 relative overflow-hidden rounded-t-lg bg-gray-100 dark:bg-gray-900">
                                <img src="{{ $template->getPreviewImageUrl() }}" alt="{{ $template->name }}"
                                    class="object-cover transition-transform duration-300 group-hover:scale-105">
                                
                                @if($template->is_system)
                                    <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full z-10">
                                        System Template
                                    </span>
                                @endif

                                <!-- Action Buttons Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-4">
                                    <button wire:click="preview('{{ $template->id }}')"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700">
                                        Preview
                                    </button>
                                    <button wire:click="customize('{{ $template->id }}')"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Use Template
                                    </button>
                                </div>
                            </div>

                            <!-- Template Info -->
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $template->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $template->description }}</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-400/30">
                                        {{ $template->category->label() }}
                                    </span>
                                </div>
                                <div class="mt-2 flex space-x-2">
                                    <a
                                        href="{{ url('/tv/' . (auth()->user()->tenant->slug ?? 'tenant') . '/widget/' . $template->category->value) }}"
                                        target="_blank"
                                        class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600"
                                        title="Preview on TV"
                                    >
                                        <x-heroicon-s-tv class="h-4 w-4 mr-1" />
                                        Preview on TV
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $templates->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Template Preview Modal -->
    <x-modal wire:model="showPreviewModal" maxWidth="4xl" id="preview-modal" title="Template Preview">
        @if ($selectedTemplate)
            <div class="bg-white dark:bg-gray-800 px-4 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            {{ $selectedTemplate->name }}
                        </h3>
                        <div class="mt-4">
                            <!-- Preview Container -->
                            <div class="aspect-h-9 aspect-w-16 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-900">
                                <livewire:content.templates.components.live-preview 
                                    :template="$selectedTemplate"
                                    :key="'preview-'.$selectedTemplate->id"
                                />
                            </div>
                            <!-- Template Description -->
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $selectedTemplate->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-modal>

    <!-- Template Customization Modal -->
    <x-modal wire:model="showCustomizeModal" maxWidth="2xl" id="customize-modal" title="Customize Template">
        @if ($selectedTemplate)
            <div class="bg-white dark:bg-gray-800 px-4 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            Customize Template
                        </h3>
                        <div class="mt-4">
                            <!-- Customization Form -->
                            <form wire:submit="saveCustomization">
                                <!-- Name -->
                                <div>
                                    <label for="customization.name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Content Name
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" wire:model="customization.name" id="customization.name"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800">
                                    </div>
                                    @error('customization.name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Style Settings -->
                                <div class="mt-4 space-y-4">
                                    <!-- Background Color -->
                                    <div>
                                        <label for="customization.settings.background"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Background Color
                                        </label>
                                        <input type="color" wire:model.live="customization.settings.background"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <!-- Other style settings... -->
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" wire:click="closeModals"
                                        class="rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                        Create Content
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-modal>
</div>
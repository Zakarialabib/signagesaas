<div>
    <livewire:dashboard.onboarding-widget :contextStepKey="App\Enums\OnboardingStep::FIRST_SCREEN_CREATED->value" />
    <!-- Header -->
    <div class="sm:flex sm:items-center mb-8">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Templates</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Create and manage templates for your digital signage content.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex sm:space-x-3">
            <button wire:click="$dispatch('create-template')"
                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <x-heroicon-s-plus class="h-4 w-4 mr-2" />
                New Template
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-4">
        <!-- Search -->
        <div>
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
        <div>
            <select wire:model.live="categoryFilter"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option value="all">All Categories</option>
                @foreach ($this->categories as $category)
                    <option value="{{ $category['value'] }}">{{ $category['label'] }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <select wire:model.live="statusFilter"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option value="all">All Statuses</option>
                @foreach ($this->statuses as $status)
                    <option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
                @endforeach
            </select>
        </div>

        <!-- Sort -->
        <div>
            <select wire:model.live="sortField"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-800 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option value="name">Sort by Name</option>
                <option value="created_at">Sort by Created Date</option>
                <option value="updated_at">Sort by Updated Date</option>
            </select>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($templates as $template)
            <div
                class="relative group bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-300 dark:ring-gray-700 rounded-lg p-6">
                <!-- Template Status & Actions -->
                <div class="flex items-center justify-between mb-4">
                    <span @class([
                        'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
                        'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20' =>
                            $template->status === \App\Enums\TemplateStatus::PUBLISHED,
                        'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20' =>
                            $template->status === \App\Enums\TemplateStatus::DRAFT,
                        'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20' =>
                            $template->status === \App\Enums\TemplateStatus::ARCHIVED,
                    ])>
                        {{ $template->status->label() }}
                    </span>
                    <div class="relative flex items-center space-x-2">
                        <button type="button" wire:click="openPreview('{{ $template->id }}')"
                            class="p-2 text-gray-400 hover:text-indigo-600 focus:outline-none" title="Preview">
                            <x-heroicon-m-eye class="h-5 w-5" />
                        </button>
                        <button type="button" wire:click="openConfigurator('{{ $template->id }}')"
                            class="p-2 text-gray-400 hover:text-green-600 focus:outline-none" title="Configure">
                            <x-heroicon-m-cog-6-tooth class="h-5 w-5" />
                        </button>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <x-heroicon-s-ellipsis-vertical class="h-5 w-5" />
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link wire:click="$dispatch('edit-template', { id: '{{ $template->id }}' })"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <x-heroicon-m-pencil-square class="mr-2 h-5 w-5" />
                                    Edit
                                </x-dropdown-link>
                                <x-dropdown-link
                                    wire:click="$dispatch('duplicate-template', { id: '{{ $template->id }}' })"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <x-heroicon-m-document-duplicate class="mr-2 h-5 w-5" />
                                    Duplicate
                                </x-dropdown-link>
                                <x-dropdown-link wire:click="confirmDelete('{{ $template->id }}')"
                                    class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <x-heroicon-m-trash class="mr-2 h-5 w-5" />
                                    Delete
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                <!-- Template Preview Image -->
                @if ($template->preview_image)
                    <img src="{{ $template->getPreviewImageUrl() }}" alt="{{ $template->name }}"
                        class="w-full h-40 object-cover rounded-md mb-4">
                @else
                    <div
                        class="w-full h-40 bg-gray-100 dark:bg-gray-700 rounded-md mb-4 flex items-center justify-center">
                        <x-heroicon-o-photo class="h-12 w-12 text-gray-400" />
                    </div>
                @endif

                <!-- Template Info -->
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $template->name }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $template->description }}</p>

                <!-- Version Control -->
                @if ($template->parent_id === null)
                    <div class="mt-4">
                        <livewire:content.templates.components.template-version-control :template="$template"
                            :key="'version-control-' . $template->id" class="border-t border-gray-200 dark:border-gray-700 pt-4" />
                    </div>
                @endif

                <!-- Template Stats -->
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                        <x-heroicon-m-document-text class="h-5 w-5" />
                        <span>{{ $template->contents_count }} contents</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center">
                    <x-heroicon-o-document-text class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No templates</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new template.</p>
                    <div class="mt-6">
                        <button wire:click="$dispatch('create-template')"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            <x-heroicon-s-plus class="h-4 w-4 mr-2" />
                            New Template
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($templates->hasPages())
        <div class="mt-6">
            {{ $templates->links() }}
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="showDeleteModal" id="delete-template-modal" maxWidth="sm" title="Delete Template">
        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-red-600" />
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">Delete Template</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete this template? This action cannot be undone.
                        @if ($templateToDelete?->contents_count > 0)
                            This template is currently being used by {{ $templateToDelete->contents_count }} content
                            items.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button wire:click="deleteTemplate"
                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                Delete
            </button>
            <button wire:click="cancelDelete"
                class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                Cancel
            </button>
        </div>
    </x-modal>

    <!-- Configurator Modal -->
    @if ($showConfigurator && $selectedTemplate)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-hidden">
            <div
                class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-4xl p-6 relative max-h-[90vh] overflow-y-auto">
                <button type="button" wire:click="closeConfigurator"
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500 z-10">
                    <x-heroicon-m-x-mark class="h-6 w-6" />
                </button>
                <livewire:content.templates.template-configurator :template="$selectedTemplate" :key="'configurator-' . $selectedTemplate->id" />
            </div>
        </div>
    @endif

    <!-- Preview Modal -->
    @if ($showPreview && $selectedTemplate)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-hidden">
            <div
                class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-4xl p-6 relative max-h-[90vh] overflow-y-auto">
                <button type="button" wire:click="closePreview"
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500">
                    <x-heroicon-m-x-mark class="h-6 w-6" />
                </button>
                <livewire:content.templates.components.live-preview :template="$selectedTemplate" :key="'preview-' . $selectedTemplate->id" />
            </div>
        </div>
    @endif
</div>

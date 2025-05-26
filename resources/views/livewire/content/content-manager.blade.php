<div>

    <livewire:dashboard.onboarding-widget :contextStepKey="App\Enums\OnboardingStep::FIRST_CONTENT_UPLOADED->value" />

    {{-- Add ContextualHelpWidget --}}
    @livewire('shared.contextual-help-widget', ['contextKey' => \App\Enums\OnboardingStep::FIRST_CONTENT_UPLOADED->value])    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Content Library</h1>
            <div class="flex space-x-2">
                <x-button color="primary" wire:click="$dispatch('createContentModal')">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Standard Content
                </x-button>
                <x-button color="secondary" wire:click="openWidgetTypeSelector">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Add Widget Content
                </x-button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4 mb-6">
                    <div class="lg:w-1/3">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>                            <input wire:model.live="search" type="text" id="search"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Search content...">
                        </div>
                    </div>

                    <div class="lg:w-1/5">
                        <select wire:model.live="statusFilter" id="status-filter"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="all">All Statuses</option>
                            @foreach ($contentStatuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:w-1/5">
                        <select wire:model.live="dateFilter" id="date-filter"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="all">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>

                    <div class="lg:w-1/5">
                        <select wire:model.live="typeFilter" id="type-filter"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="all">All Types</option>
                            @foreach ($contentTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:w-1/5">
                        <select wire:model.live="screenFilter" id="screen-filter"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="all">All Screens</option>
                            @foreach ($screens as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:w-1/10">
                        <x-button color="secondary" wire:click="refreshContents">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh
                        </x-button>
                    </div>
                </div>

                <!-- Bulk actions bar -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="selectAll" type="checkbox" wire:model.live="selectAll"
                                    wire:click="toggleSelectAll"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="selectAll" class="font-medium text-gray-700">Select All</label>
                            </div>
                        </div>

                        @if ($selectedCount > 0)
                            <span class="ml-3 text-sm text-gray-700">{{ $selectedCount }} items selected</span>
                            <x-button color="primary" size="sm" class="ml-3" wire:click="openBulkActionModal">
                                Bulk Actions
                            </x-button>
                            <x-button color="success" size="sm" class="ml-3" wire:click="exportSelected">
                                Export Selected
                            </x-button>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2">                        <span class="text-sm text-gray-700 dark:text-gray-300">Show</span>
                        <select wire:model.live="perPage"
                            class="block w-full pl-3 pr-10 py-1 text-base border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-sm text-gray-700 dark:text-gray-300">per page</span>
                    </div>
                </div>

                @if ($contents->count() > 0)
                    <div class="overflow-x-auto">                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="w-12 px-6 py-3">
                                        <!-- Empty header for checkbox column -->
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer"
                                        wire:click="sortBy('name')">
                                        <div class="flex items-center">
                                            Name
                                            @if ($sortField === 'name')
                                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if ($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        wire:click="sortBy('type')">
                                        <div class="flex items-center">
                                            Type
                                            @if ($sortField === 'type')
                                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if ($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Screen</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        wire:click="sortBy('status')">
                                        <div class="flex items-center">
                                            Status
                                            @if ($sortField === 'status')
                                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if ($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        wire:click="sortBy('duration')">
                                        <div class="flex items-center">
                                            Duration
                                            @if ($sortField === 'duration')
                                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if ($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($contents as $content)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" value="{{ $content->id }}"
                                                wire:model.live="selected"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $content->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($content->description, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100">
                                                {{ $content->type->label() }}
                                            </span>
                                            @if ($content->type == \App\Enums\ContentType::WIDGET && isset($content->content_data['widget_type']))
                                                <span class="block text-xs text-gray-500 dark:text-gray-400">({{ $content->content_data['widget_type'] }})</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span
                                                wire:click="$dispatch('show-screen', { id: '{{ $content->screen_id }}' })"
                                                class="text-indigo-600 hover:text-indigo-900 cursor-pointer">
                                                {{ $content->screen->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $content->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($content->status->value) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $content->duration ?? 10 }}s
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <x-button color="secondary" size="xs"
                                                wire:click="$dispatch('showContentModal', { id: '{{ $content->id }}' })">
                                                View
                                            </x-button>
                                            <x-button color="secondary" size="xs"
                                                wire:click="$dispatch('editContentModal', { id: '{{ $content->id }}' })">
                                                Edit
                                            </x-button>
                                            <x-button color="info" size="xs"
                                                wire:click="$dispatch('preview-content', { id: '{{ $content->id }}' })">
                                                Preview
                                            </x-button>
                                            <x-button color="danger" size="xs"
                                                wire:click="$dispatch('confirm-delete-content', { id: '{{ $content->id }}' })">
                                                Delete
                                            </x-button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $contents->links() }}
                    </div>
                @else                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No content found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating new content.</p>
                        <div class="mt-6">
                            <x-button color="primary" wire:click="createContent">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Content
                            </x-button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bulk Action Modal -->
    <x-modal id="bulk-action-modal" title="Bulk Actions" wire:model="bulkActionModal">
        <div class="p-6">
            <p class="mb-4">Select an action to perform on {{ count($selected) }} selected items:</p>

            <div class="mb-4">
                <select wire:model="bulkAction"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Select Action</option>
                    @foreach ($this->bulkActionOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <x-button color="secondary" wire:click="closeAllModals">
                    Cancel
                </x-button>
                <x-button color="primary" wire:click="executeBulkAction" :disabled="!$bulkAction">
                    Confirm
                </x-button>
            </div>
        </div>
    </x-modal>

    @if ($selectedContent)
        <!-- Delete Confirmation Modal -->
    <x-modal id="delete-content-modal" title="Delete Content" wire:model="deleteContentModal" x-on:keydown.escape.window="$wire.closeModal()">
        <div class="p-6">
            <div class="flex items-center">
                <div class="shrink-0">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                    <div class="ml-3">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Content</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete this content? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <x-button color="secondary" wire:click="closeAllModals">
                        Cancel
                    </x-button>
                    <x-button color="danger" wire:click="deleteContent">
                        Delete
                    </x-button>
                </div>
            </div>
        </div>
    </x-modal>
    @endif

    <!-- Add these components to handle their own modals -->
    <livewire:content.content-show />
    <livewire:content.content-edit />
    <livewire:content.content-create />
    <livewire:content.content-preview />

    <x-modal wire:model="showWidgetTypeSelectorModal" id="widget-type-selector-modal" title="Select Widget Type" maxWidth="4xl">
        @if($showWidgetTypeSelectorModal)
            <livewire:tenant.content.widget-type-selector />
        @endif
    </x-modal>
</div>

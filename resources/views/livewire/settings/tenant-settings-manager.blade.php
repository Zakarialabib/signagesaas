<div class="space-y-8">
    <!-- Settings Navigation -->
    <livewire:components.settings-nav-bar active="general" />

    <!-- Settings Card -->
    <div class="bg-white/60 dark:bg-gray-800/60 shadow-lg shadow-indigo-200/30 dark:shadow-black/40 backdrop-blur-md rounded-2xl border border-white/30 dark:border-gray-700/40 overflow-hidden">
        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px px-6 overflow-x-auto hide-scrollbar" aria-label="Tabs">
                <button wire:click="setActiveTab('general')" type="button"
                    class="inline-flex items-center whitespace-nowrap py-4 px-1 font-medium text-sm transition-colors duration-200 ease-in-out border-b-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $activeTab === 'general' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('General') }}
                </button>
                <button wire:click="setActiveTab('appearance')" type="button"
                    class="inline-flex items-center whitespace-nowrap py-4 px-1 ml-8 font-medium text-sm transition-colors duration-200 ease-in-out border-b-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $activeTab === 'appearance' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    {{ __('Appearance') }}
                </button>
                <button wire:click="setActiveTab('content')" type="button"
                    class="inline-flex items-center whitespace-nowrap py-4 px-1 ml-8 font-medium text-sm transition-colors duration-200 ease-in-out border-b-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $activeTab === 'content' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    {{ __('Content') }}
                </button>
                <button wire:click="setActiveTab('notifications')" type="button"
                    class="inline-flex items-center whitespace-nowrap py-4 px-1 ml-8 font-medium text-sm transition-colors duration-200 ease-in-out border-b-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $activeTab === 'notifications' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    {{ __('Notifications') }}
                </button>
                <button wire:click="setActiveTab('advanced')" type="button"
                    class="inline-flex items-center whitespace-nowrap py-4 px-1 ml-8 font-medium text-sm transition-colors duration-200 ease-in-out border-b-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $activeTab === 'advanced' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    {{ __('Advanced') }}
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <form wire:submit="saveSettings">
                <!-- Success Alert -->
                <div x-data="{ show: @entangle('showSuccessAlert') }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="mb-6 bg-green-50 dark:bg-green-900/30 backdrop-blur-sm border border-green-200 dark:border-green-800/50 rounded-xl shadow-md p-4"
                    x-cloak>
                    <div class="flex items-start">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-green-500 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-green-800 dark:text-green-300">
                                {{ $alertMessage ?: __('Settings saved successfully!') }}
                            </p>
                        </div>
                        <div class="ml-auto">
                            <button type="button" @click="show = false" class="inline-flex text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 focus:outline-none">
                                <span class="sr-only">{{ __('Dismiss') }}</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- General Tab -->
                @if ($activeTab === 'general')
                <div class="space-y-8">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
                            {{ __('General Settings') }}
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Site Name -->
                            <div class="sm:col-span-3">
                                <label for="siteName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Site Name') }}
                                </label>
                                <div class="mt-1 relative">
                                    <input type="text" id="siteName" wire:model="siteName"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 rounded-lg transition-colors duration-200 ease-in-out"
                                        placeholder="SignageSaaS"
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                    @error('siteName')
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    @enderror
                                </div>
                                @error('siteName')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Email -->
                            <div class="sm:col-span-3">
                                <label for="contactEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Contact Email') }}
                                </label>
                                <div class="mt-1 relative">
                                    <input type="email" id="contactEmail" wire:model="contactEmail"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 rounded-lg transition-colors duration-200 ease-in-out"
                                        placeholder="admin@example.com"
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                    @error('contactEmail')
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    @enderror
                                </div>
                                @error('contactEmail')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Timezone -->
                            <div class="sm:col-span-3">
                                <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Timezone') }}
                                </label>
                                <div class="mt-1">
                                    <select id="timezone" wire:model="timezone"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 rounded-lg transition-colors duration-200 ease-in-out"
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                        @foreach ($this->getTimezones() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('timezone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date Format -->
                            <div class="sm:col-span-3">
                                <label for="dateFormat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Date Format') }}
                                </label>
                                <div class="mt-1">
                                    <select id="dateFormat" wire:model="dateFormat"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 rounded-lg transition-colors duration-200 ease-in-out"
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                        @foreach ($this->getDateFormats() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('dateFormat')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Time Format -->
                            <div class="sm:col-span-3">
                                <label for="timeFormat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Time Format') }}
                                </label>
                                <div class="mt-1">
                                    <select id="timeFormat" wire:model="timeFormat"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 rounded-lg transition-colors duration-200 ease-in-out"
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                        @foreach ($this->getTimeFormats() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('timeFormat')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- This area would contain the other tabs -->

                <!-- Save Button -->
                <div class="pt-5 border-t border-gray-200 dark:border-gray-700 mt-8">
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ !$canEdit ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Save Settings') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 
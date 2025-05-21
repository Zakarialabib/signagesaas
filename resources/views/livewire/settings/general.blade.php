<div>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Settings Tabs -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button
                        class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('General') }}
                    </button>
                    <button
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('Appearance') }}
                    </button>
                    <button
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('Notifications') }}
                    </button>
                    <button
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('Advanced') }}
                    </button>
                    <button wire:click="setActiveTab('subscription')"
                        class="{{ $activeTab === 'subscription' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('Subscription') }}
                    </button>
                </nav>
            </div>

            <div class="px-6 py-5">
                <form wire:submit="saveSettings">
                    <div class="mb-8">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('General Settings') }}</h3>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-5">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <!-- Site Name -->
                                <div class="sm:col-span-3">
                                    <label for="siteName" class="block text-sm font-medium text-gray-700">
                                        {{ __('Site Name') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="siteName" wire:model="siteName"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error('siteName')
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('siteName')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Contact Email -->
                                <div class="sm:col-span-3">
                                    <label for="contactEmail" class="block text-sm font-medium text-gray-700">
                                        {{ __('Contact Email') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="email" id="contactEmail" wire:model="contactEmail"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error('contactEmail')
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('contactEmail')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Timezone -->
                                <div class="sm:col-span-3">
                                    <label for="timezone" class="block text-sm font-medium text-gray-700">
                                        {{ __('Timezone') }}
                                    </label>
                                    <div class="mt-1">
                                        <select id="timezone" wire:model="timezone"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            @foreach ($timezones as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('timezone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Language -->
                                <div class="sm:col-span-3">
                                    <label for="language" class="block text-sm font-medium text-gray-700">
                                        {{ __('Language') }}
                                    </label>
                                    <div class="mt-1">
                                        <select id="language" wire:model="language"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            @foreach ($languages as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('language')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Date Format -->
                                <div class="sm:col-span-3">
                                    <label for="dateFormat" class="block text-sm font-medium text-gray-700">
                                        {{ __('Date Format') }}
                                    </label>
                                    <div class="mt-1">
                                        <select id="dateFormat" wire:model="dateFormat"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            @foreach ($dateFormats as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('dateFormat')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Time Format -->
                                <div class="sm:col-span-3">
                                    <label for="timeFormat" class="block text-sm font-medium text-gray-700">
                                        {{ __('Time Format') }}
                                    </label>
                                    <div class="mt-1">
                                        <select id="timeFormat" wire:model="timeFormat"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            @foreach ($timeFormats as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('timeFormat')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Application URL -->
                                <div class="sm:col-span-3">
                                    <label for="appUrl" class="block text-sm font-medium text-gray-700">
                                        {{ __('Application URL') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="url" id="appUrl" wire:model="appUrl"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            placeholder="https://example.com">
                                        @error('appUrl')
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('appUrl')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('Tenant Settings') }}</h3>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-5">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <!-- Content Rotation Interval -->
                                <div class="sm:col-span-3">
                                    <label for="contentRotationInterval" class="block text-sm font-medium text-gray-700">
                                        {{ __('Content Rotation Interval (seconds)') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" id="contentRotationInterval" wire:model="contentRotationInterval"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            min="5" max="3600">
                                        @error('contentRotationInterval')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('contentRotationInterval')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Device Connectivity Threshold -->
                                <div class="sm:col-span-3">
                                    <label for="deviceConnectivityThreshold" class="block text-sm font-medium text-gray-700">
                                        {{ __('Device Connectivity Threshold (minutes)') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" id="deviceConnectivityThreshold" wire:model="deviceConnectivityThreshold"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            min="1" max="1440">
                                        @error('deviceConnectivityThreshold')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('deviceConnectivityThreshold')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Default Screen Duration -->
                                <div class="sm:col-span-3">
                                    <label for="defaultScreenDuration"
                                        class="block text-sm font-medium text-gray-700">
                                        {{ __('Default Screen Duration (seconds)') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" id="defaultScreenDuration"
                                            wire:model="defaultScreenDuration"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            min="10" max="300">
                                        @error('defaultScreenDuration')
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('defaultScreenDuration')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Company Logo -->
                                <div class="sm:col-span-3">
                                    <label for="companyLogo" class="block text-sm font-medium text-gray-700">
                                        {{ __('Company Logo URL') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="companyLogo" wire:model="companyLogo"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error('companyLogo')
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('companyLogo')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Emergency Message Template -->
                                <div class="sm:col-span-6">
                                    <label for="emergencyMessageTemplate" class="block text-sm font-medium text-gray-700">
                                        {{ __('Emergency Message Template') }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="emergencyMessageTemplate" wire:model="emergencyMessageTemplate" rows="3"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                        @error('emergencyMessageTemplate')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('This message will override all content during emergencies') }}
                                    </p>
                                </div>

                                <!-- Brand Colors Section -->
                                <div class="sm:col-span-6">
                                    <div class="mb-4 border-t border-gray-200 pt-4">
                                        <h4 class="text-sm font-semibold text-gray-800">{{ __('Brand Colors') }}</h4>
                                    </div>
                                </div>

                                <!-- Primary Color -->
                                <div class="sm:col-span-3">
                                    <label for="primaryColor" class="block text-sm font-medium text-gray-700">
                                        {{ __('Primary Color') }}
                                    </label>
                                    <div class="mt-1 flex items-center">
                                        <input type="color" id="primaryColorPicker" wire:model="primaryColor"
                                            class="h-8 w-8 mr-2 border-0 rounded-md focus:ring-0">
                                        <div class="relative rounded-md shadow-sm grow">
                                            <input type="text" id="primaryColor" wire:model="primaryColor"
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                placeholder="#000000">
                                            @error('primaryColor')
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @error('primaryColor')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('Used for buttons, links, and active states') }}</p>
                                </div>

                                <!-- Secondary Color -->
                                <div class="sm:col-span-3">
                                    <label for="secondaryColor" class="block text-sm font-medium text-gray-700">
                                        {{ __('Secondary Color') }}
                                    </label>
                                    <div class="mt-1 flex items-center">
                                        <input type="color" id="secondaryColorPicker" wire:model="secondaryColor"
                                            class="h-8 w-8 mr-2 border-0 rounded-md focus:ring-0">
                                        <div class="relative rounded-md shadow-sm grow">
                                            <input type="text" id="secondaryColor" wire:model="secondaryColor"
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                placeholder="#000000">
                                            @error('secondaryColor')
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @error('secondaryColor')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('Used for secondary elements and accents') }}</p>
                                </div>

                                <!-- Tenant Subdomain -->
                                <div class="sm:col-span-3">
                                    <label for="tenantSubdomain" class="block text-sm font-medium text-gray-700">
                                        {{ __('Tenant Subdomain') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">https://</span>
                                        </div>
                                        <input type="text" id="tenantSubdomain" wire:model.debounce.500ms="tenantSubdomain"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 sm:text-sm border-gray-300 rounded-md"
                                            placeholder="your-subdomain">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">.{{ parse_url(config('app.url'), PHP_URL_HOST) }}</span>
                                        </div>
                                        @error('tenantSubdomain')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('tenantSubdomain')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @if($tenantSubdomain && !$errors->has('tenantSubdomain'))
                                        <p class="mt-2 text-sm text-green-600">
                                            {{ __('Available! Your full URL will be:') }} https://{{ $tenantSubdomain }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('Notification Settings') }}
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <!-- Enable App Notifications -->
                                <div class="sm:col-span-3">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="enableNotifications" wire:model="enableNotifications"
                                                type="checkbox"
                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="enableNotifications" class="font-medium text-gray-700">
                                                {{ __('Enable App Notifications') }}
                                            </label>
                                            <p class="text-gray-500">
                                                {{ __('Receive notifications for system events and updates') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enable Email Notifications -->
                                <div class="sm:col-span-3">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="notificationsEnabled" wire:model="notificationsEnabled"
                                                type="checkbox"
                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="notificationsEnabled" class="font-medium text-gray-700">
                                                {{ __('Enable Email Notifications') }}
                                            </label>
                                            <p class="text-gray-500">
                                                {{ __('Receive email notifications for important events') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notification Email -->
                                <div class="sm:col-span-3">
                                    <label for="notificationEmail" class="block text-sm font-medium text-gray-700">
                                        {{ __('Notification Email') }}
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="email" id="notificationEmail" wire:model="notificationEmail"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            placeholder="notify@example.com">
                                        @error('notificationEmail')
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('notificationEmail')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-gray-200 mt-8">
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Save Settings') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Subscription Tab Content --}}
            @if ($activeTab === 'subscription')
                <div class="px-6 py-5">
                    <livewire:settings.subscription-manager />
                </div>
            @endif

        </div>
    </div>
</div>

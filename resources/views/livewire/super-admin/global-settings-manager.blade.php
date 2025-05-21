<div>
    <!-- Page Header -->
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Global Settings</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Manage system-wide settings for your SignageSaaS platform.
            </p>
        </div>
    </div>

    <!-- Success Alert -->
    @if($showSuccessAlert)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
            class="rounded-md bg-green-50 dark:bg-green-900 p-4 mb-6">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        Settings saved successfully.
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button wire:click="hideSuccessAlert" type="button" 
                            class="inline-flex rounded-md p-1.5 text-green-500 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-green-900">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button wire:click="setActiveTab('general')" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'general' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                    General
                </button>
                <button wire:click="setActiveTab('tenant')" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'tenant' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                    Tenant Settings
                </button>
                <button wire:click="setActiveTab('email')" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'email' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                    Email
                </button>
                <button wire:click="setActiveTab('api')" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'api' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                    API
                </button>
                <button wire:click="setActiveTab('integration')" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'integration' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                    Integrations
                </button>
            </nav>
        </div>
        
        <div class="p-6">
            <!-- General Settings Tab -->
            @if($activeTab === 'general')
                <form wire:submit="saveGeneralSettings">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">General Settings</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Basic configuration for your SignageSaaS platform.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Name</label>
                                <div class="mt-1">
                                    <input type="text" wire:model="site_name" id="site_name" 
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('site_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Admin Email</label>
                                <div class="mt-1">
                                    <input type="email" wire:model="admin_email" id="admin_email" 
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('admin_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="support_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Support URL</label>
                                <div class="mt-1">
                                    <input type="url" wire:model="support_url" id="support_url" 
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('support_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" wire:model="enable_new_registrations" id="enable_new_registrations" 
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="enable_new_registrations" class="font-medium text-gray-700 dark:text-gray-300">Enable New Registrations</label>
                                    <p class="text-gray-500 dark:text-gray-400">Allow new tenants to register on the platform.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="resetSettings" 
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset to Defaults
                            </button>
                            <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            
            <!-- Tenant Settings Tab -->
            @if($activeTab === 'tenant')
                <form wire:submit="saveTenantSettings">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Tenant Settings</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Configure tenant-related settings.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" wire:model="enable_trial_period" id="enable_trial_period" 
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="enable_trial_period" class="font-medium text-gray-700 dark:text-gray-300">Enable Trial Period</label>
                                    <p class="text-gray-500 dark:text-gray-400">Allow new tenants to have a trial period.</p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="trial_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trial Days</label>
                                <div class="mt-1">
                                    <input type="number" wire:model="trial_days" id="trial_days" min="0" max="365"
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('trial_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" wire:model="require_payment_for_trial" id="require_payment_for_trial" 
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="require_payment_for_trial" class="font-medium text-gray-700 dark:text-gray-300">Require Payment for Trial</label>
                                    <p class="text-gray-500 dark:text-gray-400">Require payment information to start a trial.</p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="default_tenant_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default Tenant Mode</label>
                                <div class="mt-1">
                                    <select wire:model="default_tenant_mode" id="default_tenant_mode" 
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                        <option value="subdomain">Subdomain</option>
                                        <option value="domain">Custom Domain</option>
                                        <option value="path">Path-based</option>
                                    </select>
                                    @error('default_tenant_mode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="resetSettings" 
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset to Defaults
                            </button>
                            <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            
            <!-- Email Settings Tab -->
            @if($activeTab === 'email')
                <form wire:submit="saveEmailSettings">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Email Settings</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Configure email delivery settings.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="mail_driver" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Driver</label>
                                <div class="mt-1">
                                    <select wire:model="mail_driver" id="mail_driver" 
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                        <option value="smtp">SMTP</option>
                                        <option value="mailgun">Mailgun</option>
                                        <option value="ses">Amazon SES</option>
                                        <option value="postmark">Postmark</option>
                                        <option value="sendmail">Sendmail</option>
                                    </select>
                                    @error('mail_driver') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Address</label>
                                <div class="mt-1">
                                    <input type="email" wire:model="mail_from_address" id="mail_from_address" 
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('mail_from_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Name</label>
                                <div class="mt-1">
                                    <input type="text" wire:model="mail_from_name" id="mail_from_name" 
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('mail_from_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="resetSettings" 
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset to Defaults
                            </button>
                            <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            
            <!-- API Settings Tab -->
            @if($activeTab === 'api')
                <form wire:submit="saveApiSettings">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">API Settings</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Configure API access settings.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" wire:model="enable_api" id="enable_api" 
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="enable_api" class="font-medium text-gray-700 dark:text-gray-300">Enable API</label>
                                    <p class="text-gray-500 dark:text-gray-400">Enable API access for tenants.</p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="api_token_expiry_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Token Expiry (minutes)</label>
                                <div class="mt-1">
                                    <input type="number" wire:model="api_token_expiry_minutes" id="api_token_expiry_minutes" min="1" max="1440"
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('api_token_expiry_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Set to 0 for non-expiring tokens (not recommended for production).
                                </p>
                            </div>
                            
                            <div>
                                <label for="api_rate_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Rate Limit (requests per minute)</label>
                                <div class="mt-1">
                                    <input type="number" wire:model="api_rate_limit" id="api_rate_limit" min="1" max="1000"
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('api_rate_limit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="resetSettings" 
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset to Defaults
                            </button>
                            <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            
            <!-- Integration Settings Tab -->
            @if($activeTab === 'integration')
                <form wire:submit="saveIntegrationSettings">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Integration Settings</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Configure third-party service integrations.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Analytics ID</label>
                                <div class="mt-1">
                                    <input type="text" wire:model="google_analytics_id" id="google_analytics_id" placeholder="e.g., UA-XXXXXXXXX-X or G-XXXXXXXXXX"
                                        class="py-2 ps-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                    @error('google_analytics_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Enter your Google Analytics tracking ID to enable analytics for your platform.
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="resetSettings" 
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset to Defaults
                            </button>
                            <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div> 
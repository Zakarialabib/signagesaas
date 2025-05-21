<div>
    @if($tenant)
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    Subscription Management for {{ $tenant->name }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage subscription details and settings for this tenant.
                </p>
            </div>
            
            @if($subscription)
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                        <div>
                            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">Current Plan</h4>
                            <div class="mt-2 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $subscription->plan->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            @if($subscription->billing_cycle === 'monthly')
                                                ${{ number_format($subscription->plan->price_monthly, 2) }}/month
                                            @else
                                                ${{ number_format($subscription->plan->price_yearly, 2) }}/year
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($subscription->status === 'active') 
                                            bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                        @elseif($subscription->status === 'trialing') 
                                            bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                        @elseif($subscription->status === 'canceled') 
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                        @else
                                            bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                        @endif">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">Billing Period</h4>
                            <div class="mt-2 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Current Period</span>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $subscription->current_period_starts_at->format('M d, Y') }} - 
                                            {{ $subscription->current_period_ends_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Auto Renew</span>
                                        <span class="text-sm">
                                            @if($subscription->auto_renew)
                                                <span class="text-green-600 dark:text-green-400">Enabled</span>
                                            @else
                                                <span class="text-red-600 dark:text-red-400">Disabled</span>
                                            @endif
                                        </span>
                                    </div>
                                    
                                    @if($subscription->trial_ends_at)
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Trial Ends</span>
                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $subscription->trial_ends_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    @if($subscription->canceled_at)
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Canceled On</span>
                                            <span class="text-sm text-red-600 dark:text-red-400">
                                                {{ $subscription->canceled_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">Usage Limits</h4>
                            <div class="mt-2 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Devices</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $subscription->getMaxDevices() }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Screens</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $subscription->getMaxScreens() }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Users</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $subscription->getMaxUsers() }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Storage</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $subscription->getMaxStorageMb() }} MB
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Bandwidth</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $subscription->getMaxBandwidthMb() }} MB
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-3">
                        <button wire:click="editSubscription" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Edit Subscription
                        </button>
                        
                        @if(!$subscription->isCanceled())
                            <button wire:click="confirmCancel" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel Subscription
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="px-4 py-5 sm:p-6">
                    <div class="text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">No Active Subscription</h3>
                        <div class="mt-2 max-w-xl text-sm text-gray-500 dark:text-gray-400 mx-auto">
                            <p>This tenant doesn't have an active subscription. Create one to enable access to the platform.</p>
                        </div>
                        <div class="mt-5">
                            <button wire:click="editSubscription" type="button" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Subscription
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No tenant selected</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Please select a tenant to manage their subscription.</p>
        </div>
    @endif

    <!-- Edit Subscription Modal -->
    <x-modal wire:model="showEditModal" max-width="2xl">
        <x-slot name="title">
            {{ $subscription ? 'Edit Subscription' : 'Create Subscription' }}
        </x-slot>

        <form>
            <div class="space-y-6">
                <div>
                    <label for="plan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan</label>
                    <select wire:model="plan_id" id="plan_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Select a plan</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} (${{ $plan->price_monthly }}/month)</option>
                        @endforeach
                    </select>
                    @error('plan_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model="status" id="status" 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="active">Active</option>
                            <option value="trialing">Trialing</option>
                            <option value="canceled">Canceled</option>
                            <option value="past_due">Past Due</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="billing_cycle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Cycle</label>
                        <select wire:model="billing_cycle" id="billing_cycle" 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        @error('billing_cycle') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="trial_ends_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trial End Date</label>
                        <input type="datetime-local" wire:model="trial_ends_at" id="trial_ends_at" 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('trial_ends_at') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center space-x-2 mt-6">
                        <input type="checkbox" wire:model="auto_renew" id="auto_renew" 
                            class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 focus:ring-indigo-500">
                        <label for="auto_renew" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Auto Renew</label>
                        @error('auto_renew') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="current_period_starts_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Period Start Date</label>
                        <input type="datetime-local" wire:model="current_period_starts_at" id="current_period_starts_at" 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('current_period_starts_at') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="current_period_ends_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Period End Date</label>
                        <input type="datetime-local" wire:model="current_period_ends_at" id="current_period_ends_at" 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('current_period_ends_at') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div x-data="{ showCustomLimits: false }">
                    <div class="flex items-center space-x-2">
                        <button type="button" @click="showCustomLimits = !showCustomLimits" 
                            class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                            <span x-text="showCustomLimits ? 'Hide Custom Limits' : 'Show Custom Limits'"></span>
                            <svg x-show="!showCustomLimits" class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            <svg x-show="showCustomLimits" class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        </button>
                    </div>

                    <div x-show="showCustomLimits" class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Custom Resource Limits</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Override the default limits from the plan. Leave blank to use the plan defaults.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="max_devices" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Devices</label>
                                <input type="number" wire:model="max_devices" id="max_devices" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('max_devices') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="max_screens" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Screens</label>
                                <input type="number" wire:model="max_screens" id="max_screens" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('max_screens') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="max_users" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Users</label>
                                <input type="number" wire:model="max_users" id="max_users" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('max_users') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="max_storage_mb" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Storage (MB)</label>
                                <input type="number" wire:model="max_storage_mb" id="max_storage_mb" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('max_storage_mb') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="max_bandwidth_mb" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Bandwidth (MB)</label>
                                <input type="number" wire:model="max_bandwidth_mb" id="max_bandwidth_mb" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('max_bandwidth_mb') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="$set('showEditModal', false)" 
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <button type="button" wire:click="saveSubscription" 
                    class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $subscription ? 'Update' : 'Create' }}
                </button>
            </div>
        </x-slot>
    </x-modal>

    <!-- Cancel Subscription Modal -->
    <x-modal wire:model="showCancelModal">
        <x-slot name="title">
            Cancel Subscription
        </x-slot>

        <div>
            <p class="text-sm text-gray-700 dark:text-gray-300">
                How would you like to cancel this subscription?
            </p>
            
            <div class="mt-4 space-y-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="cancel-end-period" name="cancel-type" type="radio" checked
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="cancel-end-period" class="font-medium text-gray-700 dark:text-gray-300">Cancel at end of billing period</label>
                        <p class="text-gray-500 dark:text-gray-400">Subscription will remain active until the end of the current period.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="cancel-immediately" name="cancel-type" type="radio"
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="cancel-immediately" class="font-medium text-gray-700 dark:text-gray-300">Cancel immediately</label>
                        <p class="text-gray-500 dark:text-gray-400">Subscription will be terminated immediately with no refund.</p>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="$set('showCancelModal', false)" 
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Never Mind
                </button>
                <button type="button" wire:click="cancelSubscription(false)" 
                    class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Cancel at Period End
                </button>
                <button type="button" wire:click="cancelSubscription(true)" 
                    class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Cancel Immediately
                </button>
            </div>
        </x-slot>
    </x-modal>
</div> 
<div>
    {{-- Subscription Management UI will go here --}}
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Subscription Management</h2>

    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-200 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        @if ($subscription && $subscription->plan)
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">Current Plan: {{ $subscription->plan->name }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Billing Cycle: {{ ucfirst($subscription->billing_cycle) }}
                @if ($subscription->current_period_ends_at)
                    | Renews on: {{ $subscription->current_period_ends_at->format('M d, Y') }}
                @endif
            </p>

            {{-- Usage Quota Display --}}
            @if ($usageQuota && $subscription->plan->hasLimits())
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Usage Quotas</h4>
                    <ul class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-300">
                        <li>Devices: {{ $usageQuota->devices_count }} / {{ $subscription->plan->limit_devices ?? 'Unlimited' }}</li>
                        <li>Screens: {{ $usageQuota->screens_count }} / {{ $subscription->plan->limit_screens ?? 'Unlimited' }}</li>
                        <li>Users: {{ $usageQuota->users_count }} / {{ $subscription->plan->limit_users ?? 'Unlimited' }}</li>
                        <li>Storage: {{ number_format($usageQuota->storage_used_mb / 1024, 2) }} GB / {{ $subscription->plan->limit_storage_gb ?? 'Unlimited' }} GB</li>
                        <li>Bandwidth: {{ number_format($usageQuota->bandwidth_used_mb / 1024, 2) }} GB / {{ $subscription->plan->limit_bandwidth_gb ?? 'Unlimited' }} GB</li>
                    </ul>
                </div>
            @endif

            {{-- Plan Change/Cancel Buttons --}}
            <div class="mt-6 flex flex-wrap gap-3">
                <button wire:click="openPlanComparisonModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Change Plan
                </button>
                @if ($subscription->status !== 'canceled')
                    <button wire:click="openCancelModal" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel Subscription
                    </button>
                @endif
            </div>

        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">No active subscription found. Please choose a plan.</p>
            {{-- Button to choose plan --}}
            <div class="mt-4">
                 <button wire:click="openPlanComparisonModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Choose a Plan
                </button>
            </div>
        @endif
    </div>

    {{-- Modals for Plan Change, Cancellation etc. --}}
    @include('livewire.settings.partials.subscription-modals')

</div>
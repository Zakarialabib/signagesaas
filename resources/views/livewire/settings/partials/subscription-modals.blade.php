{{-- Plan Comparison Modal --}}
<x-modal wire:model="showPlanComparisonModal">
    <x-slot name="title">
        {{ __('Choose Your Plan') }}
    </x-slot>

    <div class="p-2">
        <div class="flex justify-end mb-4">
            <div class="inline-flex items-center rounded-full border border-gray-300 dark:border-gray-600 p-1">
                <button type="button"
                    class="px-3 py-1 text-sm rounded-full {{ $billingCycle === 'monthly' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300' }}"
                    wire:click="$set('billingCycle', 'monthly')">
                    Monthly
                </button>
                <button type="button"
                    class="px-3 py-1 text-sm rounded-full {{ $billingCycle === 'yearly' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300' }}"
                    wire:click="$set('billingCycle', 'yearly')">
                    Yearly
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-{{ min($availablePlans->count(), 3) }} gap-6">
            @foreach ($availablePlans as $plan)
                <div
                    class="border dark:border-gray-700 rounded-lg p-4 flex flex-col 
                    {{ $subscription && $subscription->plan_id === $plan->id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-800' : '' }}">
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $plan->name }}</div>
                    <div class="mt-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $billingCycle === 'monthly' ? '$' . number_format($plan->price_monthly, 2) . '/mo' : '$' . number_format($plan->price_yearly, 2) . '/yr' }}
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $plan->description }}</p>

                    <div class="mt-4 space-y-2 grow">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Features:</div>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $plan->max_devices }} Devices</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $plan->max_screens }} Screens</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $plan->max_users }} Users</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ number_format($plan->max_storage_mb / 1024, 1) }} GB Storage</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-6">
                        @if (!$subscription || $subscription->plan_id !== $plan->id)
                            @if ($subscription && $this->isPlanUpgrade($plan->id))
                                <button wire:click="openUpgradeModal('{{ $plan->id }}')"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Upgrade
                                </button>
                            @else
                                <button wire:click="openDowngradeModal('{{ $plan->id }}')"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Downgrade
                                </button>
                            @endif
                        @else
                            <div
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-indigo-300 dark:border-indigo-700 text-sm font-medium rounded-md text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20">
                                Current Plan
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-slot name="footer">
        <div class="flex justify-end">
            <button wire:click="closeModals"
                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Close
            </button>
        </div>
    </x-slot>
</x-modal>

{{-- Upgrade Modal --}}
<x-modal wire:model="showUpgradeModal">
    <x-slot name="title">
        {{ __('Upgrade to Premium Plan') }}
    </x-slot>

    <div class="p-4">
        @if ($selectedPlanId && ($plan = $availablePlans->firstWhere('id', $selectedPlanId)))
            <div class="text-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $plan->name }}</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $plan->description }}</p>
                <div class="mt-4 text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $billingCycle === 'monthly' ? '$' . number_format($plan->price_monthly, 2) . '/mo' : '$' . number_format($plan->price_yearly, 2) . '/yr' }}
                </div>
            </div>

            <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-md p-4">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</h4>

                {{-- Dummy payment form for demonstration --}}
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="cardNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Card
                            Number</label>
                        <input type="text" id="cardNumber" wire:model="cardNumber" placeholder="4242 4242 4242 4242"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="cardExpiry"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiry Date</label>
                            <input type="text" id="cardExpiry" wire:model="cardExpiry" placeholder="MM/YY"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="cardCvc"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">CVC</label>
                            <input type="text" id="cardCvc" wire:model="cardCvc" placeholder="123"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                No plan selected. Please go back and select a plan.
            </div>
        @endif
    </div>

    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <button wire:click="closeModals"
                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button wire:click="upgradePlan"
                class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Confirm Upgrade
            </button>
        </div>
    </x-slot>
</x-modal>

{{-- Downgrade Modal --}}
<x-modal wire:model="showDowngradeModal">
    <x-slot name="title">
        {{ __('Change Your Plan') }}
    </x-slot>

    <div class="p-4">
        @if ($selectedPlanId && ($plan = $availablePlans->firstWhere('id', $selectedPlanId)))
            <div class="text-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $plan->name }}</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $plan->description }}</p>
                <div class="mt-4 text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $billingCycle === 'monthly' ? '$' . number_format($plan->price_monthly, 2) . '/mo' : '$' . number_format($plan->price_yearly, 2) . '/yr' }}
                </div>
            </div>

            <div class="mt-4 rounded-md bg-yellow-50 dark:bg-yellow-900/20 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Important Information</h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <p>Changing your plan will take effect immediately. Please ensure this plan meets your needs
                                before confirming.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                No plan selected. Please go back and select a plan.
            </div>
        @endif
    </div>

    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <button wire:click="closeModals"
                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button wire:click="downgradePlan"
                class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Confirm Change
            </button>
        </div>
    </x-slot>
</x-modal>

{{-- Cancel Subscription Modal --}}
<x-modal wire:model="showCancelModal">
    <x-slot name="title">
        {{ __('Cancel Subscription') }}
    </x-slot>

    <div class="p-4">
        <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4 mb-4">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Are you sure you want to cancel?
                    </h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>You'll lose access to premium features at the end of your current billing period.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <label for="cancellationReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Please
                tell us why you're canceling</label>
            <div class="mt-1">
                <textarea id="cancellationReason" wire:model="cancellationReason" rows="3"
                    class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"></textarea>
                @error('cancellationReason')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <button wire:click="closeModals"
                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Go Back
            </button>
            <button wire:click="cancelSubscription"
                class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Cancel Subscription
            </button>
        </div>
    </x-slot>
</x-modal>

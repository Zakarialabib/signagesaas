<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Tenant\Models\Plan;
use App\Tenant\Models\Subscription;
use App\Tenant\Models\Tenant;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;

final class SubscriptionManager extends Component
{
    use WithPagination;

    #[Locked]
    public ?Tenant $tenant = null;

    #[Locked]
    public ?Subscription $subscription = null;

    public bool $showEditModal = false;

    // Subscription Edit Form Properties
    #[Validate('required|uuid|exists:plans,id')]
    public string $plan_id = '';

    #[Validate('required|string|in:active,canceled,past_due,trialing,unpaid')]
    public string $status = 'active';

    #[Validate('required|string|in:monthly,yearly')]
    public string $billing_cycle = 'monthly';

    #[Validate('nullable|date')]
    public ?string $trial_ends_at = null;

    #[Validate('required|date')]
    public string $current_period_starts_at = '';

    #[Validate('required|date|after_or_equal:current_period_starts_at')]
    public string $current_period_ends_at = '';

    #[Validate('nullable|date')]
    public ?string $canceled_at = null;

    #[Validate('boolean')]
    public bool $auto_renew = true;

    #[Validate('nullable|array')]
    public array $custom_limits = [];

    // Custom Limits Fields
    #[Validate('nullable|integer|min:0')]
    public ?int $max_devices = null;

    #[Validate('nullable|integer|min:0')]
    public ?int $max_screens = null;

    #[Validate('nullable|integer|min:0')]
    public ?int $max_users = null;

    #[Validate('nullable|integer|min:0')]
    public ?int $max_storage_mb = null;

    #[Validate('nullable|integer|min:0')]
    public ?int $max_bandwidth_mb = null;

    // Cancel Subscription
    public bool $showCancelModal = false;

    public function mount(?string $tenantId = null): void
    {
        if ($tenantId) {
            $this->tenant = Tenant::findOrFail($tenantId);
            $this->authorize('update', $this->tenant);

            // Get the active subscription
            $this->subscription = Subscription::where('tenant_id', $this->tenant->id)
                ->where('status', 'active')
                ->latest()
                ->first();
        }
    }

    public function render()
    {
        return view('livewire.super-admin.subscription-manager', [
            'plans' => $this->getAvailablePlans(),
        ]);
    }

    private function getAvailablePlans()
    {
        return Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function editSubscription(): void
    {
        if ( ! $this->subscription) {
            // If no subscription exists, prepare to create a new one
            $this->resetForm();

            // Set default values for a new subscription
            $this->current_period_starts_at = now()->format('Y-m-d\TH:i');
            $this->current_period_ends_at = now()->addMonth()->format('Y-m-d\TH:i');

            // Get current plan from tenant data if available
            if (isset($this->tenant->data['plan'])) {
                $this->plan_id = $this->tenant->data['plan'];
            } else {
                // Get first available plan if no plan is set
                $firstPlan = Plan::where('is_active', true)->first();

                if ($firstPlan) {
                    $this->plan_id = $firstPlan->id;
                }
            }
        } else {
            // Populate form with existing subscription data
            $this->plan_id = $this->subscription->plan_id;
            $this->status = $this->subscription->status;
            $this->billing_cycle = $this->subscription->billing_cycle;
            $this->trial_ends_at = $this->subscription->trial_ends_at?->format('Y-m-d\TH:i');
            $this->current_period_starts_at = $this->subscription->current_period_starts_at->format('Y-m-d\TH:i');
            $this->current_period_ends_at = $this->subscription->current_period_ends_at->format('Y-m-d\TH:i');
            $this->canceled_at = $this->subscription->canceled_at?->format('Y-m-d\TH:i');
            $this->auto_renew = $this->subscription->auto_renew;

            // Load custom limits
            $this->custom_limits = $this->subscription->custom_limits ?? [];
            $this->max_devices = $this->custom_limits['max_devices'] ?? null;
            $this->max_screens = $this->custom_limits['max_screens'] ?? null;
            $this->max_users = $this->custom_limits['max_users'] ?? null;
            $this->max_storage_mb = $this->custom_limits['max_storage_mb'] ?? null;
            $this->max_bandwidth_mb = $this->custom_limits['max_bandwidth_mb'] ?? null;
        }

        $this->showEditModal = true;
    }

    public function saveSubscription(): void
    {
        $this->validate();

        // Prepare custom limits
        $customLimits = [];

        if ($this->max_devices !== null) {
            $customLimits['max_devices'] = $this->max_devices;
        }

        if ($this->max_screens !== null) {
            $customLimits['max_screens'] = $this->max_screens;
        }

        if ($this->max_users !== null) {
            $customLimits['max_users'] = $this->max_users;
        }

        if ($this->max_storage_mb !== null) {
            $customLimits['max_storage_mb'] = $this->max_storage_mb;
        }

        if ($this->max_bandwidth_mb !== null) {
            $customLimits['max_bandwidth_mb'] = $this->max_bandwidth_mb;
        }

        $subscriptionData = [
            'tenant_id'                => $this->tenant->id,
            'plan_id'                  => $this->plan_id,
            'status'                   => $this->status,
            'billing_cycle'            => $this->billing_cycle,
            'trial_ends_at'            => $this->trial_ends_at ? Carbon::parse($this->trial_ends_at) : null,
            'current_period_starts_at' => Carbon::parse($this->current_period_starts_at),
            'current_period_ends_at'   => Carbon::parse($this->current_period_ends_at),
            'canceled_at'              => $this->canceled_at ? Carbon::parse($this->canceled_at) : null,
            'auto_renew'               => $this->auto_renew,
            'custom_limits'            => $customLimits,
        ];

        if ($this->subscription) {
            // Update existing subscription
            $this->subscription->update($subscriptionData);
        } else {
            // Create new subscription
            $this->subscription = Subscription::create($subscriptionData);
        }

        // Update tenant data with the plan
        $tenantData = $this->tenant->data ?? [];
        $tenantData['plan'] = $this->plan_id;
        $this->tenant->update(['data' => $tenantData]);

        $this->dispatch('subscription-updated');
        $this->showEditModal = false;
    }

    public function confirmCancel(): void
    {
        if ( ! $this->subscription) {
            return;
        }

        $this->showCancelModal = true;
    }

    public function cancelSubscription(bool $immediately = false): void
    {
        if ( ! $this->subscription) {
            return;
        }

        if ($immediately) {
            // Cancel immediately
            $this->subscription->update([
                'status'      => 'canceled',
                'canceled_at' => now(),
                'auto_renew'  => false,
            ]);
        } else {
            // Cancel at end of billing period
            $this->subscription->update([
                'canceled_at' => now(),
                'auto_renew'  => false,
            ]);
        }

        $this->dispatch('subscription-updated');
        $this->showCancelModal = false;
    }

    public function resetForm(): void
    {
        $this->reset([
            'plan_id', 'status', 'billing_cycle', 'trial_ends_at',
            'current_period_starts_at', 'current_period_ends_at',
            'canceled_at', 'auto_renew', 'custom_limits',
            'max_devices', 'max_screens', 'max_users',
            'max_storage_mb', 'max_bandwidth_mb',
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Tenant\Models\Plan;
use App\Tenant\Models\Subscription;
use App\Tenant\Models\UsageQuota;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Exception;

#[Layout('layouts.app')]
final class SubscriptionManager extends Component
{
    // Current subscription
    public ?Subscription $subscription = null;

    // Available plans
    public Collection $availablePlans;

    // Modal states
    public bool $showUpgradeModal = false;
    public bool $showDowngradeModal = false;
    public bool $showCancelModal = false;
    public bool $showPlanComparisonModal = false;

    // Upgrade/downgrade fields
    public ?string $selectedPlanId = null;
    public string $billingCycle = 'monthly';

    // Cancellation fields
    #[Rule('required|string|max:500')]
    public string $cancellationReason = '';

    // Usage quotas
    public ?UsageQuota $usageQuota = null;

    // Card details (for demo purposes)
    public string $cardNumber = '';
    public string $cardExpiry = '';
    public string $cardCvc = '';

    // Constructor
    public function mount(): void
    {
        // $this->authorize('viewAny', Auth::user());

        // Get the current subscription
        $this->subscription = Subscription::where('tenant_id', tenant('id'))
            ->with('plan')
            ->with('usageQuota')
            ->first();

        // If there's no subscription, create a free plan subscription
        if ( ! $this->subscription) {
            // In a real app, you'd redirect to subscription creation or show a message
            // This is just sample data for demonstration
            $freePlan = Plan::where('slug', 'free')->first();

            if ($freePlan) {
                DB::beginTransaction();

                try {
                    // Create subscription
                    $this->subscription = Subscription::create([
                        'tenant_id'                => tenant('id'),
                        'plan_id'                  => $freePlan->id,
                        'status'                   => 'active',
                        'billing_cycle'            => 'monthly',
                        'current_period_starts_at' => Carbon::now(),
                        'current_period_ends_at'   => Carbon::now()->addMonth(),
                    ]);

                    // Create usage quota
                    UsageQuota::create([
                        'tenant_id'         => tenant('id'),
                        'subscription_id'   => $this->subscription->id,
                        'devices_count'     => 0,
                        'screens_count'     => 0,
                        'users_count'       => 1, // At least the admin user
                        'storage_used_mb'   => 0,
                        'bandwidth_used_mb' => 0,
                    ]);

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    // Log the error
                    logger()->error('Error creating initial subscription: '.$e->getMessage());
                }
            }
        }

        // Load usage quota
        $this->usageQuota = $this->subscription?->usageQuota;

        // Get available plans
        $this->availablePlans = Plan::where('is_active', true)
            ->where('is_public', true)
            ->orderBy('sort_order')
            ->get();
    }

    // Modal methods
    public function openUpgradeModal(string $planId): void
    {
        $this->selectedPlanId = $planId;
        $this->showUpgradeModal = true;
    }

    public function openDowngradeModal(string $planId): void
    {
        $this->selectedPlanId = $planId;
        $this->showDowngradeModal = true;
    }

    public function openCancelModal(): void
    {
        $this->showCancelModal = true;
    }

    public function openPlanComparisonModal(): void
    {
        $this->showPlanComparisonModal = true;
    }

    public function closeModals(): void
    {
        $this->showUpgradeModal = false;
        $this->showDowngradeModal = false;
        $this->showCancelModal = false;
        $this->showPlanComparisonModal = false;
        $this->selectedPlanId = null;
        $this->cancellationReason = '';
    }

    // Plan management methods
    public function getSelectedPlan(): ?Plan
    {
        if ( ! $this->selectedPlanId) {
            return null;
        }

        return Plan::find($this->selectedPlanId);
    }

    public function upgradePlan(): void
    {
        $this->validate([
            'selectedPlanId' => 'required|exists:plans,id',
            'billingCycle'   => 'required|in:monthly,yearly',
        ]);

        $selectedPlan = $this->getSelectedPlan();

        if ( ! $selectedPlan || ! $this->subscription) {
            $this->closeModals();
            $this->addError('selectedPlanId', 'Invalid plan or subscription.');

            return;
        }

        // In a real app, you'd process payment and update subscription in your payment provider

        // Update subscription
        $this->subscription->update([
            'plan_id'                  => $selectedPlan->id,
            'billing_cycle'            => $this->billingCycle,
            'current_period_starts_at' => Carbon::now(),
            'current_period_ends_at'   => $this->billingCycle === 'monthly'
                ? Carbon::now()->addMonth()
                : Carbon::now()->addYear(),
            'status'      => 'active',
            'canceled_at' => null,
        ]);

        // Update usage quota if limits have changed
        // (In a real app, you'd check if new limits are lower than current usage)

        // Log this action
        // AuditLog::recordAction('upgrade_plan', 'subscription', $this->subscription->id, ['old_plan_id' => $this->subscription->getOriginal('plan_id')], ['new_plan_id' => $selectedPlan->id]);

        $this->closeModals();
        session()->flash('message', "Successfully upgraded to the {$selectedPlan->name} plan!");

        // Reload the subscription
        $this->subscription = Subscription::where('id', $this->subscription->id)
            ->with('plan')
            ->with('usageQuota')
            ->first();
    }

    public function downgradePlan(): void
    {
        $this->validate([
            'selectedPlanId' => 'required|exists:plans,id',
            'billingCycle'   => 'required|in:monthly,yearly',
        ]);

        $selectedPlan = $this->getSelectedPlan();

        if ( ! $selectedPlan || ! $this->subscription) {
            $this->closeModals();
            $this->addError('selectedPlanId', 'Invalid plan or subscription.');

            return;
        }

        // In a real app, you'd:
        // 1. Check if the current period is over
        // 2. Schedule the downgrade at the end of the current period
        // 3. Or prorate the current period if downgrading immediately

        // For simplicity, we'll downgrade immediately
        $this->subscription->update([
            'plan_id'                  => $selectedPlan->id,
            'billing_cycle'            => $this->billingCycle,
            'current_period_starts_at' => Carbon::now(),
            'current_period_ends_at'   => $this->billingCycle === 'monthly'
                ? Carbon::now()->addMonth()
                : Carbon::now()->addYear(),
        ]);

        // Log this action
        // AuditLog::recordAction('downgrade_plan', 'subscription', $this->subscription->id, ['old_plan_id' => $this->subscription->getOriginal('plan_id')], ['new_plan_id' => $selectedPlan->id]);

        $this->closeModals();
        session()->flash('message', "Successfully downgraded to the {$selectedPlan->name} plan!");

        // Reload the subscription
        $this->subscription = Subscription::where('id', $this->subscription->id)
            ->with('plan')
            ->with('usageQuota')
            ->first();
    }

    public function cancelSubscription(): void
    {
        $this->validate([
            'cancellationReason' => 'required|string|max:500',
        ]);

        if ( ! $this->subscription) {
            $this->closeModals();
            $this->addError('general', 'No active subscription found.');

            return;
        }

        // In a real app, you'd:
        // 1. Cancel the subscription in your payment provider
        // 2. Log the cancellation reason for analytics

        // Update subscription status
        $this->subscription->update([
            'status'      => 'canceled',
            'canceled_at' => Carbon::now(),
            // We'd typically store the cancellation reason in the metadata field
            'metadata' => array_merge($this->subscription->metadata ?? [], [
                'cancellation_reason' => $this->cancellationReason,
            ]),
        ]);

        // Log this action
        // AuditLog::recordAction('cancel_subscription', 'subscription', $this->subscription->id, [], ['reason' => $this->cancellationReason]);

        $this->closeModals();
        session()->flash('message', 'Your subscription has been canceled. You can continue using your current plan until the end of the billing period.');

        // Reload the subscription
        $this->subscription = Subscription::where('id', $this->subscription->id)
            ->with('plan')
            ->with('usageQuota')
            ->first();
    }

    // Helpers
    public function isPlanCurrentlySelected(string $planId): bool
    {
        return $this->subscription && $this->subscription->plan_id === $planId;
    }

    public function isPlanUpgrade(string $planId): bool
    {
        if ( ! $this->subscription) {
            return true;
        }

        $currentPlan = $this->subscription->plan;
        $targetPlan = Plan::find($planId);

        if ( ! $currentPlan || ! $targetPlan) {
            return false;
        }

        // Simple comparison based on price
        // In a real app, you might have a more complex tier system
        return $this->billingCycle === 'monthly'
            ? $targetPlan->price_monthly > $currentPlan->price_monthly
            : $targetPlan->price_yearly > $currentPlan->price_yearly;
    }

    public function getPlanPriceFormatted(Plan $plan): string
    {
        $price = $this->billingCycle === 'monthly'
            ? $plan->price_monthly
            : $plan->price_yearly;

        return '$'.number_format((float) $price, 2);
    }

    public function getBillingCycleDisplay(): string
    {
        return $this->billingCycle === 'monthly' ? 'Monthly' : 'Yearly';
    }

    public function toggleBillingCycle(): void
    {
        $this->billingCycle = $this->billingCycle === 'monthly' ? 'yearly' : 'monthly';
    }

    public function render()
    {
        return view('livewire.settings.subscription-manager');
    }
}

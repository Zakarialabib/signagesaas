<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use App\Enums\BillingCycle;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Subscription extends Model
{
    use HasUuids;
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'status',
        'billing_cycle',
        'trial_ends_at',
        'current_period_starts_at',
        'current_period_ends_at',
        'canceled_at',
        'auto_renew',
        'payment_provider',
        'payment_provider_id',
        'metadata',
        'custom_limits',
    ];

    protected $casts = [
        'trial_ends_at'            => 'datetime',
        'current_period_starts_at' => 'datetime',
        'current_period_ends_at'   => 'datetime',
        'canceled_at'              => 'datetime',
        'auto_renew'               => 'boolean',
        'metadata'                 => 'array',
        'custom_limits'            => 'array',
        'billing_cycle'            => BillingCycle::class,
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function usageQuota(): HasOne
    {
        return $this->hasOne(UsageQuota::class);
    }

    // Status helpers
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function isPastDue(): bool
    {
        return $this->status === 'past_due';
    }

    public function isTrialing(): bool
    {
        return $this->status === 'trialing';
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at !== null && now()->lt($this->trial_ends_at);
    }

    public function trialDaysLeft()
    {
        if ( ! $this->onTrial()) {
            return null;
        }

        return now()->diffInDays($this->trial_ends_at);
    }

    public function canceledButActive(): bool
    {
        return $this->isCanceled() && now()->lt($this->current_period_ends_at);
    }

    public function daysUntilRenewal()
    {
        if ( ! $this->current_period_ends_at) {
            return null;
        }

        return now()->diffInDays($this->current_period_ends_at);
    }

    // Feature and limit methods
    public function getMaxDevices(): int
    {
        $custom = $this->custom_limits['max_devices'] ?? null;

        if ($custom !== null) {
            // Optionally cap at some max (e.g., 1000)
            return min($custom, 1000);
        }

        return $this->plan->max_devices ?? PHP_INT_MAX;
    }

    public function getMaxScreens(): int
    {
        return $this->custom_limits['max_screens'] ?? $this->plan->max_screens;
    }

    public function getMaxUsers(): int
    {
        return $this->custom_limits['max_users'] ?? $this->plan->max_users;
    }

    public function getMaxStorageMb(): int
    {
        return $this->custom_limits['max_storage_mb'] ?? $this->plan->max_storage_mb;
    }

    public function getMaxBandwidthMb(): int
    {
        return $this->custom_limits['max_bandwidth_mb'] ?? $this->plan->max_bandwidth_mb;
    }

    public function hasFeature(string $feature): bool
    {
        return $this->plan->hasFeature($feature);
    }

    // Device quota checks
    public function hasAvailableDeviceQuota(): bool
    {
        return $this->usageQuota?->devices_count < $this->getMaxDevices();
    }

    public function getDeviceQuotaRemaining(): int
    {
        $currentCount = $this->usageQuota?->devices_count ?? 0;

        return max(0, $this->getMaxDevices() - $currentCount);
    }

    public function getDeviceQuotaUsagePercentage(): float
    {
        $max = $this->getMaxDevices();

        if ($max <= 0) {
            return 100.0;
        }

        $current = $this->usageQuota?->devices_count ?? 0;

        return min(100.0, round(($current / $max) * 100, 2));
    }

    // Screen quota checks
    public function hasAvailableScreenQuota(): bool
    {
        return $this->usageQuota?->screens_count < $this->getMaxScreens();
    }

    public function getScreenQuotaRemaining(): int
    {
        $currentCount = $this->usageQuota?->screens_count ?? 0;

        return max(0, $this->getMaxScreens() - $currentCount);
    }

    public function getScreenQuotaUsagePercentage(): float
    {
        $max = $this->getMaxScreens();

        if ($max <= 0) {
            return 100.0;
        }

        $current = $this->usageQuota?->screens_count ?? 0;

        return min(100.0, round(($current / $max) * 100, 2));
    }
}

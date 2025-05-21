<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Plan extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'features',
        'max_devices',
        'max_screens',
        'max_users',
        'max_storage_mb',
        'max_bandwidth_mb',
        'is_active',
        'is_public',
        'sort_order',
    ];

    protected $casts = [
        'features'         => 'array',
        'price_monthly'    => 'decimal:2',
        'price_yearly'     => 'decimal:2',
        'max_devices'      => 'integer',
        'max_screens'      => 'integer',
        'max_users'        => 'integer',
        'max_storage_mb'   => 'integer',
        'max_bandwidth_mb' => 'integer',
        'is_active'        => 'boolean',
        'is_public'        => 'boolean',
        'sort_order'       => 'integer',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? []);
    }

    public function isActiveAndPublic(): bool
    {
        return $this->is_active && $this->is_public;
    }

    public function getMonthlyPriceAttribute(): float
    {
        return (float) $this->price_monthly;
    }

    public function getYearlyPriceAttribute(): float
    {
        return (float) $this->price_yearly;
    }

    public function getFormattedMonthlyPriceAttribute(): string
    {
        return number_format($this->price_monthly, 2);
    }

    public function getFormattedYearlyPriceAttribute(): string
    {
        return number_format($this->price_yearly, 2);
    }

    public function getYearlySavingsAttribute(): float
    {
        $monthlyCostForYear = $this->price_monthly * 12;

        return $monthlyCostForYear - $this->price_yearly;
    }

    public function getYearlySavingsPercentageAttribute(): float
    {
        $monthlyCostForYear = $this->price_monthly * 12;

        if ($monthlyCostForYear <= 0) {
            return 0;
        }

        $savings = $this->yearly_savings;

        return round(($savings / $monthlyCostForYear) * 100, 2);
    }

    /**
     * Determine if the plan has any usage limits.
     *
     * @return bool
     */
    public function hasLimits(): bool
    {
        return ! is_null($this->max_devices)
            || ! is_null($this->max_screens)
            || ! is_null($this->max_users)
            || ! is_null($this->max_storage_mb)
            || ! is_null($this->max_bandwidth_mb);
    }
}

<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class UsageQuota extends Model
{
    use HasUuids;
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'devices_count',
        'screens_count',
        'users_count',
        'storage_used_mb',
        'bandwidth_used_mb',
        'additional_quotas',
        'reset_at',
    ];

    protected $casts = [
        'devices_count'     => 'integer',
        'screens_count'     => 'integer',
        'users_count'       => 'integer',
        'storage_used_mb'   => 'integer',
        'bandwidth_used_mb' => 'integer',
        'additional_quotas' => 'array',
        'reset_at'          => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    // Device count methods
    public function incrementDeviceCount(int $count = 1): bool
    {
        $this->devices_count += $count;

        return $this->save();
    }

    public function decrementDeviceCount(int $count = 1): bool
    {
        $this->devices_count = max(0, $this->devices_count - $count);

        return $this->save();
    }

    // Screen count methods
    public function incrementScreenCount(int $count = 1): bool
    {
        $this->screens_count += $count;

        return $this->save();
    }

    public function decrementScreenCount(int $count = 1): bool
    {
        $this->screens_count = max(0, $this->screens_count - $count);

        return $this->save();
    }

    // User count methods
    public function incrementUserCount(int $count = 1): bool
    {
        $this->users_count += $count;

        return $this->save();
    }

    public function decrementUserCount(int $count = 1): bool
    {
        $this->users_count = max(0, $this->users_count - $count);

        return $this->save();
    }

    // Storage used methods
    public function addStorage(int $megabytes): bool
    {
        $this->storage_used_mb += $megabytes;

        return $this->save();
    }

    public function reduceStorage(int $megabytes): bool
    {
        $this->storage_used_mb = max(0, $this->storage_used_mb - $megabytes);

        return $this->save();
    }

    // Bandwidth used methods
    public function addBandwidth(int $megabytes): bool
    {
        $this->bandwidth_used_mb += $megabytes;

        return $this->save();
    }

    // Quota check methods
    public function isDeviceQuotaExceeded(): bool
    {
        return $this->devices_count >= $this->subscription->getMaxDevices();
    }

    public function isScreenQuotaExceeded(): bool
    {
        return $this->screens_count >= $this->subscription->getMaxScreens();
    }

    public function isUserQuotaExceeded(): bool
    {
        return $this->users_count >= $this->subscription->getMaxUsers();
    }

    public function isStorageQuotaExceeded(): bool
    {
        return $this->storage_used_mb >= $this->subscription->getMaxStorageMb();
    }

    public function isBandwidthQuotaExceeded(): bool
    {
        return $this->bandwidth_used_mb >= $this->subscription->getMaxBandwidthMb();
    }

    // Usage percentage methods
    public function getDeviceUsagePercentage(): float
    {
        $max = $this->subscription->getMaxDevices();

        if ($max <= 0) {
            return 100.0;
        }

        return min(100.0, round(($this->devices_count / $max) * 100, 2));
    }

    public function getScreenUsagePercentage(): float
    {
        $max = $this->subscription->getMaxScreens();

        if ($max <= 0) {
            return 100.0;
        }

        return min(100.0, round(($this->screens_count / $max) * 100, 2));
    }

    public function getUserUsagePercentage(): float
    {
        $max = $this->subscription->getMaxUsers();

        if ($max <= 0) {
            return 100.0;
        }

        return min(100.0, round(($this->users_count / $max) * 100, 2));
    }

    public function getStorageUsagePercentage(): float
    {
        $max = $this->subscription->getMaxStorageMb();

        if ($max <= 0) {
            return 100.0;
        }

        return min(100.0, round(($this->storage_used_mb / $max) * 100, 2));
    }

    public function getBandwidthUsagePercentage(): float
    {
        $max = $this->subscription->getMaxBandwidthMb();

        if ($max <= 0) {
            return 100.0;
        }

        return min(100.0, round(($this->bandwidth_used_mb / $max) * 100, 2));
    }
}

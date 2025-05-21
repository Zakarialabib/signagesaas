<?php

declare(strict_types=1);

namespace App\Tenant\Models\Analytics;

use App\Tenant\Models\Device;
use App\Tenant\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class BandwidthLog extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'device_id',
        'user_id',
        'resource_type',
        'resource_id',
        'bytes_transferred',
        'direction',
        'status',
        'metadata',
        'recorded_at',
    ];

    protected $casts = [
        'metadata'          => 'array',
        'bytes_transferred' => 'integer',
        'recorded_at'       => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for analytics filtering
    public function scopeForDevice(Builder $query, string $deviceId): Builder
    {
        return $query->where('device_id', $deviceId);
    }

    public function scopeForUser(Builder $query, string $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForResourceType(Builder $query, string $resourceType): Builder
    {
        return $query->where('resource_type', $resourceType);
    }

    public function scopeForDirection(Builder $query, string $direction): Builder
    {
        return $query->where('direction', $direction);
    }

    public function scopeForDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('recorded_at', [$startDate, $endDate]);
    }

    public function scopeDownload(Builder $query): Builder
    {
        return $query->where('direction', 'download');
    }

    public function scopeUpload(Builder $query): Builder
    {
        return $query->where('direction', 'upload');
    }

    // Query helpers for analytics
    public static function totalBandwidthUsed(?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::query();

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return (int) $query->sum('bytes_transferred');
    }

    public static function totalUpload(?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::upload();

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return (int) $query->sum('bytes_transferred');
    }

    public static function totalDownload(?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::download();

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return (int) $query->sum('bytes_transferred');
    }

    public static function deviceBandwidthUsed(string $deviceId, ?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::forDevice($deviceId);

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return (int) $query->sum('bytes_transferred');
    }

    public static function userBandwidthUsed(string $userId, ?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::forUser($userId);

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return (int) $query->sum('bytes_transferred');
    }
}

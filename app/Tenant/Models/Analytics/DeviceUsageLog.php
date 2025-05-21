<?php

declare(strict_types=1);

namespace App\Tenant\Models\Analytics;

use App\Tenant\Models\Device;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class DeviceUsageLog extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'device_id',
        'event_type',
        'metadata',
        'duration_seconds',
        'recorded_at',
    ];

    protected $casts = [
        'metadata'         => 'array',
        'recorded_at'      => 'datetime',
        'duration_seconds' => 'integer',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    // Scopes for analytics filtering
    public function scopeForDevice(Builder $query, string $deviceId): Builder
    {
        return $query->where('device_id', $deviceId);
    }

    public function scopeForDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('recorded_at', [$startDate, $endDate]);
    }

    public function scopeForEventType(Builder $query, string $eventType): Builder
    {
        return $query->where('event_type', $eventType);
    }

    // Query helpers for analytics
    public static function totalDeviceRuntime(string $deviceId, ?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::forDevice($deviceId);

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return (int) $query->sum('duration_seconds');
    }

    public static function deviceHeartbeatCount(string $deviceId, ?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::forDevice($deviceId)
            ->forEventType('heartbeat');

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return $query->count();
    }

    public static function deviceBootCount(string $deviceId, ?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::forDevice($deviceId)
            ->forEventType('boot');

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return $query->count();
    }
}

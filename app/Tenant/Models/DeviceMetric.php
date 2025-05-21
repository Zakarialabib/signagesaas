<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DeviceMetric extends Model
{
    protected $fillable = [
        'device_id',
        'metric_type',
        'data',
        'recorded_at',
    ];

    protected $casts = [
        'data'        => 'array',
        'recorded_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public static function recordMetric(Device $device, string $type, array $data): self
    {
        return static::create([
            'device_id'   => $device->id,
            'metric_type' => $type,
            'data'        => $data,
            'recorded_at' => now(),
        ]);
    }

    public static function getMetricsForPeriod(
        Device $device,
        string $type,
        string $startDate,
        string $endDate
    ): \Illuminate\Database\Eloquent\Collection {
        return static::query()
            ->where('device_id', $device->id)
            ->where('metric_type', $type)
            ->whereBetween('recorded_at', [$startDate, $endDate])
            ->orderBy('recorded_at')
            ->get();
    }

    public static function getLatestMetric(Device $device, string $type): ?self
    {
        return static::query()
            ->where('device_id', $device->id)
            ->where('metric_type', $type)
            ->latest('recorded_at')
            ->first();
    }

    public function getValue(string $key, $default = null)
    {
        return data_get($this->data, $key, $default);
    }
}

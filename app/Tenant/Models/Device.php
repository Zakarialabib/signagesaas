<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Enums\ScreenOrientation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Device extends Model
{
    use HasUuids;
    use SoftDeletes;
    use HasFactory;
    use BelongsToTenant;
    use HasApiTokens;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'description',
        'status',
        'hardware_id',
        'registration_code',
        'ip_address',
        'last_ping_at',
        'last_sync_at',
        'claimed_at',
        'screen_resolution',
        'orientation',
        'os_version',
        'app_version',
        'location',
        'timezone',
        'settings',
        'hardware_info',
        'system_info',
        'storage_info',
        'sync_status',
        'metadata',
    ];

    protected $casts = [
        'last_ping_at'  => 'datetime',
        'last_sync_at'  => 'datetime',
        'claimed_at'    => 'datetime',
        'settings'      => 'array',
        'location'      => 'array',
        'hardware_info' => 'array',
        'system_info'   => 'array',
        'storage_info'  => 'array',
        'metadata'      => 'array',
        'type'          => DeviceType::class,
        'status'        => DeviceStatus::class,
        'orientation'   => ScreenOrientation::class,
    ];

    protected $attributes = [
        'settings' => '{}',
        'metadata' => '{}',
    ];

    public function screens(): HasMany
    {
        return $this->hasMany(Screen::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(DeviceMetric::class);
    }

    public function getLatestMetric(string $type): ?DeviceMetric
    {
        return $this->metrics()
            ->where('metric_type', $type)
            ->latest('recorded_at')
            ->first();
    }

    public function getMetricsForPeriod(string $type, string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return $this->metrics()
            ->where('metric_type', $type)
            ->whereBetween('recorded_at', [$startDate, $endDate])
            ->orderBy('recorded_at')
            ->get();
    }

    public function isOnline(): bool
    {
        return $this->last_ping_at?->diffInMinutes(now()) < 5;
    }

    public function needsSync(): bool
    {
        return $this->last_sync_at?->diffInMinutes(now()) > 30;
    }

    public function markAsOnline(): void
    {
        $this->update([
            'status'       => DeviceStatus::ONLINE,
            'last_ping_at' => now(),
        ]);
    }

    public function markAsOffline(): void
    {
        $this->update([
            'status'       => DeviceStatus::OFFLINE,
            'last_ping_at' => now(),
        ]);
    }

    public function updateMetrics(array $metrics): void
    {
        $this->metadata = array_merge(
            $this->metadata ?? [],
            ['metrics' => array_merge(
                $this->metadata['metrics'] ?? [],
                $metrics
            )]
        );
        $this->save();
    }

    public static function generateRegistrationCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('registration_code', $code)->exists());

        return $code;
    }

    public function getMetric(string $key, $default = null)
    {
        return data_get($this->metadata, "metrics.{$key}", $default);
    }

    public function getSystemInfo(string $key, $default = null)
    {
        return data_get($this->system_info, $key, $default);
    }

    public function getHardwareInfo(string $key, $default = null)
    {
        return data_get($this->hardware_info, $key, $default);
    }

    public function getStorageInfo(string $key, $default = null)
    {
        return data_get($this->storage_info, $key, $default);
    }
}

<?php

declare(strict_types=1);

namespace App\Tenant\Models\Analytics;

use App\Tenant\Models\Content;
use App\Tenant\Models\Device;
use App\Tenant\Models\Screen;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class ContentPlayLog extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'device_id',
        'content_id',
        'screen_id',
        'duration_seconds',
        'completed',
        'metadata',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'metadata'         => 'array',
        'started_at'       => 'datetime',
        'ended_at'         => 'datetime',
        'duration_seconds' => 'integer',
        'completed'        => 'boolean',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    // Scopes for analytics filtering
    public function scopeForDevice(Builder $query, string $deviceId): Builder
    {
        return $query->where('device_id', $deviceId);
    }

    public function scopeForContent(Builder $query, string $contentId): Builder
    {
        return $query->where('content_id', $contentId);
    }

    public function scopeForScreen(Builder $query, string $screenId): Builder
    {
        return $query->where('screen_id', $screenId);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('completed', true);
    }

    public function scopeForDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('started_at', [$startDate, $endDate]);
    }

    // Query helpers for analytics
    public static function totalContentPlayTime(string $contentId, ?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::forContent($contentId);

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return (int) $query->sum('duration_seconds');
    }

    public static function contentPlayCount(string $contentId, ?string $startDate = null, ?string $endDate = null): int
    {
        $query = self::forContent($contentId);

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return $query->count();
    }

    public static function completionRate(string $contentId, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = self::forContent($contentId);

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        $total = $query->count();

        if ($total === 0) {
            return 0;
        }

        $completed = (clone $query)->completed()->count();

        return round(($completed / $total) * 100, 2);
    }
}

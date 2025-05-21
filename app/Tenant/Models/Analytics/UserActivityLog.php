<?php

declare(strict_types=1);

namespace App\Tenant\Models\Analytics;

use App\Tenant\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class UserActivityLog extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for analytics filtering
    public function scopeForUser(Builder $query, string $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }

    public function scopeForEntityType(Builder $query, string $entityType): Builder
    {
        return $query->where('entity_type', $entityType);
    }

    public function scopeForEntity(Builder $query, string $entityType, string $entityId): Builder
    {
        return $query->where('entity_type', $entityType)
            ->where('entity_id', $entityId);
    }

    public function scopeForDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Query helpers for analytics
    public static function topUsers(?string $startDate = null, ?string $endDate = null, int $limit = 10): array
    {
        $query = self::query()
            ->select('user_id', DB::raw('count(*) as activity_count'))
            ->groupBy('user_id')
            ->orderBy('activity_count', 'desc')
            ->limit($limit);

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return $query->get()->toArray();
    }

    public static function mostFrequentActions(?string $startDate = null, ?string $endDate = null, int $limit = 10): array
    {
        $query = self::query()
            ->select('action', DB::raw('count(*) as action_count'))
            ->groupBy('action')
            ->orderBy('action_count', 'desc')
            ->limit($limit);

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return $query->get()->toArray();
    }

    public static function userActivitySummary(string $userId, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = self::forUser($userId)
            ->select('action', DB::raw('count(*) as action_count'))
            ->groupBy('action')
            ->orderBy('action_count', 'desc');

        if ($startDate && $endDate) {
            $query->forDateRange($startDate, $endDate);
        }

        return $query->get()->toArray();
    }
}

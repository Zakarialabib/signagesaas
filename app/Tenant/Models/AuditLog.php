<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class AuditLog extends Model
{
    use HasUuids;
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for filtering
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

    // Helper methods
    public static function recordCreate(string $entityType, string $entityId, array $values, ?string $description = null, ?User $user = null): self
    {
        return self::create([
            'tenant_id'   => tenancy()->tenant->id,
            'user_id'     => $user?->id,
            'action'      => 'create',
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'old_values'  => null,
            'new_values'  => $values,
            'description' => $description ?? "Created {$entityType}",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    public static function recordUpdate(string $entityType, string $entityId, array $oldValues, array $newValues, ?string $description = null, ?User $user = null): self
    {
        return self::create([
            'tenant_id'   => tenancy()->tenant->id,
            'user_id'     => $user?->id,
            'action'      => 'update',
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'description' => $description ?? "Updated {$entityType}",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    public static function recordDelete(string $entityType, string $entityId, array $values, ?string $description = null, ?User $user = null): self
    {
        return self::create([
            'tenant_id'   => tenancy()->tenant->id,
            'user_id'     => $user?->id,
            'action'      => 'delete',
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'old_values'  => $values,
            'new_values'  => null,
            'description' => $description ?? "Deleted {$entityType}",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }

    public static function recordAction(string $action, string $entityType, string $entityId, ?array $oldValues = null, ?array $newValues = null, ?string $description = null, ?User $user = null): self
    {
        return self::create([
            'tenant_id'   => tenancy()->tenant->id,
            'user_id'     => $user?->id,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'description' => $description ?? "Performed {$action} on {$entityType}",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Playlist extends Model
{
    use HasUuids;
    use SoftDeletes;
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'status',
        'duration',
        'loop',
        'shuffle',
        'transition_effect',
        'transition_duration',
        'schedule_id',
        'created_by',
        'metadata',
        'settings',
    ];

    protected $casts = [
        'status'              => 'string',
        'duration'            => 'integer',
        'loop'                => 'boolean',
        'shuffle'             => 'boolean',
        'transition_duration' => 'integer',
        'metadata'            => 'array',
        'settings'            => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class)
            ->withPivot(['order', 'duration', 'transition_effect'])
            ->orderBy('order')
            ->withTimestamps();
    }

    public function getTotalDuration(): int
    {
        return $this->contents()->sum('duration') ?: $this->duration;
    }

    public function getNextContent(Content $currentContent): ?Content
    {
        $nextOrder = $this->contents()
            ->where('order', '>', $currentContent->pivot->order)
            ->min('order');

        return $nextOrder
            ? $this->contents()->where('order', $nextOrder)->first()
            : ($this->loop ? $this->contents()->orderBy('order')->first() : null);
    }

    public function reorderContents(array $contentIds): bool
    {
        $order = 1;

        foreach ($contentIds as $contentId) {
            $this->contents()->updateExistingPivot($contentId, ['order' => $order]);
            $order++;
        }

        return true;
    }

    public function getDefaultSettings(): array
    {
        return [
            'transition_effect'        => 'fade',
            'transition_duration'      => 1000,
            'default_content_duration' => 10,
            'enable_shuffle'           => false,
            'enable_loop'              => true,
            'enable_touch_navigation'  => false,
        ];
    }
}

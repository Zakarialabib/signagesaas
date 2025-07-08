<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Layout extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'template_id',
        'aspect_ratio',
        'status',
        'created_by',
        'metadata',
        'settings',
        'style_data',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'settings'   => 'array',
        'style_data' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function zones(): HasMany // Removed: Zones are now independent physical places, not parts of a layout.
    // {
    //     return $this->hasMany(Zone::class)->orderBy('order');
    // }

    public function assets(): MorphMany
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function getDefaultSettings(): array
    {
        return [
            'background_color'      => '#ffffff',
            'grid_enabled'          => true,
            'grid_size'             => 10,
            'snap_to_grid'          => true,
            'responsive_scaling'    => true,
            'maintain_aspect_ratio' => true,
        ];
    }
}

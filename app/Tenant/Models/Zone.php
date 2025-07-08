<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Zone extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        // 'layout_id', // Removed
        'name',
        'description', // Added
        'type',        // Repurposed for place type
        'x',           // Repurposed for map/floor plan
        'y',           // Repurposed for map/floor plan
        'width',       // Repurposed for map/floor plan
        'height',      // Repurposed for map/floor plan
        // 'order',    // Removed
        // 'settings', // Removed (content display settings)
        'style_data',  // For map representation
        // 'content_type', // Removed
        'metadata',    // For other place-specific structured data
    ];

    protected $casts = [
        'x'          => 'float', // Casts for map coordinates
        'y'          => 'float',
        'width'      => 'float',
        'height'     => 'float',
        // 'order'      => 'integer', // Removed
        // 'settings'   => 'array', // Removed
        'style_data' => 'array',
        'metadata'   => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // public function layout(): BelongsTo // Removed
    // {
    //     return $this->belongsTo(Layout::class);
    // }

    public function assets(): MorphMany
    {
        // Assets might still be relevant for a "place" (e.g., a photo of the location)
        return $this->morphMany(Asset::class, 'assetable');
    }

    // public function getDefaultSettings(): array // Removed
    // {
    //     // ... old settings ...
    // }

    // public function contents(): BelongsToMany // Removed
    // {
    //     // ... old relationship ...
    // }
}

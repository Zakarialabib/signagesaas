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
        'layout_id',
        'name',
        'type',
        'x',
        'y',
        'width',
        'height',
        'order',
        'settings',
        'style_data',
        'content_type',
        'metadata',
    ];

    protected $casts = [
        'x'          => 'float',
        'y'          => 'float',
        'width'      => 'float',
        'height'     => 'float',
        'order'      => 'integer',
        'settings'   => 'array',
        'style_data' => 'array',
        'metadata'   => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class);
    }

    public function assets(): MorphMany
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function getDefaultSettings(): array
    {
        return [
            'transition_effect'   => 'fade',
            'transition_duration' => 1000,
            'content_fit'         => 'contain',
            'background_color'    => 'transparent',
            'border_style'        => 'none',
            'border_width'        => '0px',
            'border_color'        => '#000000',
            'border_radius'       => '0px',
            'padding'             => '0px',
        ];
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class)
            ->using(ContentZone::class)
            ->withPivot(['order', 'duration', 'settings'])
            ->orderBy('order')
            ->withTimestamps();
    }
}

<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use App\Enums\TemplateCategory;
use App\Enums\TemplateStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Support\Facades\Storage;

final class Template extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'category',
        'status',
        'layout',
        'styles',
        'default_duration',
        'metadata',
        'settings',
        'preview_image',
        'is_system',
        'parent_id',
        'version',
        'is_variation',
    ];

    protected $casts = [
        'category'         => TemplateCategory::class,
        'status'           => TemplateStatus::class,
        'layout'           => 'array',
        'styles'           => 'array',
        'metadata'         => 'array',
        'settings'         => 'array',
        'default_duration' => 'integer',
        'is_variation'     => 'boolean',
    ];

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function assets(): MorphMany
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function variations(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->where('is_variation', true);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->where('is_variation', false);
    }

    public function latestVersion(): ?Template
    {
        return $this->versions()->orderByDesc('version')->first() ?? $this;
    }

    public function getPrevVersion(): ?Template
    {
        return $this->parent?->versions()
            ->where('version', '<', $this->version)
            ->orderByDesc('version')
            ->first();
    }

    public function getNextVersion(): ?Template
    {
        return $this->parent?->versions()
            ->where('version', '>', $this->version)
            ->orderBy('version')
            ->first();
    }

    public function getPreviewImageUrl(): ?string
    {
        return $this->preview_image
            ? Storage::disk('public')->url($this->preview_image)
            : $this->assets()->where('type', 'preview')->first()?->url;
    }

    public function getDefaultStyles(): array
    {
        return [
            'font-family'      => 'Inter, sans-serif',
            'background-color' => '#ffffff',
            'color'            => '#000000',
            'padding'          => '1rem',
            ...$this->styles ?? [],
        ];
    }

    public function getDefaultLayout(): array
    {
        return [
            'type'    => 'grid',
            'columns' => 1,
            'rows'    => 1,
            'gap'     => '1rem',
            ...$this->layout ?? [],
        ];
    }

    public function getDefaultSettings(): array
    {
        return [
            'transition'          => 'fade',
            'transition_duration' => 500,
            'refresh_interval'    => 300,
            ...$this->settings ?? [],
        ];
    }
}

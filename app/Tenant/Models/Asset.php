<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Asset extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'path',
        'disk',
        'mime_type',
        'size',
        'metadata',
        'assetable_type',
        'assetable_id',
    ];

    protected $casts = [
        'size'     => 'integer',
        'metadata' => 'array',
    ];

    public function assetable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrl(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function delete(): bool
    {
        Storage::disk($this->disk)->delete($this->path);

        return parent::delete();
    }
}

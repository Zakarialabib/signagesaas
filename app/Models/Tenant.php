<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use App\Tenant\Models\Device;
use App\Tenant\Models\Template;
use App\Tenant\Models\Screen;

// Note: App\Models\Setting is used here because the Setting model was refactored to be tenant-aware
// and is in the App\Models namespace, not App\Tenant\Models.

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDomains;
    use HasUuids;
    use HasDatabase;
    use HasFactory;
    use SoftDeletes;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'plan',
            'settings',
        ];
    }

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'settings'      => 'array',
        'data'          => 'array',
        'deleted_at'    => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'tenant_id', 'id'); // Explicit foreign and local keys for clarity
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'tenant_id', 'id');
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class, 'tenant_id', 'id');
    }

    public function screens(): HasMany
    {
        return $this->hasMany(Screen::class, 'tenant_id', 'id');
    }

    public function settingsRel(): HasMany // Renamed to avoid conflict with existing `settings` property/cast
    {
        return $this->hasMany(Setting::class, 'tenant_id', 'id');
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at?->isFuture() ?? false;
    }

    public function hasActiveSubscription(): bool
    {
        // Implementation depends on your billing integration
        return true;
    }

    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->settings = $settings;
        $this->save();
    }

    public function setSettings(array $settings): void
    {
        $this->settings = array_merge($this->settings ?? [], $settings);
        $this->save();
    }
}

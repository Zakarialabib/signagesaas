<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;
    use BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'tenant_id',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    // Relationship to Tenant
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get a setting by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        // Ensure tenant context for settings
        if (tenancy()->initialized && tenancy()->tenant) {
            $setting = static::where('key', $key)
                            // ->where('tenant_id', tenancy()->tenant->getTenantKey()) // BelongsToTenant handles this
                ->first();

            return $setting ? $setting->value : $default;
        }

        // Fallback for global settings if any (not typical with BelongsToTenant)
        // Or handle error: throw new \Exception('Tenant context not initialized for settings.');
        return $default;
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        if (tenancy()->initialized && tenancy()->tenant) {
            static::updateOrCreate(
                [
                    'key' => $key,
                    // 'tenant_id' => tenancy()->tenant->getTenantKey() // BelongsToTenant handles this
                ],
                ['value' => $value]
            );
            // Cache::forget('settings.' . tenancy()->tenant->getTenantKey()); // Example cache clearing
        } else {
            // Handle error or global setting logic
            // throw new \Exception('Tenant context not initialized for settings.');
        }
    }

    /**
     * Set multiple settings at once.
     *
     * @param array $settings
     * @return void
     */
    public static function setMany(array $settings): void
    {
        if (tenancy()->initialized && tenancy()->tenant) {
            foreach ($settings as $key => $value) {
                static::set($key, $value); // set() already handles tenant context
            }
        } else {
            // Handle error or global setting logic
        }
    }

    /**
     * Check if a setting exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        if (tenancy()->initialized && tenancy()->tenant) {
            return static::where('key', $key)
                        // ->where('tenant_id', tenancy()->tenant->getTenantKey()) // BelongsToTenant handles this
                ->exists();
        }

        return false;
    }
}

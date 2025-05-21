<?php

declare(strict_types=1);

namespace App\Support;

use App\Tenant\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Tenancy;
use Exception;

final class SettingsHelper
{
    /** The cache prefix for tenant settings. */
    private const CACHE_PREFIX = 'tenant:settings:';

    /**
     * Get all settings for the current tenant.
     *
     * @return array
     */
    public function getAll(): array
    {
        $tenant = $this->getCurrentTenant();

        if ( ! $tenant) {
            return [];
        }

        return $this->getCachedSettings($tenant);
    }

    /**
     * Get a specific setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $tenant = $this->getCurrentTenant();

        if ( ! $tenant) {
            return $default;
        }

        $settings = $this->getCachedSettings($tenant);

        return $settings[$key] ?? $default;
    }

    /**
     * Check if a setting exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $tenant = $this->getCurrentTenant();

        if ( ! $tenant) {
            return false;
        }

        $settings = $this->getCachedSettings($tenant);

        return array_key_exists($key, $settings);
    }

    /**
     * Update multiple settings at once.
     *
     * @param array $settings
     * @return bool
     */
    public function update(array $settings): bool
    {
        $tenant = $this->getCurrentTenant();

        if ( ! $tenant) {
            return false;
        }

        $currentSettings = $tenant->settings ?? [];
        $updatedSettings = array_merge($currentSettings, $settings);

        try {
            $tenant->settings = $updatedSettings;
            $tenant->save();

            // Update cache
            $this->clearCache($tenant);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to update tenant settings', [
                'tenant_id' => $tenant->id,
                'error'     => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Clear settings cache for a tenant.
     *
     * @param Tenant $tenant
     * @return void
     */
    public function clearCache(Tenant $tenant): void
    {
        Cache::forget(self::CACHE_PREFIX.$tenant->id);
    }

    /**
     * Get cached settings for a tenant.
     *
     * @param Tenant $tenant
     * @return array
     */
    private function getCachedSettings(Tenant $tenant): array
    {
        $cacheKey = self::CACHE_PREFIX.$tenant->id;

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($tenant) {
            return $tenant->settings ?? [];
        });
    }

    /**
     * Get the current tenant.
     *
     * @return Tenant|null
     */
    private function getCurrentTenant(): ?Tenant
    {
        // First try to get tenant from tenancy context
        $tenant = tenant();

        // If tenant couldn't be resolved from tenancy context, try to get from user
        if ( ! $tenant) {
            $user = Auth::user();
            $tenant = $user?->tenant;
        }

        return $tenant;
    }
}

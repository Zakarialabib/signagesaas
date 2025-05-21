<?php

declare(strict_types=1);

namespace App\Services;

use App\Tenant\Models\Setting;
use Illuminate\Support\Facades\Cache;

final class SettingsService
{
    /** Cache key for settings. */
    private const CACHE_KEY = 'app_settings';

    /** Cache lifetime in seconds (24 hours). */
    private const CACHE_TTL = 86400;

    /**
     * Get a setting by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->getAll();

        return $settings[$key] ?? $default;
    }

    /**
     * Get all settings.
     *
     * @return array
     */
    public function getAll(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $settings = Setting::all()->pluck('value', 'key')->toArray();

            return $settings;
        });
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        Setting::set($key, $value);

        // Invalidate cache
        $this->clearCache();
    }

    /**
     * Set multiple settings at once.
     *
     * @param array $settings
     * @return void
     */
    public function setMany(array $settings): void
    {
        Setting::setMany($settings);

        // Invalidate cache
        $this->clearCache();
    }

    /**
     * Check if a setting exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $settings = $this->getAll();

        return isset($settings[$key]);
    }

    /**
     * Clear settings cache.
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}

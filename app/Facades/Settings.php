<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getAll()
 * @method static mixed get(string $key, mixed $default = null)
 * @method static bool has(string $key)
 * @method static bool update(array $settings)
 * @method static void clearCache(\App\Tenant\Models\Tenant $tenant)
 *
 * @see \App\Support\SettingsHelper
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }
}

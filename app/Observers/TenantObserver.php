<?php

declare(strict_types=1);

namespace App\Observers;

use App\Tenant\Models\Tenant;

class TenantObserver
{
    /** Handle the Tenant "creating" event. */
    public function creating(Tenant $tenant): void
    {
        // Ensure settings has at least default values
        if (empty($tenant->settings)) {
            $tenant->settings = $this->getDefaultSettings();
        } else {
            // Merge with defaults, keeping existing values
            $tenant->settings = array_merge(
                $this->getDefaultSettings(),
                $tenant->settings ?? []
            );
        }
    }

    /** Get default tenant settings. */
    private function getDefaultSettings(): array
    {
        return [
            // General settings
            'siteName'     => config('app.name', 'SignageSaaS'),
            'contactEmail' => config('mail.from.address', 'hello@signagesaas.com'),
            'timezone'     => config('app.timezone', 'UTC'),
            'dateFormat'   => 'Y-m-d',
            'timeFormat'   => 'H:i',

            // Appearance settings
            'locale'          => 'en',
            'primaryColor'    => '#4f46e5',
            'secondaryColor'  => '#9ca3af',
            'darkModeDefault' => false,

            // Content settings
            'defaultScreenDuration' => 15,
            'defaultTransition'     => 'fade',

            // Notification settings
            'notificationsEnabled'       => true,
            'deviceOfflineAlerts'        => true,
            'contentUpdateNotifications' => true,
            'securityAlerts'             => true,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Tenant\Models\Tenant;
use Illuminate\Console\Command;

class UpdateTenantSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:update-settings {--tenant=* : The ID of the tenant to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tenant settings with default values';

    /** Execute the console command. */
    public function handle(): int
    {
        $tenantIds = $this->option('tenant');

        // Get tenants to update
        $query = Tenant::query();

        if ( ! empty($tenantIds)) {
            $query->whereIn('id', $tenantIds);
        }

        $tenants = $query->get();

        if ($tenants->isEmpty()) {
            $this->error('No tenants found to update.');

            return 1;
        }

        $defaultSettings = $this->getDefaultSettings();
        $count = 0;

        foreach ($tenants as $tenant) {
            $this->info("Updating settings for tenant {$tenant->id}...");

            // Get current settings or empty array
            $currentSettings = $tenant->settings ?? [];

            // Convert old setting keys if they exist
            $this->convertOldSettingKeys($currentSettings);

            // Merge with defaults (current settings take precedence)
            $updatedSettings = array_merge(
                $defaultSettings,
                $currentSettings
            );

            // Update the tenant
            $tenant->settings = $updatedSettings;
            $tenant->save();

            $count++;
        }

        $this->info("Successfully updated settings for {$count} tenant(s).");

        return 0;
    }

    /**
     * Convert old setting keys to new format if they exist.
     *
     * @param array &$settings
     * @return void
     */
    private function convertOldSettingKeys(array &$settings): void
    {
        $keyMappings = [
            'site_name'               => 'siteName',
            'contact_email'           => 'contactEmail',
            'default_screen_duration' => 'defaultScreenDuration',
            'notifications_enabled'   => 'notificationsEnabled',
            'company_logo'            => 'companyLogo',
            'primary_color'           => 'primaryColor',
            'secondary_color'         => 'secondaryColor',
            'notification_email'      => 'notificationEmail',
        ];

        foreach ($keyMappings as $oldKey => $newKey) {
            if (isset($settings[$oldKey]) && ! isset($settings[$newKey])) {
                $settings[$newKey] = $settings[$oldKey];
                unset($settings[$oldKey]);
            }
        }
    }

    /**
     * Get default tenant settings.
     *
     * @return array
     */
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

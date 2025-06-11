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

    /** Handle the Tenant "deleted" event. */
    public function deleted(Tenant $tenant): void
    {
        // Clean up associated data when a tenant is deleted
        // This includes domains, users, content, and storage

        // Delete tenant domains
        $tenant->domains()->delete();

        // Delete tenant-specific users (if not handled by foreign keys with cascade delete)
        // User::where('tenant_id', $tenant->id)->delete(); // Example if users are tenant-scoped

        // Clean up tenant-specific storage
        \Illuminate\Support\Facades\Storage::disk('tenant_media')->deleteDirectory($tenant->id);

        // You might also want to delete tenant-specific database records
        // For example, if you have tenant-scoped models that don't cascade delete automatically
        // tenancy()->initialize($tenant);
        // \App\Tenant\Models\Content::query()->delete();
        // \App\Tenant\Models\Device::query()->delete();
        // etc.
        // tenancy()->end();
    }

    /** Handle the Tenant "updated" event. */
    public function updated(Tenant $tenant): void
    {
        // Potentially handle changes to tenant plans or status that might trigger specific actions
        // For example, adjusting resource limits or sending notifications
        if ($tenant->isDirty('plan')) {
            // Log or trigger action for plan change
            // \App\Actions\HandleTenantPlanChange::dispatch($tenant);
        }

        if ($tenant->isDirty('status')) {
            // Log or trigger action for status change (e.g., suspended, active)
            // \App\Actions\HandleTenantStatusChange::dispatch($tenant);
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

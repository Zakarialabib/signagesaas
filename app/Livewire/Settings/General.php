<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Tenant\Models\Tenant;
use App\Services\SettingsService;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
#[Title('General Settings')]
final class General extends Component
{
    // General settings
    public string $siteName = '';
    public string $contactEmail = '';
    public string $timezone = '';
    public string $dateFormat = '';
    public string $timeFormat = '';
    public bool $enableNotifications = true;

    // Tenant-specific settings
    public string $language = 'en';
    public int $defaultScreenDuration = 15;
    public bool $notificationsEnabled = true;
    public string $companyLogo = '';
    public string $primaryColor = '#4f46e5';
    public string $secondaryColor = '#9ca3af';
    public string $tenantSubdomain = '';
    public string $notificationEmail = '';

    // Tab management
    public string $activeTab = 'general'; // Default tab

    private SettingsService $settingsService;

    public function boot(SettingsService $settingsService): void
    {
        $this->settingsService = $settingsService;
    }

    public function mount(): void
    {
        // Load general settings from database, use config values as fallback
        $this->siteName = $this->settingsService->get('site_name', config('app.name', 'SignageSaaS'));
        $this->contactEmail = $this->settingsService->get('contact_email', config('mail.from.address', 'admin@example.com'));
        $this->timezone = $this->settingsService->get('timezone', config('app.timezone', 'UTC'));
        $this->dateFormat = $this->settingsService->get('date_format', 'Y-m-d');
        $this->timeFormat = $this->settingsService->get('time_format', 'H:i');
        $this->enableNotifications = (bool) $this->settingsService->get('enable_notifications', true);

        // Load tenant-specific settings
        $user = Auth::user();
        $tenant = $user?->tenant;

        if ($tenant instanceof Tenant) {
            $tenantSettings = $tenant->settings ?? [];
            $this->language = $tenantSettings['language'] ?? 'en';
            $this->timezone = $tenantSettings['timezone'] ?? $this->timezone;
            $this->defaultScreenDuration = $tenantSettings['default_screen_duration'] ?? 15;
            $this->notificationsEnabled = $tenantSettings['notifications_enabled'] ?? true;
            $this->companyLogo = $tenantSettings['company_logo'] ?? '';
            $this->primaryColor = $tenantSettings['primary_color'] ?? '#4f46e5';
            $this->secondaryColor = $tenantSettings['secondary_color'] ?? '#9ca3af';
            $this->tenantSubdomain = $tenantSettings['tenant_subdomain'] ?? '';
            $this->notificationEmail = $tenantSettings['notification_email'] ?? $this->contactEmail;
        }
    }

    public function saveSettings(): void
    {
        $this->validate([
            'siteName'              => 'required|string|max:100',
            'contactEmail'          => 'required|email',
            'timezone'              => 'required|string',
            'dateFormat'            => 'required|string',
            'timeFormat'            => 'required|string',
            'language'              => 'required|string|in:en,ar',
            'defaultScreenDuration' => 'required|integer|min:10|max:300',
            'primaryColor'          => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'secondaryColor'        => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'tenantSubdomain'       => 'nullable|string|max:100|alpha_dash|unique:tenants,settings->tenant_subdomain',
            'notificationEmail'     => 'nullable|email',
        ]);

        // Log settings change
        activity('settings')
            ->causedBy(Auth::user())
            ->withProperties([
                'old_values' => $this->getOriginalSettings(),
                'new_values' => $this->getCurrentSettings(),
            ])
            ->log('General settings updated');

        // Save general settings to database
        $this->settingsService->setMany([
            'site_name'            => $this->siteName,
            'contact_email'        => $this->contactEmail,
            'timezone'             => $this->timezone,
            'date_format'          => $this->dateFormat,
            'time_format'          => $this->timeFormat,
            'enable_notifications' => $this->enableNotifications,
        ]);

        // Save tenant-specific settings
        $user = Auth::user();
        $tenant = $user?->tenant;

        if ($tenant instanceof Tenant) {
            $tenantSettings = $tenant->settings ?? [];

            $tenantSettings['language'] = $this->language;
            $tenantSettings['timezone'] = $this->timezone;
            $tenantSettings['default_screen_duration'] = $this->defaultScreenDuration;
            $tenantSettings['notifications_enabled'] = $this->notificationsEnabled;
            $tenantSettings['company_logo'] = $this->companyLogo;
            $tenantSettings['primary_color'] = $this->primaryColor;
            $tenantSettings['secondary_color'] = $this->secondaryColor;
            $tenantSettings['tenant_subdomain'] = $this->tenantSubdomain;
            $tenantSettings['notification_email'] = $this->notificationEmail;

            $tenant->settings = $tenantSettings;
            $tenant->save();
        }

        // Update application configuration if needed
        config(['app.name' => $this->siteName]);
        config(['app.timezone' => $this->timezone]);
        config(['app.locale' => $this->language]);

        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => 'Settings saved successfully!',
        ]);
    }

    public function setActiveTab(string $tabName): void
    {
        $this->activeTab = $tabName;
    }

    public function render()
    {
        return view('livewire.settings.general', [
            'timezones'   => $this->getTimezones(),
            'dateFormats' => $this->getDateFormats(),
            'timeFormats' => $this->getTimeFormats(),
            'languages'   => $this->getLanguages(),
        ]);
    }

    private function getTimezones(): array
    {
        // Should match the Blade dropdown
        return [
            'UTC'                 => 'UTC',
            'America/New_York'    => 'Eastern Time (US & Canada)',
            'America/Chicago'     => 'Central Time (US & Canada)',
            'America/Denver'      => 'Mountain Time (US & Canada)',
            'America/Los_Angeles' => 'Pacific Time (US & Canada)',
            'Europe/London'       => 'London',
            'Europe/Paris'        => 'Paris',
            'Asia/Tokyo'          => 'Tokyo',
            'Asia/Dubai'          => 'Dubai',
        ];
    }

    private function getDateFormats(): array
    {
        return [
            'Y-m-d'  => date('Y-m-d').' (ISO)',
            'd/m/Y'  => date('d/m/Y').' (EU)',
            'm/d/Y'  => date('m/d/Y').' (US)',
            'F j, Y' => date('F j, Y').' (Full)',
        ];
    }

    private function getTimeFormats(): array
    {
        return [
            'H:i'   => date('H:i').' (24-hour)',
            'h:i A' => date('h:i A').' (12-hour)',
        ];
    }

    private function getOriginalSettings(): array
    {
        return [
            'site_name'            => $this->settingsService->get('site_name'),
            'contact_email'        => $this->settingsService->get('contact_email'),
            'timezone'             => $this->settingsService->get('timezone'),
            'date_format'          => $this->settingsService->get('date_format'),
            'time_format'          => $this->settingsService->get('time_format'),
            'enable_notifications' => $this->settingsService->get('enable_notifications'),
        ];
    }

    private function getCurrentSettings(): array
    {
        return [
            'site_name'            => $this->siteName,
            'contact_email'        => $this->contactEmail,
            'timezone'             => $this->timezone,
            'date_format'          => $this->dateFormat,
            'time_format'          => $this->timeFormat,
            'enable_notifications' => $this->enableNotifications,
        ];
    }

    private function getLanguages(): array
    {
        return [
            'en' => 'English',
            'ar' => 'Arabic',
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.super-admin')]
final class GlobalSettingsManager extends Component
{
    // General Settings
    #[Validate('required|string|max:255')]
    public string $site_name = '';

    #[Validate('required|email|max:255')]
    public string $admin_email = '';

    #[Validate('nullable|url|max:255')]
    public ?string $support_url = null;

    #[Validate('required|boolean')]
    public bool $enable_new_registrations = true;

    // Tenant Settings
    #[Validate('required|boolean')]
    public bool $enable_trial_period = true;

    #[Validate('required|integer|min:0|max:365')]
    public int $trial_days = 14;

    #[Validate('required|boolean')]
    public bool $require_payment_for_trial = false;

    #[Validate('required|string|in:subdomain,domain,path')]
    public string $default_tenant_mode = 'subdomain';

    // Email Settings
    #[Validate('required|string|in:smtp,mailgun,ses,postmark,sendmail')]
    public string $mail_driver = 'smtp';

    #[Validate('required|string|max:255')]
    public string $mail_from_address = '';

    #[Validate('required|string|max:255')]
    public string $mail_from_name = '';

    // API Settings
    #[Validate('required|boolean')]
    public bool $enable_api = true;

    #[Validate('nullable|integer|min:1|max:1440')]
    public ?int $api_token_expiry_minutes = 60;

    #[Validate('required|integer|min:1|max:1000')]
    public int $api_rate_limit = 60;

    // Integration Settings
    #[Validate('nullable|string|max:255')]
    public ?string $google_analytics_id = null;

    // Success message
    public bool $showSuccessAlert = false;

    // Active settings tab
    public string $activeTab = 'general';

    public function mount(): void
    {
        // $this->authorize('viewAny', Setting::class);
        $this->loadSettings();
    }

    public function render()
    {
        return view('livewire.super-admin.global-settings-manager');
    }

    private function loadSettings(): void
    {
        // General Settings
        $this->site_name = $this->getSetting('site_name', config('app.name', 'Signage SaaS'));
        $this->admin_email = $this->getSetting('admin_email', 'admin@example.com');
        $this->support_url = $this->getSetting('support_url');
        $this->enable_new_registrations = (bool) $this->getSetting('enable_new_registrations', true);

        // Tenant Settings
        $this->enable_trial_period = (bool) $this->getSetting('enable_trial_period', true);
        $this->trial_days = (int) $this->getSetting('trial_days', 14);
        $this->require_payment_for_trial = (bool) $this->getSetting('require_payment_for_trial', false);
        $this->default_tenant_mode = $this->getSetting('default_tenant_mode', 'subdomain');

        // Email Settings
        $this->mail_driver = $this->getSetting('mail_driver', config('mail.default', 'smtp'));
        $this->mail_from_address = $this->getSetting('mail_from_address', config('mail.from.address', 'hello@example.com'));
        $this->mail_from_name = $this->getSetting('mail_from_name', config('mail.from.name', 'Signage SaaS'));

        // API Settings
        $this->enable_api = (bool) $this->getSetting('enable_api', true);
        $this->api_token_expiry_minutes = (int) $this->getSetting('api_token_expiry_minutes', 60);
        $this->api_rate_limit = (int) $this->getSetting('api_rate_limit', 60);

        // Integration Settings
        $this->google_analytics_id = $this->getSetting('google_analytics_id');
    }

    private function getSetting(string $key, $default = null)
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }

    public function saveGeneralSettings(): void
    {
        $this->validate([
            'site_name'                => 'required|string|max:255',
            'admin_email'              => 'required|email|max:255',
            'support_url'              => 'nullable|url|max:255',
            'enable_new_registrations' => 'required|boolean',
        ]);

        $this->updateSetting('site_name', $this->site_name);
        $this->updateSetting('admin_email', $this->admin_email);
        $this->updateSetting('support_url', $this->support_url);
        $this->updateSetting('enable_new_registrations', $this->enable_new_registrations);

        $this->showSuccessAlert = true;
    }

    public function saveTenantSettings(): void
    {
        $this->validate([
            'enable_trial_period'       => 'required|boolean',
            'trial_days'                => 'required|integer|min:0|max:365',
            'require_payment_for_trial' => 'required|boolean',
            'default_tenant_mode'       => 'required|string|in:subdomain,domain,path',
        ]);

        $this->updateSetting('enable_trial_period', $this->enable_trial_period);
        $this->updateSetting('trial_days', $this->trial_days);
        $this->updateSetting('require_payment_for_trial', $this->require_payment_for_trial);
        $this->updateSetting('default_tenant_mode', $this->default_tenant_mode);

        $this->showSuccessAlert = true;
    }

    public function saveEmailSettings(): void
    {
        $this->validate([
            'mail_driver'       => 'required|string|in:smtp,mailgun,ses,postmark,sendmail',
            'mail_from_address' => 'required|string|max:255',
            'mail_from_name'    => 'required|string|max:255',
        ]);

        $this->updateSetting('mail_driver', $this->mail_driver);
        $this->updateSetting('mail_from_address', $this->mail_from_address);
        $this->updateSetting('mail_from_name', $this->mail_from_name);

        $this->showSuccessAlert = true;
    }

    public function saveApiSettings(): void
    {
        $this->validate([
            'enable_api'               => 'required|boolean',
            'api_token_expiry_minutes' => 'nullable|integer|min:1|max:1440',
            'api_rate_limit'           => 'required|integer|min:1|max:1000',
        ]);

        $this->updateSetting('enable_api', $this->enable_api);
        $this->updateSetting('api_token_expiry_minutes', $this->api_token_expiry_minutes);
        $this->updateSetting('api_rate_limit', $this->api_rate_limit);

        $this->showSuccessAlert = true;
    }

    public function saveIntegrationSettings(): void
    {
        $this->validate([
            'google_analytics_id' => 'nullable|string|max:255',
        ]);

        $this->updateSetting('google_analytics_id', $this->google_analytics_id);

        $this->showSuccessAlert = true;
    }

    private function updateSetting(string $key, $value): void
    {
        $this->authorize('update', Setting::class);

        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear cache for this setting
        Cache::forget("setting:{$key}");
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function hideSuccessAlert(): void
    {
        $this->showSuccessAlert = false;
    }

    public function resetSettings(): void
    {
        if (method_exists($this, "reset{$this->activeTab}Settings")) {
            $this->{"reset{$this->activeTab}Settings"}();
        }
    }

    private function resetGeneralSettings(): void
    {
        $this->site_name = config('app.name', 'Signage SaaS');
        $this->admin_email = 'admin@example.com';
        $this->support_url = null;
        $this->enable_new_registrations = true;
    }

    private function resetTenantSettings(): void
    {
        $this->enable_trial_period = true;
        $this->trial_days = 14;
        $this->require_payment_for_trial = false;
        $this->default_tenant_mode = 'subdomain';
    }

    private function resetEmailSettings(): void
    {
        $this->mail_driver = config('mail.default', 'smtp');
        $this->mail_from_address = config('mail.from.address', 'hello@example.com');
        $this->mail_from_name = config('mail.from.name', 'Signage SaaS');
    }

    private function resetApiSettings(): void
    {
        $this->enable_api = true;
        $this->api_token_expiry_minutes = 60;
        $this->api_rate_limit = 60;
    }

    private function resetIntegrationSettings(): void
    {
        $this->google_analytics_id = null;
    }
}

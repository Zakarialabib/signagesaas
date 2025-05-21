<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Facades\Settings;
use App\Tenant\Models\Tenant;
use App\Traits\HasPermissions;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Settings')]
class TenantSettingsManager extends Component
{
    use HasPermissions;

    /** The active settings tab. */
    public string $activeTab = 'general';

    /** Site name setting. */
    public ?string $siteName = null;

    /** Contact email setting. */
    public ?string $contactEmail = null;

    /** Timezone setting. */
    public ?string $timezone = null;

    /** Date format setting. */
    public ?string $dateFormat = null;

    /** Time format setting. */
    public ?string $timeFormat = null;

    /** Show success alert flag. */
    public bool $showSuccessAlert = false;

    /** Alert message to display. */
    public ?string $alertMessage = null;

    /** Whether the user can edit settings. */
    public bool $canEdit = false;

    /** Initialize the component. */
    public function mount(): void
    {
        // Check permissions
        $this->canEdit = Auth::check() && (
            Auth::user()->hasRole('admin') ||
            Auth::user()->hasPermissionTo('settings.edit')
        );

        $this->loadSettings();
    }

    /** Load settings from the database. */
    private function loadSettings(): void
    {
        $this->siteName = Settings::get('siteName', config('app.name'));
        $this->contactEmail = Settings::get('contactEmail', Auth::user()?->email);
        $this->timezone = Settings::get('timezone', config('app.timezone'));
        $this->dateFormat = Settings::get('dateFormat', 'Y-m-d');
        $this->timeFormat = Settings::get('timeFormat', 'H:i');
    }

    /** Save tenant settings. */
    public function saveSettings(): void
    {
        // Check if user can edit settings
        if ( ! $this->canEdit) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'error',
                'message' => __('You do not have permission to update settings.'),
            ]);

            return;
        }

        // Validate settings
        $this->validate([
            'siteName'     => 'required|string|max:100',
            'contactEmail' => 'required|email',
            'timezone'     => 'required|string|max:100',
            'dateFormat'   => 'required|string|max:20',
            'timeFormat'   => 'required|string|max:20',
        ]);

        // Update settings
        $settings = [
            'siteName'     => $this->siteName,
            'contactEmail' => $this->contactEmail,
            'timezone'     => $this->timezone,
            'dateFormat'   => $this->dateFormat,
            'timeFormat'   => $this->timeFormat,
        ];

        $success = Settings::update($settings);

        if ($success) {
            $this->showSuccessAlert = true;
            $this->alertMessage = __('Settings saved successfully!');

            // Hide alert after 5 seconds
            $this->dispatch('setTimeout', ['callback' => '$set(\'showSuccessAlert\', false)', 'milliseconds' => 5000]);
        } else {
            // Handle error
            session()->flash('error', __('Failed to save settings. Please try again.'));
        }
    }

    /** Set the active tab. */
    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /** Get available timezones. */
    public function getTimezones(): array
    {
        $timezones = [
            'UTC'                 => 'UTC',
            'America/New_York'    => 'Eastern Time (US & Canada)',
            'America/Chicago'     => 'Central Time (US & Canada)',
            'America/Denver'      => 'Mountain Time (US & Canada)',
            'America/Los_Angeles' => 'Pacific Time (US & Canada)',
            'Europe/London'       => 'London',
            'Europe/Paris'        => 'Paris',
            'Europe/Berlin'       => 'Berlin',
            'Asia/Tokyo'          => 'Tokyo',
            'Asia/Dubai'          => 'Dubai',
            'Australia/Sydney'    => 'Sydney',
        ];

        return $timezones;
    }

    /** Get available date formats. */
    public function getDateFormats(): array
    {
        return [
            'Y-m-d'  => date('Y-m-d').' (YYYY-MM-DD)',
            'm/d/Y'  => date('m/d/Y').' (MM/DD/YYYY)',
            'd/m/Y'  => date('d/m/Y').' (DD/MM/YYYY)',
            'M j, Y' => date('M j, Y').' (Jan 1, 2023)',
            'j F Y'  => date('j F Y').' (1 January 2023)',
        ];
    }

    /** Get available time formats. */
    public function getTimeFormats(): array
    {
        return [
            'H:i'   => date('H:i').' (24-hour)',
            'h:i A' => date('h:i A').' (12-hour with AM/PM)',
        ];
    }

    /** Render the component. */
    public function render()
    {
        return view('livewire.settings.tenant-settings-manager');
    }
}

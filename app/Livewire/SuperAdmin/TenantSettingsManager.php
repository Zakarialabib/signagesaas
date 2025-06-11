<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Tenant Settings Manager')]
class TenantSettingsManager extends Component
{
    use WithPagination;

    public $tenantId;
    public $tenant;
    public $settings = [];

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $rules = [
        'settings.siteName' => 'required|string|max:100',
        'settings.contactEmail' => 'required|email',
        'settings.timezone' => 'required|string|max:100',
        'settings.dateFormat' => 'required|string|max:20',
        'settings.timeFormat' => 'required|string|max:20',
    ];

    public function mount($tenantId = null)
    {
        if ($tenantId) {
            $this->tenantId = $tenantId;
            $this->loadTenant();
        }
    }

    public function loadTenant()
    {
        $this->tenant = Tenant::findOrFail($this->tenantId);
        $this->settings = $this->tenant->settings ?? [];
    }

    public function saveSettings()
    {
        $this->validate();

        $this->tenant->settings = array_merge($this->tenant->settings ?? [], $this->settings);
        $this->tenant->save();

        session()->flash('message', 'Tenant settings updated successfully.');
    }

    public function render()
    {
        $tenants = Tenant::search($this->search)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.super-admin.tenant-settings-manager', [
            'tenants' => $tenants,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function selectTenant($tenantId)
    {
        $this->tenantId = $tenantId;
        $this->loadTenant();
    }

    public function resetTenantSelection()
    {
        $this->tenantId = null;
        $this->tenant = null;
        $this->settings = [];
    }

    public function getTimezones(): array
    {
        return [
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
    }

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

    public function getTimeFormats(): array
    {
        return [
            'H:i'   => date('H:i').' (24-hour)',
            'h:i A' => date('h:i A').' (12-hour with AM/PM)',
        ];
    }
}

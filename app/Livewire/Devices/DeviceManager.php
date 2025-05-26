<?php

declare(strict_types=1);

namespace App\Livewire\Devices;

use App\Services\OnboardingProgressService;
use App\Tenant\Models\AuditLog;
use App\Tenant\Models\Device;
use App\Tenant\Models\OnboardingProgress;
use App\Tenant\Models\Subscription;
use App\Tenant\Models\UsageQuota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use Exception;

#[Layout('layouts.app')]
final class DeviceManager extends Component
{
    use WithPagination;

    // Device list properties
    public int $perPage = 10;
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public ?string $statusFilter = null;
    public ?string $typeFilter = null;

    // Device form properties
    public bool $showDeviceModal = false;
    public bool $deleteDeviceModal = false;
    public string $formMode = 'create'; // 'create', 'edit'
    public ?string $deviceId = null;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|in:android,windows,raspberry-pi,other')]
    public string $type = 'android';

    #[Rule('nullable|string|max:255')]
    public ?string $hardwareId = null;

    #[Rule('nullable|string|max:255')]
    public ?string $location = null;

    #[Rule('nullable|string|max:255')]
    public ?string $timezone = null;

    #[Rule('nullable|string|max:255')]
    public ?string $orientation = 'landscape';

    // Quota management
    public ?Subscription $subscription = null;
    public ?UsageQuota $usageQuota = null;
    public bool $showQuotaModal = false;

    // Upgrade prompt
    public bool $showUpgradeModal = false;

    public function mount(): void
    {
        // $this->authorize('viewAny', Auth::user());

        // Load subscription and quota data
        $this->subscription = Subscription::where('tenant_id', tenant('id'))
            ->with('plan')
            ->with('usageQuota')
            ->first();

        $this->usageQuota = $this->subscription?->usageQuota;
    }

    // Device CRUD methods
    public function createDevice(): void
    {
        // Check if we're at the quota limit
        if ($this->isAtDeviceQuotaLimit()) {
            $this->showUpgradeModal = true;

            return;
        }

        $this->resetDeviceForm();
        $this->formMode = 'create';
        $this->showDeviceModal = true;
    }

    public function editDevice(string $deviceId): void
    {
        $device = Device::find($deviceId);

        if ( ! $device) {
            session()->flash('error', 'Device not found.');

            return;
        }

        $this->formMode = 'edit';
        $this->deviceId = $device->id;
        $this->name = $device->name;
        $this->type = $device->type;
        $this->hardwareId = $device->hardware_id;
        $this->location = $device->location;
        $this->timezone = $device->timezone;
        $this->orientation = $device->orientation ?? 'landscape';
        $this->showDeviceModal = true;
    }

    public function saveDevice(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
            if ($this->formMode === 'create') {
                // Check quota one more time before creating
                if ($this->isAtDeviceQuotaLimit()) {
                    session()->flash('error', 'You have reached your device quota limit. Please upgrade your plan to add more devices.');
                    $this->showUpgradeModal = true;
                    DB::rollBack();

                    return;
                }

                // Create new device
                $device = Device::create([
                    'tenant_id'   => tenant('id'),
                    'name'        => $this->name,
                    'type'        => $this->type,
                    'hardware_id' => $this->hardwareId,
                    'location'    => $this->location,
                    'timezone'    => $this->timezone,
                    'orientation' => $this->orientation,
                    'status'      => 'inactive', // Default status
                    'token'       => Str::random(64), // Generate a token for device authentication
                ]);

                // Increment usage quota
                if ($this->usageQuota) {
                    $this->usageQuota->incrementDeviceCount();
                }

                // Log creation
                AuditLog::recordCreate('device', $device->id, [
                    'name' => $device->name,
                    'type' => $device->type,
                ]);

                // Mark onboarding step as complete
                $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => tenant('id')]);

                if ( ! $onboardingProgress->first_device_registered) {
                    app(OnboardingProgressService::class)->completeStep($onboardingProgress, 'first_device_registered');
                }

                session()->flash('message', 'Device created successfully.');
            } else {
                // Update existing device
                $device = Device::find($this->deviceId);

                if ( ! $device) {
                    session()->flash('error', 'Device not found.');
                    DB::rollBack();
                    $this->closeModal();

                    return;
                }

                $oldData = [
                    'name'        => $device->name,
                    'type'        => $device->type,
                    'hardware_id' => $device->hardware_id,
                    'location'    => $device->location,
                    'timezone'    => $device->timezone,
                    'orientation' => $device->orientation,
                ];

                $device->update([
                    'name'        => $this->name,
                    'type'        => $this->type,
                    'hardware_id' => $this->hardwareId,
                    'location'    => $this->location,
                    'timezone'    => $this->timezone,
                    'orientation' => $this->orientation,
                ]);

                // Log update
                AuditLog::recordUpdate('device', $device->id, $oldData, [
                    'name'        => $device->name,
                    'type'        => $device->type,
                    'hardware_id' => $device->hardware_id,
                    'location'    => $device->location,
                    'timezone'    => $device->timezone,
                    'orientation' => $device->orientation,
                ]);

                session()->flash('message', 'Device updated successfully.');
            }

            DB::commit();
            $this->closeModal();
        } catch (Exception $e) {
            DB::rollBack();
            logger()->error('Error saving device: '.$e->getMessage());
            session()->flash('error', 'An error occurred while saving the device. Please try again.');
        }
    }

    public function deleteDevice(string $deviceId): void
    {
        $this->deleteDeviceModal = true;

        $device = Device::find($deviceId);

        if ( ! $device) {
            session()->flash('error', 'Device not found.');

            return;
        }

        DB::beginTransaction();

        try {
            // Log deletion
            AuditLog::recordDelete('device', $device->id, [
                'name'        => $device->name,
                'type'        => $device->type,
                'hardware_id' => $device->hardware_id,
            ]);

            $device->delete();

            // Decrement usage quota
            if ($this->usageQuota) {
                $this->usageQuota->decrementDeviceCount();
            }

            DB::commit();
            session()->flash('message', 'Device deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            logger()->error('Error deleting device: '.$e->getMessage());
            session()->flash('error', 'An error occurred while deleting the device. Please try again.');
        }
    }

    // Device status methods
    public function activateDevice(string $deviceId): void
    {
        $device = Device::find($deviceId);

        if ( ! $device) {
            session()->flash('error', 'Device not found.');

            return;
        }

        $device->update(['status' => 'active']);

        // Log status change
        AuditLog::recordAction(
            'activate_device',
            'device',
            $device->id,
            ['status' => $device->getOriginal('status')],
            ['status' => 'active'],
            "Activated device {$device->name}"
        );

        session()->flash('message', 'Device activated successfully.');
    }

    public function deactivateDevice(string $deviceId): void
    {
        $device = Device::find($deviceId);

        if ( ! $device) {
            session()->flash('error', 'Device not found.');

            return;
        }

        $device->update(['status' => 'inactive']);

        // Log status change
        AuditLog::recordAction(
            'deactivate_device',
            'device',
            $device->id,
            ['status' => $device->getOriginal('status')],
            ['status' => 'inactive'],
            "Deactivated device {$device->name}"
        );

        session()->flash('message', 'Device deactivated successfully.');
    }

    // Quota management methods
    public function isAtDeviceQuotaLimit(): bool
    {
        if ( ! $this->subscription || ! $this->usageQuota) {
            return false; // No subscription or quota means no limit
        }

        return $this->usageQuota->isDeviceQuotaExceeded();
    }

    public function getDeviceQuotaUsage(): int
    {
        return $this->usageQuota?->devices_count ?? 0;
    }

    public function getDeviceQuotaLimit(): int
    {
        return $this->subscription?->getMaxDevices() ?? 0;
    }

    public function getDeviceQuotaPercentage(): float
    {
        if ( ! $this->subscription || ! $this->usageQuota) {
            return 0.0;
        }

        return $this->subscription->getDeviceQuotaUsagePercentage();
    }

    public function viewQuotaDetails(): void
    {
        $this->showQuotaModal = true;
    }

    public function closeQuotaModal(): void
    {
        $this->showQuotaModal = false;
    }

    public function closeUpgradeModal(): void
    {
        $this->showUpgradeModal = false;
    }

    // UI methods
    public function closeModal(): void
    {
        $this->showDeviceModal = false;
        $this->resetDeviceForm();
    }

    public function resetDeviceForm(): void
    {
        $this->deviceId = null;
        $this->name = '';
        $this->type = 'android';
        $this->hardwareId = null;
        $this->location = null;
        $this->timezone = null;
        $this->orientation = 'landscape';
        $this->resetValidation();
    }

    // Sorting and filtering
    public function setSort(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query devices
        $devicesQuery = Device::query()
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('hardware_id', 'like', '%'.$this->search.'%')
                        ->orWhere('location', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function ($query) {
                return $query->where('type', $this->typeFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        // Get device type counts for filtering
        $deviceTypeCounts = Device::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // Get device status counts for filtering
        $deviceStatusCounts = Device::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('livewire.devices.device-manager', [
            'devices'            => $devicesQuery->paginate($this->perPage),
            'deviceTypeCounts'   => $deviceTypeCounts,
            'deviceStatusCounts' => $deviceStatusCounts,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Devices;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Stancl\Tenancy\Facades\Tenancy;
use App\Tenant\Models\Device;
use App\Services\DeviceRegistrationService;
use App\DTOs\DeviceRegistrationRequest;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
final class IntegrationGuide extends Component
{
    public string $tenantToken;

    public array $platforms = [
        [
            'key'  => 'android',
            'name' => 'Android',
        ],
        [
            'key'  => 'raspberry-pi',
            'name' => 'Raspberry Pi',
        ],
        [
            'key'  => 'windows',
            'name' => 'Windows',
        ],
    ];

    public string $selectedPlatform = 'android';

    public array $devices = [];
    public ?Device $selectedDevice = null;
    public string $newDeviceName = '';
    public string $newDeviceType = 'android';
    public bool $showRegistrationModal = false;

    public function mount(): void
    {
        // $this->authorize('viewAny', Auth::user());
        // $this->tenantToken = Tenancy::getTenant()?->getKey() ?? 'unknown-tenant';
        $this->devices = Device::all()->toArray();
        $this->selectedDevice = null;
    }

    public function selectPlatform(string $platform): void
    {
        $this->selectedPlatform = $platform;
    }

    public function registerDevice(): void
    {
        $request = new DeviceRegistrationRequest(
            tenantId: tenant('id'),
            name: $this->newDeviceName,
            type: $this->newDeviceType,
            hardwareId: Str::uuid()->toString(),
        );
        $device = app(DeviceRegistrationService::class)->register($request);
        $this->devices[] = $device->toArray();
        $this->selectedDevice = $device;
        $this->showRegistrationModal = false;
        $this->newDeviceName = '';
        $this->newDeviceType = 'android';
    }

    public function selectDevice(string $deviceId): void
    {
        $this->selectedDevice = Device::findOrFail($deviceId);
    }

    public function render()
    {
        return view('livewire.devices.integration-guide');
    }
}

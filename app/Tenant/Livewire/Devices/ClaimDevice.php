<?php

declare(strict_types=1);

namespace App\Tenant\Livewire\Devices;

use App\Services\DeviceRegistrationService;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Exception;

final class ClaimDevice extends Component
{
    #[Locked]
    public string $tenantId;

    public string $registrationCode = '';

    public function mount(): void
    {
        $this->tenantId = tenant('id');
    }

    public function claimDevice(DeviceRegistrationService $service): void
    {
        $this->validate([
            'registrationCode' => ['required', 'string', 'size:8'],
        ]);

        try {
            $device = $service->claimDevice(
                registrationCode: $this->registrationCode,
                tenantId: $this->tenantId
            );

            $this->dispatch('device-claimed', deviceId: $device->id);
            $this->reset('registrationCode');

            $this->dispatch('notify', [
                'type'    => 'success',
                'message' => 'Device claimed successfully!',
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tenant.devices.claim-device');
    }
}

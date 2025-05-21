<?php

declare(strict_types=1);

namespace App\Livewire\Devices;

use App\Tenant\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Illuminate\Support\Str;

final class DeviceShow extends Component
{
    #[Locked]
    public ?Device $device = null;

    public bool $showResetConfirmation = false;

    public bool $showDeviceModal = false;

    #[On('show-device')]
    public function openModal(string $id): void
    {
        $this->device = Device::findOrFail($id);
        $this->authorize('view', $this->device);
        $this->showDeviceModal = true;
    }

    public function render()
    {
        return view('livewire.devices.device-show');
    }

    public function confirmResetToken(): void
    {
        $this->showResetConfirmation = true;
    }

    public function cancelReset(): void
    {
        $this->showResetConfirmation = false;
    }

    public function resetActivationToken(): void
    {
        if ( ! $this->device) {
            return;
        }

        $this->authorize('update', $this->device);

        $this->device->activation_token = Str::random(32);
        $this->device->save();

        $this->showResetConfirmation = false;

        session()->flash('flash.banner', 'Device activation token reset successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }

    public function refreshDeviceStatus(): void
    {
        if ( ! $this->device) {
            return;
        }

        $this->device->refresh();
    }
}

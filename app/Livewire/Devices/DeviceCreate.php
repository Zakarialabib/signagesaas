<?php

declare(strict_types=1);

namespace App\Livewire\Devices;

use App\Tenant\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use App\Enums\DeviceType;
use App\Enums\DeviceStatus;

final class DeviceCreate extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $type = 'media-player';

    #[Validate('nullable|string|max:255')]
    public ?string $notes = null;

    #[Validate('nullable|string|max:255')]
    public ?string $location = null;

    public bool $openCreateDevice = false;

    #[On('openCreateDevice')]
    public function openModal(): void
    {
        $this->openCreateDevice = true;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'type', 'notes', 'location']);
        $this->type = DeviceType::MEDIA_PLAYER->value;
    }

    public function mount(): void
    {
        $this->authorize('create', Device::class);
    }

    public function render()
    {
        return view('livewire.devices.device-create', [
            'deviceTypes' => DeviceType::options(),
        ]);
    }

    public function createDevice(): void
    {
        $validated = $this->validate();

        $device = Device::create([
            'name'             => $validated['name'],
            'type'             => $validated['type'],
            'notes'            => $validated['notes'],
            'location'         => $validated['location'],
            'hardware_id'      => Str::uuid()->toString(),
            'activation_token' => Str::random(32),
            'status'           => DeviceStatus::INACTIVE->value,
        ]);

        // Dispatch event for the DeviceManager to handle
        $this->dispatch('device-created', deviceId: $device->id);

        // Clear the form
        $this->resetForm();

        // Close the modal
        $this->openCreateDevice = false;
    }
}

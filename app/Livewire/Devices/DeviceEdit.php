<?php

declare(strict_types=1);

namespace App\Livewire\Devices;

use App\Tenant\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Enums\DeviceType;

final class DeviceEdit extends Component
{
    public ?Device $device = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $type = '';

    #[Validate('nullable|string|max:255')]
    public ?string $notes = null;

    #[Validate('nullable|array')]
    public $location = null;

    public bool $editDevice = false;

    #[On('edit-device')]
    public function openModal(string $id): void
    {
        $device = Device::findOrFail($id);
        $this->authorize('update', $device);

        $this->device = $device;
        $this->name = $device->name;
        $this->type = $device->type->value;
        $this->notes = $device->notes ?? null;
        $this->location = $device->location ?? null;

        $this->editDevice = true;
    }

    public function render()
    {
        return view('livewire.devices.device-edit', [
            'deviceTypes' => DeviceType::options(),
        ]);
    }

    public function updateDevice(): void
    {
        if ( ! $this->device) {
            return;
        }

        $this->authorize('update', $this->device);

        $validated = $this->validate();

        $this->device->update([
            'name'     => $validated['name'],
            'type'     => $validated['type'],
            'notes'    => $validated['notes'],
            'location' => $validated['location'],
        ]);

        session()->flash('flash.banner', 'Device updated successfully!');
        session()->flash('flash.bannerStyle', 'success');

        $this->dispatch('device-updated', deviceId: $this->device->id);

        $this->editDevice = false;
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Screens;

use App\Enums\ScreenOrientation;
use App\Enums\ScreenResolution;
use App\Enums\ScreenStatus;
use App\Tenant\Models\Device;
use App\Tenant\Models\Screen;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

final class ScreenEditForm extends Component
{
    #[Locked]
    public ?Screen $screen = null;

    public bool $editScreenModal = false;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public ?string $description = null;

    #[Validate('required|string')]
    public string $status = '';

    #[Validate('required|string')]
    public string $orientation = '';

    #[Validate('required|string')]
    public string $resolution = '';

    #[Validate('nullable|array')]
    public ?array $location = null;

    #[Validate('nullable|array')]
    public ?array $settings = null;

    #[Locked]
    public string $device_id = '';

    public bool $showEditScreenModal = false;

    #[On('editScreen')]
    public function openModal(string $id): void
    {
        $this->editScreenModal = true;
        $this->screen = Screen::with('device')->findOrFail($id);
        $this->authorize('update', $this->screen);

        $this->device_id = $this->screen->device_id;
        $this->name = $this->screen->name;
        $this->description = $this->screen->description;
        $this->status = $this->screen->status->value;
        $this->orientation = $this->screen->orientation->value;
        $this->resolution = $this->screen->resolution->value;
        $this->location = $this->screen->location;
        $this->settings = $this->screen->settings;
    }

    #[Computed()]
    public function devices()
    {
        return Device::query()->get();
    }

    public function render()
    {
        return view('livewire.screens.screen-edit-form', [
            'orientations' => ScreenOrientation::options(),
            'statuses'     => ScreenStatus::options(),
            'resolutions'  => $this->getResolutionOptions(),
        ]);
    }

    /** Get available resolutions based on selected orientation */
    public function getResolutionOptions(): array
    {
        return $this->orientation === ScreenOrientation::LANDSCAPE->value
            ? ScreenResolution::landscapeOptions()
            : ScreenResolution::portraitOptions();
    }

    /** Method triggered when orientation changes to update the resolution options */
    public function updatedOrientation(): void
    {
        // If current resolution doesn't match orientation, reset to a default
        $currentOrientation = ScreenOrientation::from($this->orientation);

        // Check if current resolution is valid for the new orientation
        $isValidResolution = false;
        $resolutionsForOrientation = $currentOrientation === ScreenOrientation::LANDSCAPE
            ? ScreenResolution::landscapeOptions()
            : ScreenResolution::portraitOptions();

        if (array_key_exists($this->resolution, $resolutionsForOrientation)) {
            $isValidResolution = true;
        }

        // If not valid, set to default resolution for this orientation
        if ( ! $isValidResolution) {
            $this->resolution = $currentOrientation === ScreenOrientation::LANDSCAPE
                ? ScreenResolution::FULL_HD->value
                : ScreenResolution::PORTRAIT_FULL_HD->value;
        }
    }

    public function save(): void
    {
        if ( ! $this->screen) {
            return;
        }

        $this->authorize('update', $this->screen);

        $validated = $this->validate();

        $this->screen->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'status'      => $validated['status'],
            'orientation' => $validated['orientation'],
            'resolution'  => $validated['resolution'],
            'location'    => $validated['location'],
            'settings'    => $validated['settings'],
        ]);

        session()->flash('flash.banner', 'Screen updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        $this->dispatch('screen-updated', id: $this->screen->id);
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->editScreenModal = false;
        $this->reset(['name', 'description', 'status', 'orientation', 'resolution', 'location', 'settings']);
        $this->screen = null;
    }
}

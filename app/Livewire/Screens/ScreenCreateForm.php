<?php

declare(strict_types=1);

namespace App\Livewire\Screens;

use App\Enums\ScreenOrientation;
use App\Enums\ScreenResolution;
use App\Enums\ScreenStatus;
use App\Services\OnboardingProgressService;
use App\Tenant\Models\Device;
use App\Tenant\Models\OnboardingProgress;
use App\Tenant\Models\Screen;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Validate;

final class ScreenCreateForm extends Component
{
    /**
     * Screen name
     */
    #[Validate('required|string|max:255')]
    public string $name = '';

    /**
     * Screen description
     */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = null;

    /**
     * Screen status
     */
    #[Validate('required|string|in:active,inactive,maintenance,scheduled')]
    public string $status = 'active';

    /**
     * Screen resolution
     */
    #[Validate('required|string')]
    public string $resolution = '1920x1080';

    /**
     * Screen orientation
     */
    #[Validate('required|string|in:landscape,portrait')]
    public string $orientation = 'landscape';

    /**
     * Associated device ID
     */
    #[Validate('required|uuid|exists:devices,id')]
    public string $device_id = '';

    /**
     * Location name
     */
    #[Validate('nullable|string|max:255')]
    public ?string $locationName = null;

    /**
     * Location address
     */
    #[Validate('nullable|string|max:255')]
    public ?string $locationAddress = null;

    /**
     * Location zone
     */
    #[Validate('nullable|string|max:255')]
    public ?string $locationZone = null;

    /**
     * Location floor
     */
    #[Validate('nullable|string|max:255')]
    public ?string $locationFloor = null;

    /**
     * Controls the visibility of the create screen modal
     */
    public bool $createScreenModal = false;

    /**
     * Open the create screen modal and authorize the action
     */
    #[On('createScreen')]
    public function openModal(): void
    {
        $this->authorize('create', Screen::class);
        $this->reset();
        $this->status = 'active';
        $this->orientation = 'landscape';
        $this->resolution = ScreenResolution::FULL_HD->value;
        $this->createScreenModal = true;
    }

    /**
     * Render the screen create form
     */
    public function render(): View
    {
        return view('livewire.screens.screen-create-form', [
            'devices'      => Device::where('status', 'active')->get(['id', 'name', 'orientation', 'type']),
            'statuses'     => ScreenStatus::options(),
            'orientations' => ScreenOrientation::options(),
            'resolutions'  => $this->getResolutionOptions(),
        ]);
    }

    /**
     * Get available resolutions based on selected orientation
     */
    public function getResolutionOptions(): array
    {
        return $this->orientation === ScreenOrientation::LANDSCAPE->value
            ? ScreenResolution::landscapeOptions()
            : ScreenResolution::portraitOptions();
    }

    /**
     * Update resolution options when orientation changes
     */
    public function updatedOrientation(): void
    {
        // Set default resolution based on new orientation
        $this->resolution = $this->orientation === ScreenOrientation::LANDSCAPE->value
            ? ScreenResolution::FULL_HD->value
            : ScreenResolution::PORTRAIT_FULL_HD->value;
    }

    /**
     * Update orientation when device is selected
     */
    public function updatedDeviceId(): void
    {
        if (!$this->device_id) {
            return;
        }

        // Get the device and set orientation to match device if possible
        $device = Device::find($this->device_id, ['id', 'orientation']);

        if ($device && $device->orientation) {
            $this->orientation = $device->orientation->value;
            $this->updatedOrientation(); // Update resolution to match
        }
    }

    /**
     * Save the screen
     */
    public function save(): void
    {
        $this->authorize('create', Screen::class);

        $validatedData = $this->validate();

        // Prepare location data
        $location = null;
        if ($this->locationName || $this->locationAddress || $this->locationZone || $this->locationFloor) {
            $location = [
                'name'    => $this->locationName,
                'address' => $this->locationAddress,
                'zone'    => $this->locationZone,
                'floor'   => $this->locationFloor,
            ];
        }

        try {
            // Use transaction to ensure data integrity
            DB::transaction(function () use ($validatedData, $location) {
                // Create screen
                $screen = Screen::create([
                    'name'        => $validatedData['name'],
                    'description' => $validatedData['description'],
                    'status'      => $validatedData['status'],
                    'resolution'  => $validatedData['resolution'],
                    'orientation' => $validatedData['orientation'],
                    'device_id'   => $validatedData['device_id'],
                    'location'    => $location,
                    'settings'    => [
                        'refresh_rate'        => 60,
                        'transition_effect'   => 'fade',
                        'transition_duration' => 1000,
                    ],
                ]);
                
                // Mark onboarding step as complete
                $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $screen->tenant_id]);
                if (!$onboardingProgress->first_screen_created) {
                    app(OnboardingProgressService::class)->completeStep($onboardingProgress, 'first_screen_created');
                }

                // Notify parent component
                $this->dispatch('screen-created', id: $screen->id);
            });

            // Show notification
            session()->flash('flash.banner', 'Screen created successfully.');
            session()->flash('flash.bannerStyle', 'success');
            
            // Reset the form and close modal
            $this->reset();
            $this->createScreenModal = false;
            
        } catch (\Exception $e) {
            session()->flash('flash.banner', 'Error creating screen: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
    }
    
    /**
     * Cancel screen creation and close modal
     */
    public function cancel(): void
    {
        $this->reset();
        $this->createScreenModal = false;
    }
}

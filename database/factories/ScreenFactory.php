<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ScreenOrientation;
use App\Enums\ScreenResolution;
use App\Enums\ScreenStatus;
use App\Tenant\Models\Device;
use App\Tenant\Models\Screen;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

final class ScreenFactory extends Factory
{
    protected $model = Screen::class;

    public function definition(): array
    {
        $device = Device::factory()->create();

        // Determine orientation - match device if available
        $orientation = $device->orientation ?? fake()->randomElement(ScreenOrientation::cases());

        // Select an appropriate resolution based on orientation
        $resolution = $this->getResolutionForOrientation($orientation);

        // Default tenant_id from device, can be overridden by forTenant state
        $tenantId = $device->tenant_id;

        return [
            'tenant_id'   => $tenantId,
            'name'        => fake()->words(2, true).' Screen',
            'description' => fake()->optional()->sentence(),
            'status'      => fake()->randomElement(ScreenStatus::cases()),
            'resolution'  => $resolution,
            'orientation' => $orientation,
            'device_id'   => $device->id,
            'location'    => [
                'zone'     => fake()->word(),
                'floor'    => fake()->numberBetween(1, 10),
                'building' => fake()->optional()->buildingNumber(),
                'notes'    => fake()->optional()->sentence(),
            ],
            'settings' => [
                'refresh_rate'        => fake()->numberBetween(30, 120),
                'transition_effect'   => fake()->randomElement(['fade', 'slide', 'none']),
                'transition_duration' => fake()->numberBetween(300, 1500),
                'brightness'          => fake()->numberBetween(50, 100),
                'volume'              => fake()->numberBetween(0, 100),
                'enable_touch'        => fake()->boolean(70),
                'enable_sensors'      => fake()->boolean(30),
            ],
        ];
    }

    /** Helper method to get appropriate resolution for orientation */
    private function getResolutionForOrientation(ScreenOrientation $orientation): ScreenResolution
    {
        return match ($orientation) {
            ScreenOrientation::LANDSCAPE => fake()->randomElement([
                ScreenResolution::FULL_HD,
                ScreenResolution::UHD_4K,
                ScreenResolution::HD,
                ScreenResolution::HD_PLUS,
                ScreenResolution::XGA,
            ]),
            ScreenOrientation::PORTRAIT => fake()->randomElement([
                ScreenResolution::PORTRAIT_FULL_HD,
                ScreenResolution::PORTRAIT_HD,
                ScreenResolution::PORTRAIT_HD_PLUS,
            ]),
        };
    }

    public function forTenant(Tenant $tenant): self
    {
        return $this->state([
            'tenant_id' => $tenant->id,
        ]);
    }

    public function active(): self
    {
        return $this->state([
            'status' => ScreenStatus::ACTIVE,
        ]);
    }

    public function inactive(): self
    {
        return $this->state([
            'status' => ScreenStatus::INACTIVE,
        ]);
    }

    public function maintenance(): self
    {
        return $this->state([
            'status' => ScreenStatus::MAINTENANCE,
        ]);
    }

    public function scheduled(): self
    {
        return $this->state([
            'status' => ScreenStatus::SCHEDULED,
        ]);
    }

    public function forDevice(Device $device): self
    {
        return $this->state(function () use ($device) {
            // Match orientation with device
            $orientation = $device->orientation ?? ScreenOrientation::LANDSCAPE;

            return [
                'device_id'   => $device->id,
                'tenant_id'   => $device->tenant_id,
                'orientation' => $orientation,
                'resolution'  => $this->getResolutionForOrientation($orientation),
            ];
        });
    }

    public function landscape(): self
    {
        return $this->state([
            'orientation' => ScreenOrientation::LANDSCAPE,
            'resolution'  => fake()->randomElement([
                ScreenResolution::FULL_HD,
                ScreenResolution::UHD_4K,
                ScreenResolution::HD,
                ScreenResolution::HD_PLUS,
            ]),
        ]);
    }

    public function portrait(): self
    {
        return $this->state([
            'orientation' => ScreenOrientation::PORTRAIT,
            'resolution'  => fake()->randomElement([
                ScreenResolution::PORTRAIT_FULL_HD,
                ScreenResolution::PORTRAIT_HD,
                ScreenResolution::PORTRAIT_HD_PLUS,
            ]),
        ]);
    }

    public function fullHD(): self
    {
        return $this->state([
            'orientation' => ScreenOrientation::LANDSCAPE,
            'resolution'  => ScreenResolution::FULL_HD,
        ]);
    }

    public function uhd4K(): self
    {
        return $this->state([
            'orientation' => ScreenOrientation::LANDSCAPE,
            'resolution'  => ScreenResolution::UHD_4K,
        ]);
    }

    public function portraitFullHD(): self
    {
        return $this->state([
            'orientation' => ScreenOrientation::PORTRAIT,
            'resolution'  => ScreenResolution::PORTRAIT_FULL_HD,
        ]);
    }

    public function withZone(string $zone): self
    {
        return $this->state(function (array $attributes) use ($zone) {
            $location = $attributes['location'] ?? [];
            $location['zone'] = $zone;

            return [
                'location' => $location,
            ];
        });
    }

    public function withSettings(array $settings): self
    {
        return $this->state(function (array $attributes) use ($settings) {
            return [
                'settings' => array_merge($attributes['settings'] ?? [], $settings),
            ];
        });
    }

    public function withTouchEnabled(): self
    {
        return $this->withSettings(['enable_touch' => true]);
    }

    public function withSensorsEnabled(): self
    {
        return $this->withSettings(['enable_sensors' => true]);
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Screen $screen) {
            if ($screen->wasRecentlyCreated && isset($this->contentToAttach)) {
                $screen->contents()->attach($this->contentToAttach);
            }
        });
    }

    protected ?Collection $contentToAttach = null;

    public function withContent(int $count = 1): self
    {
        return $this->afterCreating(function (Screen $screen) use ($count) {
            // Content created will belong to the same tenant as the screen
            $contentItems = Content::factory()->count($count)->forTenant($screen->tenant)->create([
                // Potentially override screen_id if ContentFactory defaults to creating one screen
                // Or ensure ContentFactory for this use case doesn't auto-assign a primary screen_id
                // For now, we assume ContentFactory is flexible or we primarily use pivot table.
            ]);
            $screen->contents()->attach($contentItems->pluck('id')->all());
        });
    }

    public function attachContent(array|Collection $contentIds): self
    {
        $this->contentToAttach = collect($contentIds);

        return $this;
    }
}

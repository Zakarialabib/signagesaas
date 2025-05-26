<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Enums\ScreenOrientation;
use App\Tenant\Models\Device;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\Screen;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        $tenantId = fn () => Tenant::factory()->create()->id;

        return [
            'tenant_id'         => $tenantId,
            'name'              => fake()->company().' Display',
            'type'              => fake()->randomElement(DeviceType::cases()),
            'status'            => fake()->randomElement(DeviceStatus::cases()),
            'hardware_id'       => fake()->unique()->uuid(),
            'activation_token'  => Str::random(32),
            'ip_address'        => fake()->ipv4(),
            'last_ping_at'      => fake()->optional()->dateTimeBetween('-1 hour', 'now'),
            'last_sync_at'      => fake()->optional()->dateTimeBetween('-1 day', 'now'),
            'screen_resolution' => fake()->randomElement(['1920x1080', '3840x2160', '1280x720']),
            'orientation'       => fake()->randomElement(ScreenOrientation::cases()),
            'os_version'        => 'Android '.fake()->numberBetween(9, 13),
            'app_version'       => '1.'.fake()->numberBetween(0, 9).'.'.fake()->numberBetween(0, 9),
            'location'          => [
                'lat'     => fake()->latitude(),
                'lng'     => fake()->longitude(),
                'address' => fake()->address(),
            ],
            'timezone' => fake()->timezone(),
            'settings' => [
                'brightness' => fake()->numberBetween(0, 100),
                'volume'     => fake()->numberBetween(0, 100),
                'autoUpdate' => fake()->boolean(),
            ],
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Device $device) {
            if (isset($this->numberOfScreensToCreate) && $this->numberOfScreensToCreate > 0) {
                Screen::factory()->count($this->numberOfScreensToCreate)->forDevice($device)->create();
            }
        });
    }

    protected int $numberOfScreensToCreate = 0;

    public function withScreens(int $count = 1): self
    {
        $this->numberOfScreensToCreate = $count;
        return $this;
    }

    public function forTenant(Tenant $tenant): self
    {
        return $this->state([
            'tenant_id' => $tenant->id,
        ]);
    }

    public function online(): self
    {
        return $this->state([
            'status'       => DeviceStatus::ONLINE,
            'last_ping_at' => now()->subMinutes(fake()->numberBetween(0, 4)),
        ]);
    }

    public function offline(): self
    {
        return $this->state([
            'status'       => DeviceStatus::OFFLINE,
            'last_ping_at' => fake()->optional()->dateTimeBetween('-1 day', '-1 hour'),
        ]);
    }

    public function maintenance(): self
    {
        return $this->state([
            'status'       => DeviceStatus::MAINTENANCE,
            'last_ping_at' => now()->subHours(fake()->numberBetween(1, 12)),
        ]);
    }

    public function inactive(): self
    {
        return $this->state([
            'status'       => DeviceStatus::INACTIVE,
            'last_ping_at' => null,
            'last_sync_at' => null,
        ]);
    }

    public function withType(DeviceType $type): self
    {
        return $this->state([
            'type' => $type,
        ]);
    }

    public function landscape(): self
    {
        return $this->state([
            'orientation'       => ScreenOrientation::LANDSCAPE,
            'screen_resolution' => fake()->randomElement(['1920x1080', '3840x2160']),
        ]);
    }

    public function portrait(): self
    {
        return $this->state([
            'orientation'       => ScreenOrientation::PORTRAIT,
            'screen_resolution' => fake()->randomElement(['1080x1920', '720x1280']),
        ]);
    }
}

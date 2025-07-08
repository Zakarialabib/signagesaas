<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Tenant\Models\Layout;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition(): array
    {
        $placeTypes = ['WALL_MOUNT', 'KIOSK', 'ROOM_ENTRANCE', 'SHELF_EDGE', 'GENERAL_AREA', 'CEILING_DISPLAY'];

        return [
            'tenant_id'   => Tenant::factory(),
            'name'        => fake()->unique()->words(3, true) . ' Place',
            'description' => fake()->optional()->sentence(10),
            'type'        => fake()->randomElement($placeTypes),
            'x'           => fake()->optional(0.7)->randomFloat(2, 0, 1000), // Optional, up to 1000
            'y'           => fake()->optional(0.7)->randomFloat(2, 0, 1000),
            'width'       => fake()->optional(0.7)->randomFloat(2, 50, 500),
            'height'      => fake()->optional(0.7)->randomFloat(2, 50, 300),
            'style_data'  => fake()->optional(0.5)->randomElement([
                                ['color' => fake()->hexColor(), 'icon' => 'pin'],
                                ['shape' => 'rectangle', 'borderColor' => fake()->hexColor()],
                                [],
                            ]),
            'metadata'    => fake()->optional(0.6)->randomElement([
                                ['floor' => fake()->numberBetween(1, 10), 'building_wing' => fake()->randomElement(['North', 'South', 'East', 'West'])],
                                ['gps_lat' => fake()->latitude(), 'gps_lon' => fake()->longitude()],
                                ['capacity' => fake()->numberBetween(5, 50)],
                                [],
                            ]),
        ];
    }
}

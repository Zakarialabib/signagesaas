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
        return [
            'tenant_id' => Tenant::factory(),
            'layout_id' => Layout::factory(),
            'name'      => fake()->words(2, true),
            'type'      => fake()->randomElement(['image', 'video', 'text', 'html']),
            'x'         => fake()->randomFloat(2, 0, 100),
            'y'         => fake()->randomFloat(2, 0, 100),
            'width'     => fake()->randomFloat(2, 10, 100),
            'height'    => fake()->randomFloat(2, 10, 100),
            'order'     => fake()->numberBetween(0, 10),
            'settings'  => [
                'transition_effect'   => fake()->randomElement(['fade', 'slide', 'zoom']),
                'transition_duration' => fake()->numberBetween(500, 2000),
                'content_fit'         => fake()->randomElement(['contain', 'cover', 'fill']),
                'background_color'    => fake()->hexColor(),
            ],
            'style_data' => [
                'border_style'  => fake()->randomElement(['none', 'solid', 'dashed']),
                'border_width'  => fake()->numberBetween(0, 5).'px',
                'border_color'  => fake()->hexColor(),
                'border_radius' => fake()->numberBetween(0, 20).'px',
            ],
            'content_type' => fake()->randomElement(['image', 'video', 'text']),
            'metadata'     => [
                'created_by'    => fake()->name(),
                'last_modified' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            ],
        ];
    }
}

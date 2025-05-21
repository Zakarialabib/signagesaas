<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Tenant\Models\Layout;
use App\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayoutFactory extends Factory
{
    protected $model = Layout::class;

    public function definition(): array
    {
        return [
            'tenant_id'   => Tenant::factory(),
            'name'        => fake()->words(2, true),
            'description' => fake()->sentence(),
            'width'       => fake()->numberBetween(800, 1920),
            'height'      => fake()->numberBetween(600, 1080),
            'settings'    => [
                'background_color' => fake()->hexColor(),
                'scale_mode'       => fake()->randomElement(['fit', 'fill', 'stretch']),
            ],
            'metadata' => [
                'created_by'       => fake()->name(),
                'template_version' => '1.0',
            ],
        ];
    }
}

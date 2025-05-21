<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Tenant\Models\Template;
use App\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    protected $model = Template::class;

    public function definition(): array
    {
        return [
            'tenant_id'   => Tenant::factory(),
            'name'        => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'category'    => fake()->randomElement(['digital-signage', 'menu-board', 'information-display', 'advertising']),
            'status'      => fake()->randomElement(['draft', 'published', 'archived']),
            'layout'      => [
                'width'      => fake()->numberBetween(800, 1920),
                'height'     => fake()->numberBetween(600, 1080),
                'background' => fake()->hexColor(),
                'grid_size'  => fake()->numberBetween(8, 24),
            ],
            'styles' => [
                'font_family'     => fake()->randomElement(['Arial', 'Roboto', 'Open Sans']),
                'primary_color'   => fake()->hexColor(),
                'secondary_color' => fake()->hexColor(),
                'text_color'      => fake()->hexColor(),
            ],
            'default_duration' => fake()->numberBetween(5, 30),
            'metadata'         => [
                'created_by'    => fake()->name(),
                'version'       => '1.0',
                'last_modified' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s'),
                'tags'          => fake()->words(3),
            ],
            'settings' => [
                'transition_effect'   => fake()->randomElement(['fade', 'slide', 'zoom']),
                'transition_duration' => fake()->numberBetween(500, 2000),
                'auto_play'           => fake()->boolean(),
                'loop'                => fake()->boolean(),
            ],
            'preview_image' => fake()->imageUrl(800, 600),
        ];
    }

    public function published(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
            ];
        });
    }

    public function draft(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft',
            ];
        });
    }

    public function asVersion(Template $parent, int $version): self
    {
        return $this->state(function (array $attributes) use ($parent, $version) {
            return [
                'parent_id'    => $parent->id,
                'version'      => $version,
                'is_variation' => false,
                'tenant_id'    => $parent->tenant_id,
            ];
        });
    }

    public function asVariation(Template $parent): self
    {
        return $this->state(function (array $attributes) use ($parent) {
            return [
                'parent_id'    => $parent->id,
                'version'      => $parent->version,
                'is_variation' => true,
                'tenant_id'    => $parent->tenant_id,
                'name'         => $attributes['name'].' (Variation)',
            ];
        });
    }

    public function withVersionHistory(int $versionCount = 3): self
    {
        return $this->afterCreating(function (Template $template) use ($versionCount) {
            for ($i = 2; $i <= $versionCount; $i++) {
                self::new()->asVersion($template, $i)->create();
            }
        });
    }

    public function withVariations(int $variationCount = 2): self
    {
        return $this->afterCreating(function (Template $template) use ($variationCount) {
            for ($i = 1; $i <= $variationCount; $i++) {
                self::new()->asVariation($template)->create();
            }
        });
    }
}

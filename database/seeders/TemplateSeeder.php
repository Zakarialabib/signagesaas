<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TemplateCategory;
use App\Enums\TemplateStatus;
use App\Tenant\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name'        => 'Retail Promotion Display',
                'description' => 'A dynamic template for showcasing product promotions and sales with eye-catching visuals.',
                'category'    => TemplateCategory::RETAIL,
                'status'      => TemplateStatus::PUBLISHED,
                'layout'      => [
                    'type'    => 'grid',
                    'columns' => 2,
                    'rows'    => 2,
                    'gap'     => '1rem',
                    'areas'   => [
                        'header header',
                        'main sidebar',
                    ],
                ],
                'styles' => [
                    'font-family'      => 'Inter, sans-serif',
                    'background-color' => '#ffffff',
                    'color'            => '#000000',
                    'header'           => [
                        'background-color' => '#4f46e5',
                        'color'            => '#ffffff',
                        'padding'          => '2rem',
                    ],
                    'main' => [
                        'padding' => '2rem',
                    ],
                    'sidebar' => [
                        'background-color' => '#f3f4f6',
                        'padding'          => '1.5rem',
                    ],
                ],
                'default_duration' => 30,
                'settings'         => [
                    'transition'          => 'slide',
                    'transition_duration' => 800,
                    'refresh_interval'    => 300,
                ],
            ],
            [
                'name'        => 'Restaurant Menu Board',
                'description' => 'Digital menu board template with sections for daily specials, main menu, and pricing.',
                'category'    => TemplateCategory::RESTAURANT,
                'status'      => TemplateStatus::PUBLISHED,
                'layout'      => [
                    'type'    => 'grid',
                    'columns' => 3,
                    'rows'    => 2,
                    'gap'     => '1.5rem',
                    'areas'   => [
                        'header header header',
                        'menu specials pricing',
                    ],
                ],
                'styles' => [
                    'font-family'      => 'Poppins, sans-serif',
                    'background-color' => '#1a1a1a',
                    'color'            => '#ffffff',
                    'header'           => [
                        'background-color' => '#2d3748',
                        'padding'          => '2rem',
                        'text-align'       => 'center',
                    ],
                    'menu' => [
                        'background-color' => '#2d3748',
                        'padding'          => '1.5rem',
                        'border-radius'    => '0.5rem',
                    ],
                ],
                'default_duration' => 45,
                'settings'         => [
                    'transition'          => 'fade',
                    'transition_duration' => 500,
                    'refresh_interval'    => 600,
                ],
            ],
            [
                'name'        => 'Corporate Lobby Display',
                'description' => 'Professional template for corporate lobbies featuring company news, events, and visitor information.',
                'category'    => TemplateCategory::CORPORATE,
                'status'      => TemplateStatus::PUBLISHED,
                'layout'      => [
                    'type'    => 'grid',
                    'columns' => 12,
                    'rows'    => 'auto',
                    'gap'     => '1rem',
                    'areas'   => [
                        'header header header header header header header header header header header header',
                        'news news news news events events events events visitor visitor visitor visitor',
                        'footer footer footer footer footer footer footer footer footer footer footer footer',
                    ],
                ],
                'styles' => [
                    'font-family'      => 'Inter, sans-serif',
                    'background-color' => '#f8fafc',
                    'color'            => '#1a1a1a',
                    'header'           => [
                        'background-color' => '#ffffff',
                        'box-shadow'       => '0 1px 3px rgba(0, 0, 0, 0.1)',
                        'padding'          => '2rem',
                    ],
                ],
                'default_duration' => 60,
                'settings'         => [
                    'transition'          => 'slide',
                    'transition_duration' => 700,
                    'refresh_interval'    => 900,
                ],
            ],
            [
                'name'        => 'Hotel Welcome Screen',
                'description' => 'Elegant template for hotel lobbies with weather, events, and amenity information.',
                'category'    => TemplateCategory::HOSPITALITY,
                'status'      => TemplateStatus::PUBLISHED,
                'layout'      => [
                    'type'    => 'grid',
                    'columns' => 3,
                    'rows'    => 3,
                    'gap'     => '2rem',
                ],
                'styles' => [
                    'font-family'      => 'Playfair Display, serif',
                    'background-color' => '#f8f9fa',
                    'color'            => '#2d3748',
                ],
                'default_duration' => 40,
                'settings'         => [
                    'transition'          => 'fade',
                    'transition_duration' => 600,
                    'refresh_interval'    => 300,
                ],
            ],
            [
                'name'        => 'Transportation Schedule',
                'description' => 'Real-time transportation schedule display with departure times and platform information.',
                'category'    => TemplateCategory::TRANSPORTATION,
                'status'      => TemplateStatus::PUBLISHED,
                'layout'      => [
                    'type'    => 'grid',
                    'columns' => 1,
                    'rows'    => 'auto',
                    'gap'     => '0',
                ],
                'styles' => [
                    'font-family'      => 'IBM Plex Mono, monospace',
                    'background-color' => '#000000',
                    'color'            => '#ffffff',
                ],
                'default_duration' => 20,
                'settings'         => [
                    'transition'       => 'none',
                    'refresh_interval' => 60,
                ],
            ],
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }

        // Create templates with version history and variations
        Template::factory()
            ->count(3)
            ->state(function (array $attributes) {
                return [
                    'name' => 'Dynamic Display Template '.fake()->unique()->numberBetween(1, 100),
                ];
            })
            ->withVersionHistory(4)  // Create 4 versions for each template
            ->withVariations(2)      // Create 2 variations for each template
            ->create();

        // Create some templates without versions or variations for variety
        Template::factory()
            ->count(2)
            ->create();

        // Create a template with only variations
        Template::factory()
            ->withVariations(3)
            ->create();

        // Create a template with only version history
        Template::factory()
            ->withVersionHistory(5)
            ->create();
    }
}

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
                'is_system'   => true,
                'layout'      => [
                    'type'  => 'fixed', // Using fixed type for percentage based zones
                    'zones' => [
                        [
                            'id'             => 'header',
                            'name'           => 'Header Area',
                            'type'           => 'content',
                            'x_percentage'   => 0, 'y_percentage' => 0, 'width_percentage' => 100, 'height_percentage' => 15,
                            'content_type_suggestion' => 'image',
                            'settings'       => ['background_color' => '#2d3748', 'padding' => '1rem']
                        ],
                        [
                            'id'             => 'menu_left',
                            'name'           => 'Menu (Left Column)',
                            'type'           => 'widget',
                            'widget_type'    => 'MenuWidget',
                            'x_percentage'   => 5, 'y_percentage' => 18, 'width_percentage' => 43, 'height_percentage' => 70,
                            'settings'       => ['background_color' => '#ffffff', 'padding' => '1rem', 'border-radius' => '0.5rem']
                        ],
                        [
                            'id'             => 'menu_right',
                            'name'           => 'Menu (Right Column)',
                            'type'           => 'widget',
                            'widget_type'    => 'MenuWidget',
                            'x_percentage'   => 52, 'y_percentage' => 18, 'width_percentage' => 43, 'height_percentage' => 70,
                            'settings'       => ['background_color' => '#ffffff', 'padding' => '1rem', 'border-radius' => '0.5rem']
                        ],
                        [
                            'id'             => 'footer',
                            'name'           => 'Footer Area',
                            'type'           => 'content',
                            'x_percentage'   => 0, 'y_percentage' => 90, 'width_percentage' => 100, 'height_percentage' => 10,
                            'content_type_suggestion' => 'text',
                            'settings'       => ['background_color' => '#2d3748', 'text_align' => 'center', 'padding' => '0.5rem']
                        ]
                    ]
                ],
                'styles' => [
                    'font-family'      => 'Poppins, sans-serif',
                    'background-color' => '#1a1a1a', // Dark background for the overall page
                    'color'            => '#ffffff', // White text for overall page
                    // Specific zone styles can be handled within layout.zones[n].settings or here if needed globally
                ],
                'default_duration' => 45,
                'settings'         => [
                    'transition'          => 'fade',
                    'transition_duration' => 500,
                    'refresh_interval'    => 600,
                ],
            ],
            [
                'name'        => 'Retail Product Showcase',
                'description' => 'A versatile template for showcasing products, promotions, and sales in a retail setting.',
                'category'    => TemplateCategory::RETAIL,
                'status'      => TemplateStatus::PUBLISHED,
                'is_system'   => true,
                'layout'      => [
                    'type'  => 'fixed',
                    'zones' => [
                        [
                            'id'             => 'promo_header',
                            'name'           => 'Promotion Header',
                            'type'           => 'content',
                            'x_percentage'   => 0, 'y_percentage' => 0, 'width_percentage' => 100, 'height_percentage' => 20,
                            'content_type_suggestion' => 'image',
                            'settings'       => ['background_color' => '#e0e0e0', 'padding' => '1rem']
                        ],
                        [
                            'id'             => 'product_grid_main',
                            'name'           => 'Main Product Grid',
                            'type'           => 'widget',
                            'widget_type'    => 'ProductGridWidget',
                            'x_percentage'   => 5, 'y_percentage' => 22, 'width_percentage' => 65, 'height_percentage' => 73,
                            'settings'       => ['background_color' => '#ffffff', 'padding' => '1rem']
                        ],
                        [
                            'id'             => 'sidebar_featured_item',
                            'name'           => 'Featured Item Sidebar',
                            'type'           => 'widget',
                            'widget_type'    => 'FeaturedProductWidget',
                            'x_percentage'   => 75, 'y_percentage' => 22, 'width_percentage' => 20, 'height_percentage' => 73,
                            'content_type_suggestion' => 'image',
                            'settings'       => ['background_color' => '#f0f0f0', 'padding' => '1rem']
                        ],
                        [
                            'id'             => 'footer_banner',
                            'name'           => 'Footer Banner',
                            'type'           => 'content',
                            'x_percentage'   => 0, 'y_percentage' => 95, 'width_percentage' => 100, 'height_percentage' => 5,
                            'content_type_suggestion' => 'text',
                            'settings'       => ['background_color' => '#333333', 'color' => '#ffffff', 'text_align' => 'center', 'padding' => '0.5rem']
                        ]
                    ]
                ],
                'styles' => [
                    'font-family'      => 'Roboto, sans-serif',
                    'background-color' => '#f7f7f7',
                    'color'            => '#333333',
                ],
                'default_duration' => 60,
                'settings'         => [
                    'transition'          => 'slide',
                    'transition_duration' => 700,
                    'refresh_interval'    => 300,
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

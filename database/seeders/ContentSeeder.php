<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Tenant\Models\Content;
use App\Tenant\Models\Screen;
use App\Tenant\Models\Template;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $templates = Template::all();
        $screens = Screen::all();

        if ($templates->isEmpty()) {
            $this->command->info('No templates found to seed content for.');

            return;
        }

        $this->command->getOutput()->progressStart($templates->count());

        // Create content for each template
        $templates->each(function (Template $template) use ($screens) {
            // Create 2-4 content items for each template
            $count = rand(2, 4);

            for ($i = 0; $i < $count; $i++) {
                $content = Content::create([
                    'name'        => 'Content '.($i + 1)." for {$template->name}",
                    'description' => "Sample content for {$template->name}",
                    'type'        => ContentType::HTML,
                    'status'      => ContentStatus::ACTIVE,
                    'template_id' => $template->id,
                    'duration'    => $template->default_duration,
                    'data'        => $this->getContentData($template->category->value),
                    'settings'    => [
                        'transition'          => 'fade',
                        'transition_duration' => 500,
                        'refresh_interval'    => 300,
                    ],
                    'metadata' => [
                        'created_by'       => 'system',
                        'last_modified_by' => 'system',
                        'version'          => '1.0.0',
                    ],
                ]);

                // Assign content to random screens (1-3 screens)
                $randomScreens = $screens->random(rand(1, min(3, $screens->count())));
                $content->screens()->attach($randomScreens->pluck('id')->toArray(), [
                    'order'    => rand(1, 10),
                    'duration' => $template->default_duration,
                    'settings' => [
                        'transition'          => 'fade',
                        'transition_duration' => 500,
                    ],
                ]);
            }
            $this->command->getOutput()->progressAdvance();
        });

        $this->command->getOutput()->progressFinish();

        $this->command->info('\nCreating special content items...');
        // Create some special content items
        $this->createSpecialContent($templates, $screens);
    }

    private function getContentData(string $category): array
    {
        return match ($category) {
            'menu' => [
                'menuData' => [
                    [
                        'name'        => 'Breakfast Specials',
                        'description' => 'Served until 11 AM',
                        'items'       => [
                            [
                                'name'        => 'Classic Pancakes',
                                'description' => 'Fluffy pancakes served with syrup and butter.',
                                'price'       => 7.99,
                                'calories'    => 450,
                                'allergens'   => ['gluten', 'dairy'],
                                'image'       => 'pancakes.jpg',
                                'special'     => true,
                            ],
                            [
                                'name'        => 'Avocado Toast',
                                'description' => 'Smashed avocado on artisan bread with cherry tomatoes.',
                                'price'       => 9.50,
                                'calories'    => 320,
                                'allergens'   => ['gluten'],
                                'image'       => 'avocado_toast.jpg',
                                'special'     => false,
                            ],
                        ],
                    ],
                    [
                        'name'        => 'Lunch Menu',
                        'description' => 'Available from 11 AM to 3 PM',
                        'items'       => [
                            [
                                'name'        => 'Club Sandwich',
                                'description' => 'Triple decker with turkey, bacon, lettuce, and tomato.',
                                'price'       => 12.75,
                                'calories'    => 750,
                                'allergens'   => ['gluten', 'dairy'],
                                'image'       => 'club_sandwich.jpg',
                                'special'     => false,
                            ],
                        ],
                    ],
                ],
            ],
            'announcement' => [
                'title'     => 'Important Announcement',
                'message'   => 'Welcome to our facility. Please check in at the reception desk.',
                'image_url' => 'announcements/welcome.jpg',
            ],
            'news' => [
                'title' => 'Latest Updates',
                'items' => [
                    ['title' => 'Company News', 'content' => 'Exciting developments coming soon!'],
                    ['title' => 'Events', 'content' => 'Join us for the upcoming town hall meeting.'],
                ],
            ],
            'calendar' => [
                'calendarEventData' => [
                    [
                        'id'          => 'event_001',
                        'title'       => 'Team Meeting',
                        'description' => 'Weekly team sync and project updates',
                        'start_time'  => '09:00',
                        'end_time'    => '10:00',
                        'date'        => now()->format('Y-m-d'),
                        'location'    => 'Conference Room A',
                        'organizer'   => 'Sarah Johnson',
                        'attendees'   => 12,
                        'type'        => 'meeting',
                        'priority'    => 'high',
                        'color'       => '#3b82f6',
                    ],
                    [
                        'id'          => 'event_002',
                        'title'       => 'Product Launch',
                        'description' => 'Introducing our latest product line',
                        'start_time'  => '14:00',
                        'end_time'    => '16:00',
                        'date'        => now()->addDay()->format('Y-m-d'),
                        'location'    => 'Main Auditorium',
                        'organizer'   => 'Marketing Team',
                        'attendees'   => 150,
                        'type'        => 'presentation',
                        'priority'    => 'high',
                        'color'       => '#10b981',
                    ],
                ],
            ],
            'retail' => [
                'productData' => [
                    [
                        'id'             => 'prod_101',
                        'name'           => 'Wireless Bluetooth Headphones',
                        'description'    => 'Premium noise-cancelling headphones with 30-hour battery life and crystal-clear sound quality.',
                        'price'          => 199.99,
                        'original_price' => 249.99,
                        'image'          => 'https://placehold.co/600x400/2563EB/FFFFFF?text=Headphones',
                        'rating'         => 4.5,
                        'review_count'   => 324,
                        'tags'           => ['Electronics', 'Audio', 'Wireless', 'Sale'],
                        'stock_status'   => 'In Stock',
                        'features'       => ['Noise Cancelling', '30hr Battery', 'Quick Charge', 'Wireless'],
                    ],
                    [
                        'id'             => 'prod_102',
                        'name'           => 'Smart Fitness Watch',
                        'description'    => 'Track your health and fitness goals with this advanced smartwatch featuring GPS, heart rate monitoring, and sleep tracking.',
                        'price'          => 299.99,
                        'original_price' => null,
                        'image'          => 'https://placehold.co/600x400/059669/FFFFFF?text=Watch',
                        'rating'         => 4.7,
                        'review_count'   => 156,
                        'tags'           => ['Electronics', 'Fitness', 'Health', 'Smart'],
                        'stock_status'   => 'In Stock',
                        'features'       => ['GPS Tracking', 'Heart Rate Monitor', 'Sleep Analysis', 'Waterproof'],
                    ],
                ],
            ],
            'weather' => [
                'location'    => 'New York, NY',
                'api_key'     => 'demo_weather_api_key',
                'units'       => 'metric',
                'widget_type' => 'weather',
            ],
            'social_media' => [
                'sources'     => ['twitter', 'instagram'],
                'hashtags'    => ['#digitalsignage', '#technology'],
                'posts_count' => 10,
            ],
            'corporate' => [
                'title' => 'Corporate Updates',
                'items' => [
                    ['title' => 'Quarterly Results', 'content' => 'Strong performance across all divisions.'],
                    ['title' => 'New Partnership', 'content' => 'Exciting collaboration announced today.'],
                ],
            ],
            'restaurant' => [
                'menuData' => [
                    [
                        'name'        => 'Today\'s Specials',
                        'description' => 'Chef\'s recommendations',
                        'items'       => [
                            [
                                'name'        => 'Grilled Salmon',
                                'description' => 'Fresh Atlantic salmon with seasonal vegetables.',
                                'price'       => 24.99,
                                'calories'    => 420,
                                'allergens'   => ['fish'],
                                'image'       => 'salmon.jpg',
                                'special'     => true,
                            ],
                        ],
                    ],
                ],
            ],
            default => [
                'title'   => 'Welcome',
                'message' => 'Thank you for visiting.',
            ],
        };
    }

    private function createSpecialContent($templates, $screens): void
    {
        // Create a weather widget content
        Content::create([
            'name'        => 'Weather Widget',
            'description' => 'Live weather information display',
            'type'        => ContentType::WEATHER,
            'status'      => ContentStatus::ACTIVE,
            'template_id' => $templates->where('category', 'weather')->first()?->id ?? $templates->first()->id,
            'duration'    => 30,
            'data'        => [
                'widget_type' => 'weather',
                'location'    => 'New York, NY',
                'api_key'     => 'demo_key',
                'units'       => 'metric',
            ],
            'settings' => [
                'refresh_interval' => 900, // 15 minutes
                'transition'       => 'fade',
            ],
        ]);

        // Create a social media feed content
        Content::create([
            'name'        => 'Social Media Wall',
            'description' => 'Latest social media updates',
            'type'        => ContentType::SOCIAL,
            'status'      => ContentStatus::ACTIVE,
            'template_id' => $templates->where('category', 'social_media')->first()?->id ?? $templates->first()->id,
            'duration'    => 45,
            'data'        => [
                'sources'     => ['twitter', 'instagram'],
                'hashtags'    => ['#digitalsignage', '#technology'],
                'posts_count' => 10,
            ],
            'settings' => [
                'refresh_interval' => 300, // 5 minutes
                'transition'       => 'slide',
            ],
        ]);

        // Create an emergency alert content (inactive)
        Content::create([
            'name'        => 'Emergency Alert Template',
            'description' => 'Emergency notification display',
            'type'        => ContentType::CUSTOM,
            'status'      => ContentStatus::INACTIVE,
            'template_id' => $templates->where('category', 'announcement')->first()?->id ?? $templates->first()->id,
            'duration'    => 0, // Continuous display
            'data'        => [
                'title'           => 'EMERGENCY ALERT',
                'message'         => 'This is a template for emergency notifications.',
                'severity'        => 'high',
                'action_required' => 'Follow emergency procedures.',
            ],
            'settings' => [
                'background_color' => '#ff0000',
                'text_color'       => '#ffffff',
                'flash_effect'     => true,
            ],
        ]);
    }
}

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
        });

        // Create some special content items
        $this->createSpecialContent($templates, $screens);
    }

    private function getContentData(string $category): array
    {
        return match ($category) {
            'menu' => [
                'title' => 'Daily Specials',
                'items' => [
                    ['name' => 'Soup of the Day', 'price' => '5.99', 'description' => 'Fresh homemade soup'],
                    ['name' => 'Chef\'s Special', 'price' => '15.99', 'description' => 'Ask your server'],
                    ['name' => 'Catch of the Day', 'price' => '24.99', 'description' => 'Fresh seafood'],
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
                'title'  => 'Today\'s Events',
                'events' => [
                    ['time' => '09:00', 'title' => 'Morning Meeting', 'location' => 'Room 101'],
                    ['time' => '14:00', 'title' => 'Team Workshop', 'location' => 'Conference Room A'],
                ],
            ],
            'retail' => [
                'title' => 'Grand Opening Sale!', // Example title
                'products' => [
                    [
                        'name' => 'Comfy Cotton T-Shirt',
                        'price' => '24.99',
                        'sale_price' => '19.99',
                        'image' => 'images/retail/tshirt_blue.jpg', // Example path
                        'description' => 'Soft, breathable 100% cotton t-shirt available in various colors.',
                        'promotion_badge' => '20% OFF'
                    ],
                    [
                        'name' => 'Wireless Noise-Cancelling Headphones',
                        'price' => '199.00',
                        'sale_price' => '149.00',
                        'image' => 'images/retail/headphones_noise_cancelling.jpg',
                        'description' => 'Immersive sound experience with active noise cancellation and 20-hour battery life.',
                        'promotion_badge' => 'SAVE $50'
                    ],
                    [
                        'name' => 'Smart Fitness Tracker Watch',
                        'price' => '120.00',
                        'sale_price' => null, // Not on sale
                        'image' => 'images/retail/fitness_tracker_watch.jpg',
                        'description' => 'Track your steps, heart rate, and sleep patterns. Multiple sport modes.',
                        'promotion_badge' => null
                    ],
                    [
                        'name' => 'Organic Blend Coffee Beans',
                        'price' => '18.50',
                        'sale_price' => '15.00',
                        'image' => 'images/retail/coffee_beans_organic.jpg',
                        'description' => 'Premium Arabica beans, ethically sourced and locally roasted.',
                        'promotion_badge' => 'SPECIAL OFFER'
                    ],
                    [
                        'name' => 'Yoga Mat Premium',
                        'price' => '45.00',
                        'sale_price' => null,
                        'image' => 'images/retail/yoga_mat_premium.jpg',
                        'description' => 'Eco-friendly, non-slip yoga mat for all your fitness needs.',
                        'promotion_badge' => 'NEW ARRIVAL'
                    ]
                ],
                'footer_promo_text' => 'All offers valid while supplies last. Visit us at City Center Mall!' // Example footer
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

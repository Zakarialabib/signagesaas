<?php

declare(strict_types=1);

namespace App\Services;

class PlaceholderDataService
{
    public static function getRetailProductPlaceholderData(): array
    {
        return [
            'widgetTitle' => 'Grand Opening Specials',
            'products'    => [
                [
                    'name'                => 'Smart Home Hub X1',
                    'price'               => '129.99',
                    'sale_price'          => '99.99',
                    'image'               => 'images/products/smart_hub_x1.png',
                    'description'         => 'Control your entire smart home with this intuitive and powerful hub. Voice assistant compatible.',
                    'promotion_badge'     => 'NEW!',
                    'discount_percentage' => '23',
                ],
                [
                    'name'                => 'Wireless Noise-Cancelling Headphones',
                    'price'               => '199.50',
                    'sale_price'          => '149.00',
                    'image'               => 'images/products/headphones_noise_cancelling.png',
                    'description'         => 'Immerse yourself in pure audio bliss with these premium noise-cancelling headphones. 30-hour battery life.',
                    'promotion_badge'     => '25% OFF',
                    'discount_percentage' => '25',
                ],
                [
                    'name'            => 'Organic Arabica Coffee Beans (1kg)',
                    'price'           => '25.00',
                    'sale_price'      => '', // No sale price for this one
                    'image'           => 'images/products/coffee_beans_arabica.png',
                    'description'     => 'Start your day right with our ethically sourced, freshly roasted organic Arabica coffee beans.',
                    'promotion_badge' => 'BESTSELLER',
                ],
                [
                    'name'                => 'Ultra-Slim 4K OLED TV (55-inch)',
                    'price'               => '1499.00',
                    'sale_price'          => '1199.00',
                    'image'               => 'images/products/oled_tv_55inch.png',
                    'description'         => 'Experience breathtaking visuals with perfect blacks and vibrant colors on this stunning 4K OLED TV.',
                    'promotion_badge'     => 'SAVE $300',
                    'discount_percentage' => '20',
                ],
                [
                    'name'            => 'Professional DSLR Camera Kit',
                    'price'           => '899.99',
                    'sale_price'      => '749.99',
                    'image'           => 'images/products/dslr_camera_kit.png',
                    'description'     => 'Capture life\'s moments in stunning detail with this comprehensive DSLR camera kit, including two lenses.',
                    'promotion_badge' => 'LIMITED STOCK',
                ],
                [
                    'name'            => 'Ergonomic Office Chair Pro',
                    'price'           => '349.00',
                    'sale_price'      => '',
                    'image'           => 'images/products/office_chair_pro.png',
                    'description'     => 'Work in comfort and style with our top-rated ergonomic office chair. Fully adjustable lumbar support.',
                    'promotion_badge' => 'STAFF PICK',
                ],
            ],
            'footerPromoText' => 'All offers valid while supplies last. Visit us in-store or online!',
        ];
    }

    public static function getCalendarPlaceholderData(): array
    {
        return [
            'widgetTitle' => 'Upcoming Events',
            'events'      => [
                [
                    'title'       => 'Team Meeting',
                    'date'        => now()->addDays(2)->format('Y-m-d'),
                    'time'        => '10:00 AM',
                    'description' => 'Weekly sync-up with the team.',
                    'location'    => 'Conference Room A',
                ],
                [
                    'title'       => 'Client Presentation',
                    'date'        => now()->addDays(5)->format('Y-m-d'),
                    'time'        => '02:00 PM',
                    'description' => 'Presenting Q3 results to key client.',
                    'location'    => 'Client Office',
                ],
                [
                    'title'       => 'Product Launch Party',
                    'date'        => now()->addDays(10)->format('Y-m-d'),
                    'time'        => '07:00 PM',
                    'description' => 'Celebrate the new product release!',
                    'location'    => 'Downtown Venue',
                ],
            ],
        ];
    }

    public static function getNewsPlaceholderData(): array
    {
        return [
            'widgetTitle' => 'Latest News',
            'articles'    => [
                [
                    'title'        => 'Breaking News: Market Hits Record High',
                    'description'  => 'The stock market reached an all-time high today amidst positive economic indicators.',
                    'source'       => 'News Network A',
                    'category'     => 'Business',
                    'published_at' => '2 hours ago',
                    'author'       => 'Jane Doe',
                    'image'        => '/images/placeholder-news1.jpg',
                    'url'          => '#',
                ],
                [
                    'title'        => 'Tech Giant Unveils New Gadget',
                    'description'  => 'A revolutionary new device was announced today, promising to change the way we interact with technology.',
                    'source'       => 'Tech Today',
                    'category'     => 'Technology',
                    'published_at' => '5 hours ago',
                    'author'       => 'John Smith',
                    'image'        => '/images/placeholder-news2.jpg',
                    'url'          => '#',
                ],
                [
                    'title'        => 'Sports Update: Local Team Wins Championship',
                    'description'  => 'The home team clinched the championship in a thrilling final match.',
                    'source'       => 'Sports Central',
                    'category'     => 'Sports',
                    'published_at' => '1 day ago',
                    'author'       => 'Alex Green',
                    'image'        => '/images/placeholder-news3.jpg',
                    'url'          => '#',
                ],
            ],
        ];
    }

    public static function getWeatherPlaceholderData(): array
    {
        return [
            'widgetTitle' => 'Weather Forecast',
            'location'    => 'New York, USA',
            'temperature' => '22°C',
            'condition'   => 'Partly Cloudy',
            'icon'        => 'wi-day-cloudy',
            'forecast'    => [
                ['day' => 'Mon', 'temp' => '20°C', 'icon' => 'wi-day-cloudy'],
                ['day' => 'Tue', 'temp' => '23°C', 'icon' => 'wi-day-sunny'],
                ['day' => 'Wed', 'temp' => '18°C', 'icon' => 'wi-showers'],
            ],
        ];
    }

    public static function getMenuPlaceholderData(): array
    {
        return [
            'widgetTitle' => 'Daily Specials',
            'menuItems'   => [
                [
                    'category' => 'Appetizers',
                    'items'    => [
                        ['name' => 'Spring Rolls', 'description' => 'Crispy vegetables with sweet chili sauce.', 'price' => '5.99'],
                        ['name' => 'Garlic Bread', 'description' => 'Toasted baguette with fresh garlic butter.', 'price' => '4.50'],
                    ],
                ],
                [
                    'category' => 'Main Courses',
                    'items'    => [
                        ['name' => 'Grilled Salmon', 'description' => 'Served with asparagus and lemon-dill sauce.', 'price' => '18.99'],
                        ['name' => 'Chicken Alfredo', 'description' => 'Creamy fettuccine with grilled chicken and parmesan.', 'price' => '15.75'],
                    ],
                ],
                [
                    'category' => 'Desserts',
                    'items'    => [
                        ['name' => 'Chocolate Lava Cake', 'description' => 'Warm chocolate cake with a molten center.', 'price' => '7.00'],
                        ['name' => 'New York Cheesecake', 'description' => 'Classic cheesecake with berry compote.', 'price' => '6.50'],
                    ],
                ],
            ],
        ];
    }

    public static function getSocialMediaPlaceholderData(): array
    {
        return [
            'widgetTitle' => 'Social Feed',
            'posts'       => [
                [
                    'platform'    => 'X',
                    'author'      => '@SignageSaaS',
                    'text'        => 'Excited to announce our new feature updates! Check them out on our website.',
                    'timestamp'   => '2 hours ago',
                    'profile_pic' => '/images/placeholder-profile.jpg',
                ],
                [
                    'platform'    => 'Instagram',
                    'author'      => '@SignageSaaS_Official',
                    'text'        => 'Behind the scenes at our latest photoshoot! #digitalsignage #innovation',
                    'timestamp'   => '1 day ago',
                    'profile_pic' => '/images/placeholder-profile.jpg',
                ],
            ],
        ];
    }
}
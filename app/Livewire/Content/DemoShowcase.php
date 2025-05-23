<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Enums\TemplateCategory;
use Livewire\Component;

final class DemoShowcase extends Component
{
    public function getInformationWidgets(): array
    {
        return [
            [
                'category' => TemplateCategory::WEATHER,
                'title' => 'Weather Widget',
                'description' => 'Real-time weather updates with customizable locations',
                'features' => [
                    'Live weather data',
                    'Multiple location support',
                    'Forecast display',
                    'Custom styling options',
                ],
                'gradient' => 'from-blue-500 to-cyan-400',
                'preview' => [
                    'temperature' => '24°C',
                    'condition' => 'Partly Cloudy',
                    'location' => 'New York, NY',
                    'forecast' => [
                        ['day' => 'Mon', 'temp' => '23°C', 'icon' => 'sun'],
                        ['day' => 'Tue', 'temp' => '25°C', 'icon' => 'cloud-sun'],
                        ['day' => 'Wed', 'temp' => '22°C', 'icon' => 'cloud'],
                    ],
                ],
            ],
            [
                'category' => TemplateCategory::CLOCK,
                'title' => 'Clock Widget',
                'description' => 'Dynamic time display with multiple format options',
                'features' => [
                    'Multiple time formats',
                    'Timezone support',
                    'Custom styling',
                    'Analog/digital options',
                ],
                'gradient' => 'from-blue-400 to-cyan-300',
                'preview' => [
                    'time' => '14:30',
                    'date' => 'Monday, March 18, 2024',
                    'timezone' => 'EST',
                    'styles' => [
                        'modern' => ['font' => 'font-mono', 'size' => 'text-6xl'],
                        'classic' => ['font' => 'font-serif', 'size' => 'text-5xl'],
                        'minimal' => ['font' => 'font-sans', 'size' => 'text-4xl'],
                    ],
                ],
            ],
            [
                'category' => TemplateCategory::RSS_FEED,
                'title' => 'RSS Feed Widget',
                'description' => 'Dynamic content aggregation from multiple sources',
                'features' => [
                    'Multiple feed support',
                    'Auto-rotation',
                    'Content filtering',
                    'Custom styling',
                ],
                'gradient' => 'from-blue-600 to-cyan-500',
                'preview' => [
                    'feeds' => [
                        [
                            'title' => 'Latest Technology News',
                            'items' => [
                                ['title' => 'AI Breakthrough in Medical Research', 'time' => '2 hours ago'],
                                ['title' => 'New Quantum Computing Milestone', 'time' => '4 hours ago'],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getSocialWidgets(): array
    {
        return [
            [
                'category' => TemplateCategory::SOCIAL_MEDIA,
                'title' => 'Social Media Wall',
                'description' => 'Aggregate and display social media content',
                'features' => [
                    'Multi-platform support',
                    'Real-time updates',
                    'Content moderation',
                    'Custom layouts',
                ],
                'gradient' => 'from-pink-500 to-rose-400',
                'preview' => [
                    'posts' => [
                        [
                            'platform' => 'twitter',
                            'author' => '@techcompany',
                            'content' => 'Excited to announce our latest product launch! 🚀',
                            'engagement' => ['likes' => 1234, 'shares' => 567],
                        ],
                        [
                            'platform' => 'instagram',
                            'author' => '@designstudio',
                            'image' => 'workspace.jpg',
                            'engagement' => ['likes' => 2345, 'comments' => 123],
                        ],
                    ],
                ],
            ],
            [
                'category' => TemplateCategory::NEWS,
                'title' => 'News Feed',
                'description' => 'Display latest news from various sources',
                'features' => [
                    'Multiple categories',
                    'Auto-rotation',
                    'Custom sources',
                    'Breaking news alerts',
                ],
                'gradient' => 'from-pink-400 to-rose-300',
                'preview' => [
                    'articles' => [
                        [
                            'headline' => 'Global Markets Show Strong Recovery',
                            'source' => 'Financial Times',
                            'time' => '35 minutes ago',
                            'category' => 'Business',
                        ],
                        [
                            'headline' => 'Breakthrough in Renewable Energy Storage',
                            'source' => 'Tech Daily',
                            'time' => '1 hour ago',
                            'category' => 'Technology',
                        ],
                    ],
                ],
            ],
            [
                'category' => TemplateCategory::ANNOUNCEMENTS,
                'title' => 'Announcements',
                'description' => 'Share important updates and notifications',
                'features' => [
                    'Scheduled posts',
                    'Priority levels',
                    'Rich text support',
                    'Alert system',
                ],
                'gradient' => 'from-pink-600 to-rose-500',
                'preview' => [
                    'announcements' => [
                        [
                            'title' => 'Office Closure Notice',
                            'content' => 'Building maintenance scheduled for Saturday',
                            'priority' => 'high',
                            'icon' => 'warning',
                        ],
                        [
                            'title' => 'New Team Member',
                            'content' => 'Please welcome Sarah to the Marketing team',
                            'priority' => 'normal',
                            'icon' => 'user',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getBusinessWidgets(): array
    {
        return [
            [
                'category' => TemplateCategory::MENU,
                'title' => 'Menu Board',
                'description' => 'Dynamic menu display with pricing and images',
                'features' => [
                    'Price updates',
                    'Category management',
                    'Special offers',
                    'Nutritional info',
                ],
                'gradient' => 'from-emerald-500 to-green-400',
                'preview' => [
                    'categories' => [
                        [
                            'name' => 'Starters',
                            'items' => [
                                [
                                    'name' => 'Caesar Salad',
                                    'price' => '$12.99',
                                    'description' => 'Fresh romaine, parmesan, croutons',
                                    'allergens' => ['dairy', 'gluten'],
                                    'calories' => '320 cal',
                                ],
                                [
                                    'name' => 'Bruschetta',
                                    'price' => '$9.99',
                                    'description' => 'Tomatoes, basil, garlic on toasted bread',
                                    'allergens' => ['gluten'],
                                    'calories' => '280 cal',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'category' => TemplateCategory::RETAIL,
                'title' => 'Retail Products',
                'description' => 'Showcase products with dynamic pricing',
                'features' => [
                    'Inventory sync',
                    'Price updates',
                    'Promotions',
                    'Product details',
                ],
                'gradient' => 'from-emerald-400 to-green-300',
                'preview' => [
                    'products' => [
                        [
                            'name' => 'Premium Wireless Headphones',
                            'price' => '$199.99',
                            'sale_price' => '$149.99',
                            'image' => 'headphones.jpg',
                            'status' => 'In Stock',
                            'rating' => 4.5,
                        ],
                        [
                            'name' => 'Smart Fitness Watch',
                            'price' => '$299.99',
                            'image' => 'watch.jpg',
                            'status' => 'Low Stock',
                            'rating' => 4.8,
                        ],
                    ],
                ],
            ],
            [
                'category' => TemplateCategory::CALENDAR,
                'title' => 'Calendar & Events',
                'description' => 'Display upcoming events and schedules',
                'features' => [
                    'Event management',
                    'Recurring events',
                    'Custom categories',
                    'Room booking',
                ],
                'gradient' => 'from-emerald-600 to-green-500',
                'preview' => [
                    'events' => [
                        [
                            'title' => 'Team Meeting',
                            'time' => '10:00 AM - 11:00 AM',
                            'location' => 'Conference Room A',
                            'category' => 'Meeting',
                            'attendees' => 8,
                        ],
                        [
                            'title' => 'Product Launch',
                            'time' => '2:00 PM - 4:00 PM',
                            'location' => 'Main Hall',
                            'category' => 'Event',
                            'attendees' => 50,
                        ],
                    ],
                    'upcoming' => [
                        ['date' => 'Mar 19', 'count' => 3],
                        ['date' => 'Mar 20', 'count' => 5],
                        ['date' => 'Mar 21', 'count' => 2],
                    ],
                ],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.content.demo-showcase', [
            'informationWidgets' => $this->getInformationWidgets(),
            'socialWidgets' => $this->getSocialWidgets(),
            'businessWidgets' => $this->getBusinessWidgets(),
        ]);
    }
} 
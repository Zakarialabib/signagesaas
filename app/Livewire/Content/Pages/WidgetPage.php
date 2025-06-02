<?php

declare(strict_types=1);

namespace App\Livewire\Content\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Enums\TemplateCategory;
use App\Tenant\Models\Template;
use App\Tenant\Models\Content;
use InvalidArgumentException;

#[Layout('layouts.tv')]
final class WidgetPage extends Component
{
    public TemplateCategory $category;
    public ?string $weatherApiKey = null;
    public ?string $weatherLocation = null;
    // You can add more properties here if other widgets need specific data passed from the page
    public string $rssFeedUrl = 'https://feeds.bbci.co.uk/news/world/rss.xml'; // Example default for RSS
    public int $rssItemCount = 5;

    public string $defaultAnnouncementTitle = 'Important Update';
    public string $defaultAnnouncementMessage = 'Please be advised of the new company policy effective next Monday. Details will be shared via email.';
    public string $defaultCustomText = 'Welcome to Our Digital Signage Display!';

    public bool $isWeatherWidget = false;
    public ?string $announcementText = null;
    public bool $isAnnouncementWidget = false;

    // Property to hold menu data
    public array $menuData = [];

    // Property to hold product data for RetailWidget
    public array $productData = [];

    // Property to hold event data for CalendarWidget
    public array $calendarEventData = [];

    // Template and content properties
    public ?Template $template = null;
    public ?Content $content = null;
    public array $widgetConfig = [];

    public function mount($category): void
    {
        if (is_string($category)) {
            $this->category = TemplateCategory::from($category);
        } elseif ($category instanceof TemplateCategory) {
            $this->category = $category;
        } else {
            throw new InvalidArgumentException('Invalid category type provided.');
        }

        // Attempt to load a Content item for the given category
        // We'll try to find content that has a template of the specified category.
        $this->content = Content::whereHas('template', function ($query) {
            $query->where('category', $this->category->value);
        })->first();

        if ($this->content && isset($this->content->data)) {
            // Populate widget-specific data from the loaded Content item
            match ($this->category) {
                TemplateCategory::MENU => $this->menuData = $this->content->data['menuData'] ?? $this->getDefaultMenuData(),
                TemplateCategory::RETAIL => $this->productData = $this->content->data['productData'] ?? $this->getDefaultProductData(),
                TemplateCategory::CALENDAR => $this->calendarEventData = $this->content->data['calendarEventData'] ?? $this->getDefaultCalendarEventData(),
                // Add other categories as needed
                default => null,
            };
        } else {
            // Fallback to default data if no content is found or data is missing
            $this->loadDefaultDataForCategory();
        }

        if ($this->category === TemplateCategory::WEATHER) {
            $this->isWeatherWidget = true;
            $this->weatherApiKey = $this->content->data['api_key'] ?? session('demo_weather_api_key', 'YOUR_FALLBACK_API_KEY');
            $this->weatherLocation = $this->content->data['location'] ?? session('demo_weather_location', 'London');
        }

        if ($this->category === TemplateCategory::ANNOUNCEMENT) {
            $this->isAnnouncementWidget = true;
            $this->defaultAnnouncementTitle = $this->content->data['title'] ?? $this->defaultAnnouncementTitle;
            $this->defaultAnnouncementMessage = $this->content->data['message'] ?? $this->defaultAnnouncementMessage;
        }

        // Ensure RSS feed data is set, either from content or default
        $this->rssFeedUrl = $this->content->data['feed_url'] ?? $this->rssFeedUrl;
        $this->rssItemCount = $this->content->data['item_count'] ?? $this->rssItemCount;

        // Ensure Custom Text data is set
        $this->defaultCustomText = $this->content->data['text'] ?? $this->defaultCustomText;

        $this->loadTemplateAndContentData();
    }

    private function loadDefaultDataForCategory(): void
    {
        match ($this->category) {
            TemplateCategory::MENU => $this->menuData = $this->getDefaultMenuData(),
            TemplateCategory::RETAIL => $this->productData = $this->getDefaultProductData(),
            TemplateCategory::CALENDAR => $this->calendarEventData = $this->getDefaultCalendarEventData(),
            // For other categories, ensure their specific default data is loaded if necessary
            default => null,
        };
    }

    private function getDefaultMenuData(): array
    {
        return [
            [
                'name'        => 'Breakfast Specials (Default)',
                'description' => 'Served until 11 AM',
                'items'       => [
                    [
                        'name'        => 'Classic Pancakes',
                        'description' => 'Fluffy pancakes served with syrup and butter.',
                        'price'       => 7.99,
                        'calories'    => 450,
                        'allergens'   => ['gluten', 'dairy', 'egg'],
                        'image'       => '',
                        'special'     => true,
                        'sub_heading' => 'Most Popular',
                    ],
                ],
            ],
        ];
    }

    private function getDefaultProductData(): array
    {
        return [
            [
                'id'             => 'prod_default_101',
                'name'           => 'Stylish Urban Backpack (Default)',
                'description'    => 'A sleek and durable backpack perfect for city commutes and short trips.',
                'price'          => 89.99,
                'original_price' => 119.99,
                'image'          => 'https://placehold.co/600x400/5A5A5A/FFFFFF?text=Backpack',
                'rating'         => 4.7,
                'review_count'   => 150,
                'tags'           => ['Accessories', 'Travel', 'Urban', 'Sale'],
                'stock_status'   => 'In Stock',
                'features'       => ['Water-resistant fabric', '15-inch Laptop Sleeve'],
            ],
        ];
    }

    private function getDefaultCalendarEventData(): array
    {
        return [
            [
                'id'          => 'event_default_001',
                'title'       => 'Team Meeting (Default)',
                'description' => 'Weekly team sync and project updates',
                'start_time'  => '09:00',
                'end_time'    => '10:00',
                'date'        => now()->format('Y-m-d'),
                'location'    => 'Conference Room A',
                'organizer'   => 'System', 
                'attendees'   => 5,
                'type'        => 'meeting',
                'priority'    => 'high',
                'color'       => '#3b82f6',
            ],
        ];
    }

    //     if ($this->category === TemplateCategory::MENU) {
    //         // Initialize with sample menu data
    //         $this->menuData = [
    //             [
    //                 'name'        => 'Breakfast Specials',
    //                 'description' => 'Served until 11 AM',
    //                 'items'       => [
    //                     [
    //                         'name'        => 'Classic Pancakes',
    //                         'description' => 'Fluffy pancakes served with syrup and butter.',
    //                         'price'       => 7.99,
    //                         'calories'    => 450,
    //                         'allergens'   => ['gluten', 'dairy', 'egg'],
    //                         'image'       => '', // Optional image path
    //                         'special'     => true,
    //                         'sub_heading' => 'Most Popular', // For modern-dark template
    //                     ],
    //                     [
    //                         'name'        => 'Avocado Toast',
    //                         'description' => 'Sourdough toast with fresh avocado and seasoning.',
    //                         'price'       => 9.50,
    //                         'calories'    => 320,
    //                         'allergens'   => ['gluten'],
    //                         'image'       => '',
    //                         'special'     => false,
    //                     ],
    //                 ],
    //             ],
    //             [
    //                 'name'        => 'Lunch Menu',
    //                 'description' => 'Available from 11 AM to 3 PM',
    //                 'items'       => [
    //                     [
    //                         'name'        => 'Club Sandwich',
    //                         'description' => 'Triple decker with turkey, bacon, lettuce, and tomato.',
    //                         'price'       => 12.75,
    //                         'calories'    => 750,
    //                         'allergens'   => ['gluten', 'dairy'],
    //                         'image'       => 'club_sandwich.jpg', // Example image
    //                         'special'     => false,
    //                     ],
    //                 ],
    //             ],
    //         ];
    //     }

    //     if ($this->category === TemplateCategory::RETAIL) {
    //         // Initialize with sample product data
    //         $this->productData = [
    //             [
    //                 'id'             => 'prod_101',
    //                 'name'           => 'Stylish Urban Backpack',
    //                 'description'    => 'A sleek and durable backpack perfect for city commutes and short trips. Features multiple compartments and a padded laptop sleeve.',
    //                 'price'          => 89.99,
    //                 'original_price' => 119.99,
    //                 'image'          => 'https://placehold.co/600x400/5A5A5A/FFFFFF?text=Backpack',
    //                 'rating'         => 4.7,
    //                 'review_count'   => 150,
    //                 'tags'           => ['Accessories', 'Travel', 'Urban', 'Sale'],
    //                 'stock_status'   => 'In Stock',
    //                 'features'       => ['Water-resistant fabric', '15-inch Laptop Sleeve', 'Anti-theft pocket', 'USB charging port'],
    //             ],
    //             [
    //                 'id'           => 'prod_102',
    //                 'name'         => 'Gourmet Espresso Maker',
    //                 'description'  => 'Craft barista-quality espresso at home with this advanced coffee machine. Rich crema and perfect temperature control.',
    //                 'price'        => 349.50,
    //                 'image'        => 'https://placehold.co/600x400/C0A080/FFFFFF?text=Espresso+Maker',
    //                 'rating'       => 4.9,
    //                 'review_count' => 95,
    //                 'tags'         => ['Kitchen', 'Coffee', 'Appliances', 'New'],
    //                 'stock_status' => 'In Stock',
    //                 'features'     => ['15-bar pressure pump', 'Milk frother included', 'Programmable shot volume', 'Easy to clean'],
    //             ],
    //             [
    //                 'id'           => 'prod_103',
    //                 'name'         => 'Ultra-Soft Cashmere Scarf',
    //                 'description'  => 'Wrap yourself in luxury with this 100% pure cashmere scarf. Incredibly soft, warm, and stylish for any occasion.',
    //                 'price'        => 120.00,
    //                 'image'        => 'https://placehold.co/600x400/A0D2DB/FFFFFF?text=Scarf',
    //                 'rating'       => 4.6,
    //                 'review_count' => 72,
    //                 'tags'         => ['Fashion', 'Accessories', 'Luxury', 'Winter'],
    //                 'stock_status' => 'Low Stock',
    //                 'features'     => ['100% Pure Cashmere', 'Hand-finished tassels', 'Multiple color options', 'Gift-boxed'],
    //             ],
    //             [
    //                 'id'             => 'prod_104',
    //                 'name'           => 'Professional Drone Kit',
    //                 'description'    => 'Capture stunning aerial footage with this high-performance drone. Features 4K camera, long flight time, and intelligent flight modes.',
    //                 'price'          => 799.00,
    //                 'original_price' => 899.00,
    //                 'image'          => 'https://placehold.co/600x400/3D4849/FFFFFF?text=Drone',
    //                 'rating'         => 4.8,
    //                 'review_count'   => 210,
    //                 'tags'           => ['Electronics', 'Gadgets', 'Photography', 'Outdoor', 'Sale'],
    //                 'stock_status'   => 'In Stock',
    //                 'features'       => ['4K UHD Camera', '30-min Flight Time', 'Obstacle Avoidance', 'Foldable Design', 'GPS Return-to-Home'],
    //             ],
    //         ];
    //     }

    //     if ($this->category === TemplateCategory::CALENDAR) {
    //         $today = \Carbon\Carbon::now(config('app.timezone', 'UTC'));
    //         $this->calendarEventData = [
    //             [
    //                 'id'          => 'cal_evt_001',
    //                 'title'       => 'Company Town Hall',
    //                 'start'       => $today->copy()->setHour(10)->setMinute(0)->setSecond(0)->toDateTimeString(),
    //                 'end'         => $today->copy()->setHour(11)->setMinute(30)->setSecond(0)->toDateTimeString(),
    //                 'description' => 'Join us for the quarterly company town hall. We\'ll cover recent achievements and upcoming goals.',
    //                 'location'    => 'Main Auditorium / Zoom',
    //                 'category'    => 'Company Event',
    //                 'color'       => 'blue',
    //                 'attendees'   => ['All Employees'],
    //                 'isFullDay'   => false,
    //             ],
    //             [
    //                 'id'          => 'cal_evt_002',
    //                 'title'       => 'New Feature Launch - Project Zeta',
    //                 'start'       => $today->copy()->addDays(3)->setHour(14)->setMinute(0)->toDateTimeString(),
    //                 'end'         => $today->copy()->addDays(3)->setHour(15)->setMinute(0)->toDateTimeString(),
    //                 'description' => 'Official launch of Project Zeta\'s new features. Demo and Q&A session.',
    //                 'location'    => 'Online Webinar',
    //                 'category'    => 'Product Launch',
    //                 'color'       => 'green',
    //                 'isFullDay'   => false,
    //             ],
    //             [
    //                 'id'          => 'cal_evt_003',
    //                 'title'       => 'Team Building Activity',
    //                 'start'       => $today->copy()->addDays(7)->startOfDay()->toDateTimeString(),
    //                 'end'         => $today->copy()->addDays(7)->endOfDay()->toDateTimeString(),
    //                 'description' => 'Annual team building retreat. More details to follow.',
    //                 'location'    => 'Outdoor Adventure Park',
    //                 'category'    => 'Team Event',
    //                 'color'       => 'amber',
    //                 'isFullDay'   => true,
    //             ],
    //         ];
    //     }

    /**
     * Load template and content data for the current category
     */
    private function loadTemplateAndContentData(): void
    {
        // Try to find a published template for this category
        $this->template = Template::where('category', $this->category)
            ->where('status', 'published')
            ->where('is_system', true)
            ->first();

        if ($this->template) {
            // Extract widget configurations from template layout
            $this->extractWidgetConfigurations();

            // Try to find content for this template
            $this->content = Content::where('template_id', $this->template->id)
                ->where('status', 'published')
                ->first();

            if ($this->content && !empty($this->content->content_data)) {
                $this->loadContentData($this->content->content_data);
            }
        }
    }

    /**
     * Extract widget configurations from template layout
     */
    private function extractWidgetConfigurations(): void
    {
        if (!$this->template || !isset($this->template->layout['zones'])) {
            return;
        }

        foreach ($this->template->layout['zones'] as $zone) {
            if (isset($zone['widget_config'])) {
                $this->widgetConfig = array_merge($this->widgetConfig, $zone['widget_config']);
            }

            // Extract weather configuration
            if (isset($zone['widget_type']) && $zone['widget_type'] === 'WeatherWidget') {
                $this->isWeatherWidget = true;
                $this->weatherApiKey = $zone['settings']['weather_api_key'] ?? 'YOUR_FALLBACK_API_KEY';
                $this->weatherLocation = $zone['settings']['weather_location'] ?? 'London';
            }
        }
    }

    /**
     * Load content data based on content type
     */
    private function loadContentData(array $contentData): void
    {
        switch ($this->category) {
            case TemplateCategory::MENU:
            case TemplateCategory::RESTAURANT:
                if (isset($contentData['menuData'])) {
                    $this->menuData = $contentData['menuData'];
                }
                break;

            case TemplateCategory::RETAIL:
                if (isset($contentData['productData'])) {
                    $this->productData = $contentData['productData'];
                }
                break;

            case TemplateCategory::CALENDAR:
                if (isset($contentData['calendarEventData'])) {
                    $this->calendarEventData = $contentData['calendarEventData'];
                }
                break;

            case TemplateCategory::WEATHER:
                if (isset($contentData['weatherApiKey'])) {
                    $this->weatherApiKey = $contentData['weatherApiKey'];
                }
                if (isset($contentData['weatherLocation'])) {
                    $this->weatherLocation = $contentData['weatherLocation'];
                }
                break;

            case TemplateCategory::RSS_FEED:
                if (isset($contentData['rssFeedUrl'])) {
                    $this->rssFeedUrl = $contentData['rssFeedUrl'];
                }
                if (isset($contentData['rssItemCount'])) {
                    $this->rssItemCount = $contentData['rssItemCount'];
                }
                break;

            case TemplateCategory::ANNOUNCEMENT:
                if (isset($contentData['announcementText'])) {
                    $this->announcementText = $contentData['announcementText'];
                }
                break;
        }
    }

    /**
     * Get widget configuration for a specific key
     */
    public function getWidgetConfig(string $key, $default = null)
    {
        return $this->widgetConfig[$key] ?? $default;
    }

    /**
     * Check if a widget feature is enabled
     */
    public function isWidgetFeatureEnabled(string $feature): bool
    {
        return (bool) ($this->widgetConfig[$feature] ?? false);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.pages.widget-page', [
            'category'                   => $this->category,
            'isWeatherWidget'            => $this->isWeatherWidget,
            'weatherApiKey'              => $this->weatherApiKey,
            'weatherLocation'            => $this->weatherLocation,
            'rssFeedUrl'                 => $this->rssFeedUrl, // Pass RSS related data
            'rssItemCount'               => $this->rssItemCount,
            'defaultAnnouncementTitle'   => $this->defaultAnnouncementTitle, // Pass Announcement related data
            'defaultAnnouncementMessage' => $this->defaultAnnouncementMessage,
            'defaultCustomText'          => $this->defaultCustomText, // Pass Custom Text related data
            'menuData'                   => $this->menuData, // Pass menu data to the view
            'productData'                => $this->productData, // Pass product data to the view
            'calendarEventData'          => $this->calendarEventData, // Pass calendar event data
            'template'                   => $this->template, // Pass template data
            'content'                    => $this->content, // Pass content data
            'widgetConfig'               => $this->widgetConfig, // Pass widget configuration
        ]);
    }
}

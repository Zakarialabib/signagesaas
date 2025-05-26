<?php

declare(strict_types=1);

namespace App\Livewire\Content\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Enums\TemplateCategory;

#[Layout('layouts.tv')]
final class WidgetPage extends Component
{
    public TemplateCategory $category;
    public ?string $weatherApiKey = null;
    public ?string $weatherLocation = null;
    // You can add more properties here if other widgets need specific data passed from the page
    public string $rssFeedUrl = 'https://feeds.bbci.co.uk/news/world/rss.xml'; // Example default for RSS
    public int $rssItemCount = 5;

    public string $defaultAnnouncementTitle = "Important Update";
    public string $defaultAnnouncementMessage = "Please be advised of the new company policy effective next Monday. Details will be shared via email.";
    public string $defaultCustomText = "Welcome to Our Digital Signage Display!";
  
    public bool $isWeatherWidget = false;
    public ?string $announcementText = null;
    public bool $isAnnouncementWidget = false;
    
    // Property to hold menu data
    public array $menuData = [];

    // Property to hold product data for RetailWidget
    public array $productData = [];

    // Property to hold event data for CalendarWidget
    public array $calendarEventData = [];

    public function mount($category): void
    {
        if (is_string($category)) {
            $this->category = TemplateCategory::from($category);
        } elseif ($category instanceof TemplateCategory) {
            $this->category = $category;
        } else {
            // Handle error or default case if $category is not a string or TemplateCategory instance
            // For example, throw an exception or set a default category
            throw new \InvalidArgumentException('Invalid category type provided.');
        }
    
        // if ($this->category === TemplateCategory::WEATHER) {
        //     $this->isWeatherWidget = true;
        //     $this->weatherApiKey = session('demo_weather_api_key', 'YOUR_FALLBACK_API_KEY');
        //     $this->weatherLocation = session('demo_weather_location', 'London');
        // }
    
        if ($this->category === TemplateCategory::ANNOUNCEMENT) {
            $this->isAnnouncementWidget = true;
            $this->announcementText = session('demo_announcement_text', 'Welcome to our Digital Signage!');
        }

        if ($this->category === TemplateCategory::WEATHER) {
            $this->isWeatherWidget = true;
            // Find a template (e.g., a default "single weather widget" template for the tenant)
            // This logic needs to be defined: How do we pick THE template to show for this category?
            // For simplicity, let's assume we have a way to get a relevant $template object.
            // $template = Template::where('category', TemplateCategory::WEATHER)
            //                    ->where('is_default_for_category', true) // Hypothetical
            //                    ->first(); 

            // For demo, let's assume $template is loaded.
            // You'll need to implement actual template loading logic.
            // If a $template is loaded:
            // foreach ($template->layout['zones'] ?? [] as $zone) {
            //     // Check if this zone is configured as a weather widget
            //     // This check needs to match how you define it in TemplateConfigurator
            //     $isConfiguredAsWeather = ($zone['settings']['widget_type'] ?? null) === TemplateCategory::WEATHER->value || 
            //                              ($zone['type'] ?? null) === \App\Enums\ContentType::WEATHER->value;

            //     if ($isConfiguredAsWeather) {
            //         $this->weatherApiKey = $zone['settings']['weather_api_key'] ?? null;
            //         $this->weatherLocation = $zone['settings']['weather_location'] ?? null;
            //         break; // Found the first weather zone
            //     }
            // }

            // --- SIMPLIFIED DEMO FOR NOW ---
            // Manually set for demo until template loading logic is solid
            // In a real scenario, these would come from the $template's zone settings
            $this->weatherApiKey = session('demo_weather_api_key', 'YOUR_FALLBACK_API_KEY');
            $this->weatherLocation = session('demo_weather_location', 'London');
            // You'd need to ensure the TemplateConfigurator actually saves 'widget_type'
            // for the zone to be identified here.
        }

        if ($this->category === TemplateCategory::MENU) {
            // Initialize with sample menu data
            $this->menuData = [
                [
                    'name' => 'Breakfast Specials',
                    'description' => 'Served until 11 AM',
                    'items' => [
                        [
                            'name' => 'Classic Pancakes',
                            'description' => 'Fluffy pancakes served with syrup and butter.',
                            'price' => 7.99,
                            'calories' => 450,
                            'allergens' => ['gluten', 'dairy', 'egg'],
                            'image' => '', // Optional image path
                            'special' => true,
                            'sub_heading' => 'Most Popular' // For modern-dark template
                        ],
                        [
                            'name' => 'Avocado Toast',
                            'description' => 'Sourdough toast with fresh avocado and seasoning.',
                            'price' => 9.50,
                            'calories' => 320,
                            'allergens' => ['gluten'],
                            'image' => '',
                            'special' => false,
                        ],
                    ]
                ],
                [
                    'name' => 'Lunch Menu',
                    'description' => 'Available from 11 AM to 3 PM',
                    'items' => [
                        [
                            'name' => 'Club Sandwich',
                            'description' => 'Triple decker with turkey, bacon, lettuce, and tomato.',
                            'price' => 12.75,
                            'calories' => 750,
                            'allergens' => ['gluten', 'dairy'],
                            'image' => 'club_sandwich.jpg', // Example image
                            'special' => false,
                        ],
                    ]
                ],
            ];
        }

        if ($this->category === TemplateCategory::RETAIL) {
            // Initialize with sample product data
            $this->productData = [
                [
                    'id' => 'prod_101',
                    'name' => 'Stylish Urban Backpack',
                    'description' => 'A sleek and durable backpack perfect for city commutes and short trips. Features multiple compartments and a padded laptop sleeve.',
                    'price' => 89.99,
                    'original_price' => 119.99,
                    'image' => 'https://placehold.co/600x400/5A5A5A/FFFFFF?text=Backpack',
                    'rating' => 4.7,
                    'review_count' => 150,
                    'tags' => ['Accessories', 'Travel', 'Urban', 'Sale'],
                    'stock_status' => 'In Stock',
                    'features' => ['Water-resistant fabric', '15-inch Laptop Sleeve', 'Anti-theft pocket', 'USB charging port'],
                ],
                [
                    'id' => 'prod_102',
                    'name' => 'Gourmet Espresso Maker',
                    'description' => 'Craft barista-quality espresso at home with this advanced coffee machine. Rich crema and perfect temperature control.',
                    'price' => 349.50,
                    'image' => 'https://placehold.co/600x400/C0A080/FFFFFF?text=Espresso+Maker',
                    'rating' => 4.9,
                    'review_count' => 95,
                    'tags' => ['Kitchen', 'Coffee', 'Appliances', 'New'],
                    'stock_status' => 'In Stock',
                    'features' => ['15-bar pressure pump', 'Milk frother included', 'Programmable shot volume', 'Easy to clean'],
                ],
                [
                    'id' => 'prod_103',
                    'name' => 'Ultra-Soft Cashmere Scarf',
                    'description' => 'Wrap yourself in luxury with this 100% pure cashmere scarf. Incredibly soft, warm, and stylish for any occasion.',
                    'price' => 120.00,
                    'image' => 'https://placehold.co/600x400/A0D2DB/FFFFFF?text=Scarf',
                    'rating' => 4.6,
                    'review_count' => 72,
                    'tags' => ['Fashion', 'Accessories', 'Luxury', 'Winter'],
                    'stock_status' => 'Low Stock',
                    'features' => ['100% Pure Cashmere', 'Hand-finished tassels', 'Multiple color options', 'Gift-boxed'],
                ],
                [
                    'id' => 'prod_104',
                    'name' => 'Professional Drone Kit',
                    'description' => 'Capture stunning aerial footage with this high-performance drone. Features 4K camera, long flight time, and intelligent flight modes.',
                    'price' => 799.00,
                    'original_price' => 899.00,
                    'image' => 'https://placehold.co/600x400/3D4849/FFFFFF?text=Drone',
                    'rating' => 4.8,
                    'review_count' => 210,
                    'tags' => ['Electronics', 'Gadgets', 'Photography', 'Outdoor', 'Sale'],
                    'stock_status' => 'In Stock',
                    'features' => ['4K UHD Camera', '30-min Flight Time', 'Obstacle Avoidance', 'Foldable Design', 'GPS Return-to-Home'],
                ]
            ];
        }

        if ($this->category === TemplateCategory::CALENDAR) {
            $today = \Carbon\Carbon::now(config('app.timezone', 'UTC'));
            $this->calendarEventData = [
                [
                    'id' => 'cal_evt_001',
                    'title' => 'Company Town Hall',
                    'start' => $today->copy()->setHour(10)->setMinute(0)->setSecond(0)->toDateTimeString(),
                    'end' => $today->copy()->setHour(11)->setMinute(30)->setSecond(0)->toDateTimeString(),
                    'description' => 'Join us for the quarterly company town hall. We\'ll cover recent achievements and upcoming goals.',
                    'location' => 'Main Auditorium / Zoom',
                    'category' => 'Company Event',
                    'color' => 'blue',
                    'attendees' => ['All Employees'],
                    'isFullDay' => false,
                ],
                [
                    'id' => 'cal_evt_002',
                    'title' => 'New Feature Launch - Project Zeta',
                    'start' => $today->copy()->addDays(3)->setHour(14)->setMinute(0)->toDateTimeString(),
                    'end' => $today->copy()->addDays(3)->setHour(15)->setMinute(0)->toDateTimeString(),
                    'description' => 'Official launch of Project Zeta\'s new features. Demo and Q&A session.',
                    'location' => 'Online Webinar',
                    'category' => 'Product Launch',
                    'color' => 'green',
                    'isFullDay' => false,
                ],
                [
                    'id' => 'cal_evt_003',
                    'title' => 'Team Building Activity',
                    'start' => $today->copy()->addDays(7)->startOfDay()->toDateTimeString(),
                    'end' => $today->copy()->addDays(7)->endOfDay()->toDateTimeString(),
                    'description' => 'Annual team building retreat. More details to follow.',
                    'location' => 'Outdoor Adventure Park',
                    'category' => 'Team Event',
                    'color' => 'amber',
                    'isFullDay' => true,
                ],
            ];
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.pages.widget-page', [
            'category' => $this->category,
            'isWeatherWidget' => $this->isWeatherWidget,
            'weatherApiKey' => $this->weatherApiKey,
            'weatherLocation' => $this->weatherLocation,
            'rssFeedUrl' => $this->rssFeedUrl, // Pass RSS related data
            'rssItemCount' => $this->rssItemCount,
            'defaultAnnouncementTitle' => $this->defaultAnnouncementTitle, // Pass Announcement related data
            'defaultAnnouncementMessage' => $this->defaultAnnouncementMessage,
            'defaultCustomText' => $this->defaultCustomText, // Pass Custom Text related data
            'menuData' => $this->menuData, // Pass menu data to the view
            'productData' => $this->productData, // Pass product data to the view
            'calendarEventData' => $this->calendarEventData, // Pass calendar event data
        ]);
    }
}

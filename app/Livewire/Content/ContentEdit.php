<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Tenant\Models\Content;
use App\Tenant\Models\Screen;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

final class ContentEdit extends Component
{
    use WithFileUploads;

    #[Locked]
    public ?Content $content = null;

    public bool $editContentModal = false;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:1000')]
    public ?string $description = null;

    #[Validate('required|string')]
    public string $type = 'image';

    #[Validate('required|uuid|exists:screens,id')]
    public string $screen_id = '';

    #[Validate('required|string|in:active,inactive')]
    public string $status = 'active';

    #[Validate('required|integer|min:5|max:300')]
    public int $duration = 10;

    #[Validate('nullable|integer|min:0')]
    public ?int $order = 0;

    #[Validate('nullable|date')]
    public ?string $start_date = null;

    #[Validate('nullable|date|after_or_equal:start_date')]
    public ?string $end_date = null;

    #[Validate('nullable|image|max:10240')] // 10MB max
    public $image_file = null;

    #[Validate('nullable|string|url|max:2048')]
    public ?string $url = null;

    #[Validate('nullable|string')]
    public ?string $html_content = null;

    #[Validate('nullable|string|url|max:2048')]
    public ?string $feed_url = null; // For RSS

    #[Validate('nullable|string|max:255')]
    public ?string $location = null; // For Weather

    #[Validate('nullable|string|max:255')]
    public ?string $platform = null; // For Social

    #[Validate('nullable|string|max:255')]
    public ?string $handle = null; // For Social

    #[Validate('nullable|string|url|max:2048')]
    public ?string $calendar_url = null; // For Calendar

    #[Validate('nullable|string')]
    public ?string $custom_html = null;

    #[Validate('nullable|array')]
    public ?array $settings = null;

    #[Locked]
    public string $screenId = '';

    public bool $showAdvancedSettings = false;
    public bool $showPreview = true;
    public array $widgetData = [];
    public array $previewData = [];

    // Widget-specific properties
    public array $retailProductSettings = [
        'currency' => 'USD',
        'show_prices' => true,
        'columns' => 3,
        'show_images' => true,
        'refresh_interval' => 30,
    ];
    
    public array $menuSettings = [
        'show_prices' => true,
        'show_descriptions' => true,
        'show_calories' => false,
        'show_allergens' => false,
        'template_style' => 'default',
        'refresh_interval' => 60,
    ];
    
    public array $weatherSettings = [
        'location' => '',
        'units' => 'metric',
        'show_forecast' => true,
        'refresh_interval' => 300,
    ];
    
    public array $newsSettings = [
        'source' => 'general',
        'category' => 'general',
        'max_articles' => 5,
        'refresh_interval' => 600,
    ];

    #[On('editContentModal')]
    public function openModal(string $id): void
    {
        $this->content = Content::with('screen')->findOrFail($id);
        $this->authorize('update', $this->content);

        // $this->rules['type'] = 'required|string|in:'.implode(',', ContentType::values());
        $this->loadContentData();
        $this->editContentModal = true;
    }

    private function loadContentData(): void
    {
        if (!$this->content) {
            return;
        }

        $this->screen_id = $this->content->screen_id;
        $this->name = $this->content->name;
        $this->description = $this->content->description;
        $this->type = $this->content->type->value;
        $this->status = $this->content->status->value;
        $this->duration = $this->content->duration;
        $this->order = $this->content->order;
        $this->start_date = $this->content->start_date ? $this->content->start_date->format('Y-m-d') : null;
        $this->end_date = $this->content->end_date ? $this->content->end_date->format('Y-m-d') : null;
        
        // Load widget data if present
        if ($this->content->data && isset($this->content->data['widget_type'])) {
            $this->widgetData = $this->content->data;
            $this->loadWidgetSettings();
        }

        $this->settings = $this->content->settings;
        $this->updatePreviewData();

        // Reset other fields
        $this->image_file = null;
        $this->url = null;
        $this->html_content = null;
        $this->feed_url = null;
        $this->location = null;
        $this->platform = null;
        $this->handle = null;
        $this->calendar_url = null;
        $this->custom_html = null;

        // Load type-specific data
        if ($this->type !== ContentType::PRODUCT_LIST->value && $this->type !== ContentType::MENU->value) {
            $data = $this->content->data ?? [];
            switch ($this->type) {
                case ContentType::IMAGE->value:
                    $this->url = $data['url'] ?? null;
                    break;
                case ContentType::VIDEO->value:
                case ContentType::URL->value:
                    $this->url = $data['url'] ?? null;
                    break;
                case ContentType::HTML->value:
                case ContentType::CUSTOM->value:
                    $this->html_content = $data['html'] ?? null;
                    break;
                case ContentType::RSS->value:
                    $this->feed_url = $data['feed_url'] ?? null;
                    break;
                case ContentType::WEATHER->value:
                    $this->location = $data['location'] ?? null;
                    break;
                case ContentType::SOCIAL->value:
                    $this->platform = $data['platform'] ?? null;
                    $this->handle = $data['handle'] ?? null;
                    break;
                case ContentType::CALENDAR->value:
                    $this->calendar_url = $data['calendar_url'] ?? null;
                    break;
            }
        }
    }

    private function loadWidgetSettings(): void
    {
        if (!isset($this->widgetData['data'])) {
            return;
        }

        $data = $this->widgetData['data'];

        switch ($this->widgetData['widget_type']) {
            case 'retail_product':
                $this->retailProductSettings = array_merge($this->retailProductSettings, [
                    'currency' => $data['currency'] ?? 'USD',
                    'show_prices' => $data['show_prices'] ?? true,
                    'columns' => $data['columns'] ?? 3,
                    'show_images' => $data['show_images'] ?? true,
                    'refresh_interval' => $data['refresh_interval'] ?? 30,
                ]);
                break;

            case 'menu':
                $this->menuSettings = array_merge($this->menuSettings, [
                    'show_prices' => $data['show_prices'] ?? true,
                    'show_descriptions' => $data['show_descriptions'] ?? true,
                    'show_calories' => $data['show_calories'] ?? false,
                    'show_allergens' => $data['show_allergens'] ?? false,
                    'template_style' => $data['template_style'] ?? 'default',
                    'refresh_interval' => $data['refresh_interval'] ?? 60,
                ]);
                break;

            case 'weather':
                $this->weatherSettings = array_merge($this->weatherSettings, [
                    'location' => $data['location'] ?? '',
                    'units' => $data['units'] ?? 'metric',
                    'show_forecast' => $data['show_forecast'] ?? true,
                    'refresh_interval' => $data['refresh_interval'] ?? 300,
                ]);
                break;

            case 'news':
                $this->newsSettings = array_merge($this->newsSettings, [
                    'source' => $data['source'] ?? 'general',
                    'category' => $data['category'] ?? 'general',
                    'max_articles' => $data['max_articles'] ?? 5,
                    'refresh_interval' => $data['refresh_interval'] ?? 600,
                ]);
                break;
        }
    }

    public function updatedRetailProductSettings()
    {
        $this->updateWidgetData();
    }

    public function updatedMenuSettings()
    {
        $this->updateWidgetData();
    }

    public function updatedWeatherSettings()
    {
        $this->updateWidgetData();
    }

    public function updatedNewsSettings()
    {
        $this->updateWidgetData();
    }

    private function updateWidgetData(): void
    {
        if (!isset($this->widgetData['data'])) {
            $this->widgetData['data'] = [];
        }

        switch ($this->widgetData['widget_type']) {
            case 'retail_product':
                $this->widgetData['data'] = array_merge(
                    $this->widgetData['data'],
                    $this->retailProductSettings
                );
                break;

            case 'menu':
                $this->widgetData['data'] = array_merge(
                    $this->widgetData['data'],
                    $this->menuSettings
                );
                break;

            case 'weather':
                $this->widgetData['data'] = array_merge(
                    $this->widgetData['data'],
                    $this->weatherSettings
                );
                break;

            case 'news':
                $this->widgetData['data'] = array_merge(
                    $this->widgetData['data'],
                    $this->newsSettings
                );
                break;
        }

        $this->updatePreviewData();
    }

    private function updatePreviewData(): void
    {
        if (!$this->showPreview) {
            $this->previewData = [];
            return;
        }

        // Initialize with widget-specific settings
        $this->previewData = ['settings' => $this->widgetData['data'] ?? []];

        // Add actual content data if available
        if ($this->content && $this->content->data) {
            $this->previewData = array_merge($this->previewData, $this->content->data);
        } else {
            // Add placeholder data
            $this->previewData = array_merge($this->previewData, $this->getPlaceholderData());
        }

        // Ensure essential keys exist
        if (isset($this->widgetData['widget_type'])) {
            match ($this->widgetData['widget_type']) {
                'retail_product' => $this->previewData['products'] ??= [],
                'menu' => $this->previewData['categories'] ??= [],
                'news' => $this->previewData['articles'] ??= [],
                'weather' => $this->previewData['current'] ??= [],
                default => null,
            };
        }
    }

    private function getPlaceholderData(): array
    {
        if (!isset($this->widgetData['widget_type'])) {
            return [];
        }

        return match($this->widgetData['widget_type']) {
            'retail_product' => [
                'products' => [
                    ['name' => 'Sample Product 1', 'price' => 29.99, 'original_price' => 35.00, 'image' => '/images/placeholder-product.jpg', 'description' => 'This is a great sample product.', 'stock_status' => 'in_stock', 'category' => 'Electronics'],
                    ['name' => 'Sample Product 2', 'price' => 39.99, 'image' => '/images/placeholder-product.jpg', 'description' => 'Another fantastic item for your collection.', 'stock_status' => 'out_of_stock', 'category' => 'Books'],
                    ['name' => 'Sample Product 3', 'price' => 19.99, 'image' => '/images/placeholder-product.jpg', 'description' => 'Affordable and high quality.', 'stock_status' => 'in_stock', 'category' => 'Home Goods'],
                ],
            ],
            'menu' => [
                'categories' => [
                    [
                        'name' => 'Appetizers',
                        'items' => [
                            ['name' => 'Caesar Salad', 'price' => 12.99, 'description' => 'Fresh romaine lettuce, parmesan, croutons, and Caesar dressing.', 'calories' => 450, 'allergens' => ['Dairy', 'Gluten']],
                            ['name' => 'Bruschetta', 'price' => 8.99, 'description' => 'Toasted baguette slices topped with fresh tomatoes, garlic, basil, and olive oil.', 'calories' => 300, 'allergens' => ['Gluten']],
                        ]
                    ],
                    [
                        'name' => 'Main Courses',
                        'items' => [
                            ['name' => 'Grilled Salmon', 'price' => 24.99, 'description' => 'Atlantic salmon fillet grilled to perfection, served with roasted vegetables.', 'calories' => 600, 'allergens' => ['Fish']],
                            ['name' => 'Ribeye Steak', 'price' => 32.99, 'description' => '12oz premium cut ribeye steak, cooked to your liking, with garlic mashed potatoes.', 'calories' => 850, 'allergens' => ['Dairy']],
                        ]
                    ],
                ],
            ],
            'news' => [
                'articles' => [
                    ['title' => 'Breaking News: Market Hits Record High', 'description' => 'The stock market reached an all-time high today amidst positive economic indicators.', 'source' => 'News Network A', 'category' => 'Business', 'published_at' => '2 hours ago', 'author' => 'Jane Doe', 'image' => '/images/placeholder-news1.jpg', 'url' => '#'],
                    ['title' => 'Tech Giant Unveils New Gadget', 'description' => 'A revolutionary new device was announced today, promising to change the way we interact with technology.', 'source' => 'Tech Today', 'category' => 'Technology', 'published_at' => '5 hours ago', 'author' => 'John Smith', 'image' => '/images/placeholder-news2.jpg', 'url' => '#'],
                    ['title' => 'Sports Update: Local Team Wins Championship', 'description' => 'The home team clinched the championship in a thrilling final match.', 'source' => 'Sports Central', 'category' => 'Sports', 'published_at' => '1 day ago', 'author' => 'Alex Green', 'image' => '/images/placeholder-news3.jpg', 'url' => '#'],
                ],
            ],
            'weather' => [
                'current' => [
                    'temperature' => 25,
                    'description' => 'Sunny',
                    'icon' => '☀️',
                    'feels_like' => 26,
                    'humidity' => 60,
                    'wind_speed' => 10,
                    'visibility' => 15,
                ],
                'forecast' => [
                    ['day' => 'Mon', 'icon' => '☀️', 'high' => 28, 'low' => 18],
                    ['day' => 'Tue', 'icon' => '⛅️', 'high' => 26, 'low' => 17],
                    ['day' => 'Wed', 'icon' => '🌦️', 'high' => 24, 'low' => 16],
                    ['day' => 'Thu', 'icon' => '☁️', 'high' => 23, 'low' => 15],
                    ['day' => 'Fri', 'icon' => '☀️', 'high' => 27, 'low' => 19],
                ],
                'last_updated' => '10 minutes ago',
            ],
            default => [],
        };
    }

    public function togglePreview(): void
    {
        $this->showPreview = !$this->showPreview;
        $this->updatePreviewData();
    }

    public function render()
    {
        return view('livewire.content.content-edit', [
            'contentTypes' => ContentType::options(),
            'statuses'     => ContentStatus::options(),
            'screens'      => Screen::where('status', 'active')
                ->with('device')
                ->get(),
            'templateStyleOptions' => [
                'default' => 'Default',
                'modern' => 'Modern',
                'minimalist' => 'Minimalist',
                'elegant' => 'Elegant',
                'bold' => 'Bold',
            ],
        ]);
    }

    public function updateContent(): void
    {
        if (!$this->content) {
            return;
        }

        $this->authorize('update', $this->content);

        // $this->rules['type'] = 'required|string|in:'.implode(',', ContentType::values());
        $validated = $this->validate();

        $contentDataForUpdate = $this->content->data ?? [];

        // Handle widget data
        if (isset($this->widgetData['widget_type'])) {
            $contentDataForUpdate = $this->widgetData;
        } else {
            // Handle other content types
            switch ($validated['type']) {
                case ContentType::IMAGE->value:
                    if ($this->image_file) {
                        $path = $this->image_file->store('content/images', 'public');
                        $contentDataForUpdate['url'] = Storage::url($path);
                        $contentDataForUpdate['path'] = $path;
                    }
                    break;
                case ContentType::VIDEO->value:
                case ContentType::URL->value:
                    $contentDataForUpdate['url'] = $validated['url'] ?? $this->url;
                    break;
                case ContentType::HTML->value:
                case ContentType::CUSTOM->value:
                    $contentDataForUpdate['html'] = $validated['html_content'] ?? $this->html_content;
                    break;
                case ContentType::RSS->value:
                    $contentDataForUpdate['feed_url'] = $validated['feed_url'] ?? $this->feed_url;
                    break;
                case ContentType::WEATHER->value:
                    $contentDataForUpdate['location'] = $validated['location'] ?? $this->location;
                    break;
                case ContentType::SOCIAL->value:
                    $contentDataForUpdate['platform'] = $validated['platform'] ?? $this->platform;
                    $contentDataForUpdate['handle'] = $validated['handle'] ?? $this->handle;
                    break;
                case ContentType::CALENDAR->value:
                    $contentDataForUpdate['calendar_url'] = $validated['calendar_url'] ?? $this->calendar_url;
                    break;
            }
        }

        $this->content->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'screen_id' => $validated['screen_id'],
            'status' => $validated['status'],
            'duration' => $validated['duration'],
            'order' => $validated['order'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'data' => $contentDataForUpdate,
            'settings' => $this->settings,
        ]);

        $this->dispatch('content-updated');
        $this->closeModal();
    }

    private function resetFields(): void
    {
        $this->reset([
            'name', 'description', 'type', 'screen_id', 'status', 'duration', 'order',
            'start_date', 'end_date', 'image_file', 'url', 'html_content', 'feed_url',
            'location', 'platform', 'handle', 'calendar_url', 'custom_html', 'settings',
        ]);
        $this->content = null;
        $this->type = 'image';
    }

    public function closeModal(): void
    {
        $this->editContentModal = false;
        $this->resetFields();
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Tenant\Models\Content;
use App\Tenant\Models\Screen;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
final class ContentManager extends Component
{
    use WithPagination;

    #[Validate('nullable|string|max:255')]
    public ?string $search = null;

    #[Validate('nullable|string')]
    public string $statusFilter = 'all';

    #[Validate('nullable|string')]
    public string $typeFilter = 'all';

    #[Validate('nullable|string')]
    public string $screenFilter = 'all';

    #[Validate('nullable|string')]
    public string $sortField = 'created_at';

    #[Validate('nullable|string|in:asc,desc')]
    public string $sortDirection = 'desc';

    #[Locked]
    public ?Content $selectedContent = null;

    public bool $deleteContentModal = false;

    public bool $bulkActionModal = false;
    public bool $showWidgetTypeSelectorModal = false;

    #[Locked]
    public ?string $contentToDelete = null;

    #[Locked]
    public int $perPage = 10;

    public string $dateFilter = 'all';

    public array $selected = [];
    public string $bulkAction = '';
    public bool $selectAll = false;

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

    protected $listeners = [
        // 'close-widget-type-selector' => 'closeWidgetTypeSelectorModal', // Potentially add if needed
    ];

    public function openWidgetTypeSelector(): void
    {
        $this->showWidgetTypeSelectorModal = true;
    }

    // public function closeWidgetTypeSelectorModal(): void
    // {
    //     $this->showWidgetTypeSelectorModal = false;
    // }

    public function updatedType($value): void
    {
        if ($value === ContentType::PRODUCT_LIST->value) {
            $this->widgetData = [
                'widget_type' => 'retail_product',
                'data' => $this->retailProductSettings
            ];
        } elseif ($value === ContentType::MENU->value) {
            $this->widgetData = [
                'widget_type' => 'menu',
                'data' => $this->menuSettings
            ];
        } elseif ($value === ContentType::WEATHER->value) {
            $this->widgetData = [
                'widget_type' => 'weather',
                'data' => $this->weatherSettings
            ];
        } elseif ($value === ContentType::NEWS->value) {
            $this->widgetData = [
                'widget_type' => 'news',
                'data' => $this->newsSettings
            ];
        } else {
            $this->widgetData = [];
        }

        $this->updatePreviewData();
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

        // Add placeholder data
        $this->previewData = array_merge($this->previewData, $this->getPlaceholderData());

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
        $query = Content::query()
            ->with(['screen' => fn ($query) => $query->with('device')])
            ->when(
                $this->search,
                fn ($query) => $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas(
                        'screen',
                        fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                    )
            )
            ->when(
                $this->statusFilter !== 'all',
                fn ($query) => $query->where('status', $this->statusFilter)
            )
            ->when(
                $this->typeFilter !== 'all',
                fn ($query) => $query->where('type', $this->typeFilter)
            )
            ->when(
                $this->screenFilter !== 'all',
                fn ($query) => $query->where('screen_id', $this->screenFilter)
            )
            ->orderBy($this->sortField, $this->sortDirection);

        $contents = $query->paginate($this->perPage);

        // Handle select all functionality
        if ($this->selectAll) {
            $this->selected = $query->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        }

        return view('livewire.content.content-manager', [
            'contents'         => $contents,
            'contentTypes'     => ContentType::options(),
            'contentStatuses'  => ContentStatus::options(),
            'screens'          => $this->getScreensForFilter(),
            'hasSelectedItems' => count($this->selected) > 0,
            'selectedCount'    => count($this->selected),
            'templateStyleOptions' => [
                'default' => 'Default',
                'modern' => 'Modern',
                'minimalist' => 'Minimalist',
                'elegant' => 'Elegant',
                'bold' => 'Bold',
            ],
        ]);
    }

    #[Computed]
    public function getScreensForFilter()
    {
        return Screen::where('status', 'active')
            ->with('device')
            ->get()
            ->mapWithKeys(function ($screen) {
                return [$screen->id => $screen->name.' ('.$screen->device->name.')'];
            });
    }

    #[Computed]
    public function bulkActionOptions()
    {
        return [
            'activate'   => 'Activate Selected',
            'deactivate' => 'Deactivate Selected',
            'delete'     => 'Delete Selected',
        ];
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
        $this->resetPage();
    }

    public function refreshContents(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'typeFilter', 'screenFilter']);
        $this->resetPage();
    }

    #[On('confirm-delete-content')]
    public function confirmDeleteContent(string $id): void
    {
        $content = Content::findOrFail($id);
        $this->authorize('delete', $content);

        $this->contentToDelete = $id;
        $this->deleteContentModal = true;
    }

    #[On('content-created')]
    public function handleContentCreated(): void
    {
        $this->refreshContents();

        // Show confirmation message
        session()->flash('flash.banner', 'Content created successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }

    #[On('content-updated')]
    public function handleContentUpdated(): void
    {
        $this->refreshContents();

        // Show confirmation message
        session()->flash('flash.banner', 'Content updated successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }

    public function deleteContent(): void
    {
        if ( ! $this->contentToDelete) {
            return;
        }

        $content = Content::findOrFail($this->contentToDelete);
        $this->authorize('delete', $content);

        $content->delete();

        session()->flash('flash.banner', 'Content deleted successfully.');
        session()->flash('flash.bannerStyle', 'success');

        $this->refreshContents();
    }

    public function toggleSelectAll(): void
    {
        $this->selectAll = ! $this->selectAll;

        if ( ! $this->selectAll) {
            $this->selected = [];
        }
    }

    public function openBulkActionModal(): void
    {
        if (empty($this->selected)) {
            session()->flash('flash.banner', 'Please select at least one content item.');
            session()->flash('flash.bannerStyle', 'danger');

            return;
        }

        $this->bulkActionModal = true;
    }

    public function executeBulkAction(): void
    {
        if (empty($this->selected) || empty($this->bulkAction)) {
            return;
        }

        $contents = Content::whereIn('id', $this->selected)->get();

        switch ($this->bulkAction) {
            case 'activate':
                foreach ($contents as $content) {
                    if ($this->authorize('update', $content)) {
                        $content->status = ContentStatus::ACTIVE;
                        $content->save();
                    }
                }
                session()->flash('flash.banner', count($this->selected).' content items activated.');

                break;

            case 'deactivate':
                foreach ($contents as $content) {
                    if ($this->authorize('update', $content)) {
                        $content->status = ContentStatus::INACTIVE;
                        $content->save();
                    }
                }
                session()->flash('flash.banner', count($this->selected).' content items deactivated.');

                break;

            case 'delete':
                $deletedCount = 0;

                foreach ($contents as $content) {
                    if ($this->authorize('delete', $content)) {
                        $content->delete();
                        $deletedCount++;
                    }
                }
                session()->flash('flash.banner', $deletedCount.' content items deleted.');

                break;
        }

        session()->flash('flash.bannerStyle', 'success');
        $this->selected = [];
        $this->selectAll = false;
        $this->bulkAction = '';

        $this->refreshContents();
    }

    public function createContent(): void
    {
        // $this->rules['type'] = 'required|string|in:'.implode(',', ContentType::values());
        $validated = $this->validate();

        $contentData = [];

        // Handle widget data
        if (isset($this->widgetData['widget_type'])) {
            $contentData = $this->widgetData;
        } else {
            // Handle other content types
            switch ($validated['type']) {
                case ContentType::IMAGE->value:
                    if ($this->image_file) {
                        $path = $this->image_file->store('content/images', 'public');
                        $contentData['url'] = Storage::url($path);
                        $contentData['path'] = $path;
                    }
                    break;
                case ContentType::VIDEO->value:
                case ContentType::URL->value:
                    $contentData['url'] = $validated['url'] ?? $this->url;
                    break;
                case ContentType::HTML->value:
                case ContentType::CUSTOM->value:
                    $contentData['html'] = $validated['html_content'] ?? $this->html_content;
                    break;
                case ContentType::RSS->value:
                    $contentData['feed_url'] = $validated['feed_url'] ?? $this->feed_url;
                    break;
                case ContentType::WEATHER->value:
                    $contentData['location'] = $validated['location'] ?? $this->location;
                    break;
                case ContentType::SOCIAL->value:
                    $contentData['platform'] = $validated['platform'] ?? $this->platform;
                    $contentData['handle'] = $validated['handle'] ?? $this->handle;
                    break;
                case ContentType::CALENDAR->value:
                    $contentData['calendar_url'] = $validated['calendar_url'] ?? $this->calendar_url;
                    break;
            }
        }

        $content = Content::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'screen_id' => $validated['screen_id'],
            'status' => $validated['status'],
            'duration' => $validated['duration'],
            'order' => $validated['order'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'data' => $contentData,
            'settings' => $this->settings,
        ]);

        $this->dispatch('content-created', contentId: $content->id);
        $this->closeModal();
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
}

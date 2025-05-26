<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;

use App\Tenant\Models\Content;

final class MenuWidget extends BaseWidget
{
    #[Locked]
    public array $menu = []; // This will hold the categories and items

    #[Locked]
    public string $lastUpdated = ''; // Initialize to empty string

    #[Locked]
    public string $widgetTitle = 'Menu'; // Default title

    public ?string $contentId = null;

    // Configurable settings from template zone or screen settings
    public int $refreshInterval = 300; // 5 minutes
    public string $menuType = 'restaurant'; // Default, can be overridden by settings
    public bool $showPrices = true;
    public bool $showCalories = false; // Default to false for cleaner display
    public bool $showAllergens = false; // Default to false
    public string $currency = '$';

    // New properties for view/template management
    #[Locked]
    public array $availableViews = [
        'default' => [
            'name' => 'Classic View',
            'view_path' => 'livewire.content.widgets.menu-templates.default'
        ],
        'modernDark' => [
            'name' => 'Modern Dark',
            'view_path' => 'livewire.content.widgets.menu-templates.modern-dark'
        ],
        'minimalist' => [
            'name' => 'Minimalist',
            'view_path' => 'livewire.content.widgets.menu-templates.minimalist'
        ],
        'vintage' => [
            'name' => 'Vintage',
            'view_path' => 'livewire.content.widgets.menu-templates.vintage'
        ],
    ];
    public string $activeView = 'default'; // Default view key

    /**
     * Mount the component.
     *
     * @param array $settings Widget settings from the template zone or screen.
     * @param array $initialData Fallback data if contentId is not provided (original behavior).
     * @param string|null $contentId The ID of the Content model to load data from.
     * @param string $title Title for the widget from BaseWidget.
     * @param string $category Category for the widget from BaseWidget.
     * @param string $icon Icon for the widget from BaseWidget.
     */
    public function mount(
        array $settings = [],
        string $title = 'Menu Widget', // Default BaseWidget title
        string $category = 'MENU',    // Default BaseWidget category
        string $icon = 'heroicon-o-list-bullet', // Default BaseWidget icon
        array $initialData = [],      // Fallback data
        ?string $contentId = null
    ): void {
        parent::mount($settings, $title, $category, $icon); // Call BaseWidget's mount

        $this->contentId = $contentId;
        $this->activeView = $settings['default_view'] ?? 'default'; // Allow overriding default view from settings

        if ($this->contentId) {
            $contentModel = Content::find($this->contentId);
            if ($contentModel && isset($contentModel->content_data['widget_type']) && $contentModel->content_data['widget_type'] === 'MenuWidget') {
                $widgetDataSource = $contentModel->content_data['data'] ?? []; // Data is under 'data' key
                $this->menu = $widgetDataSource['categories'] ?? []; // Assuming 'categories' is the key for menu structure
                $this->widgetTitle = $widgetDataSource['title'] ?? $contentModel->name; // Use widget's specific title or content name
                $this->lastUpdated = $contentModel->updated_at?->diffForHumans() ?? now()->diffForHumans();
            } else {
                // Content not found or not a MenuWidget, load placeholder/default
                $this->loadPlaceholderData();
                $this->error = $this->error ?? "Content ID {$this->contentId} not found or not a MenuWidget.";
            }
        } elseif (!empty($initialData)) {
            // Fallback to initialData if contentId is not provided (e.g., preview during configuration)
            $this->menu = $initialData['categories'] ?? [];
            $this->widgetTitle = $initialData['title'] ?? 'Menu Preview';
            $this->lastUpdated = now()->diffForHumans();
            if (isset($initialData['active_view']) && array_key_exists($initialData['active_view'], $this->availableViews)) {
                $this->activeView = $initialData['active_view'];
            }
        } else {
            $this->loadPlaceholderData(); // Load placeholder if no data source
        }

        // Apply settings from template zone or screen settings
        $this->applySettings($settings);
    }

    protected function applySettings(array $settings): void
    {
        $this->menuType = $settings['menu_type'] ?? $this->menuType;
        $this->showPrices = $settings['show_prices'] ?? $this->showPrices;
        $this->showCalories = $settings['show_calories'] ?? $this->showCalories;
        $this->showAllergens = $settings['show_allergens'] ?? $this->showAllergens;
        $this->currency = $settings['currency'] ?? $this->currency;
        $this->refreshInterval = $settings['refresh_interval'] ?? $this->refreshInterval;
        // If BaseWidget's title wasn't overridden by content, use setting title
        if ($this->title === 'Menu Widget' && isset($settings['title'])) {
            $this->title = $settings['title']; // This is BaseWidget's title
        }
        // If widgetTitle (specific to this class) wasn't set by content, use setting title
        if ($this->widgetTitle === 'Menu' && isset($settings['widget_title'])) {
            $this->widgetTitle = $settings['widget_title'];
        }
        if (isset($settings['active_view']) && array_key_exists($settings['active_view'], $this->availableViews)) {
            $this->activeView = $settings['active_view'];
        }
    }

    /**
     * Load data for the widget.
     * This is called by BaseWidget's initialize method.
     * If contentId is set, data is already loaded in mount. This can be used for refresh logic.
     */
    protected function loadData(): void
    {
        if ($this->contentId) {
            $contentModel = Content::find($this->contentId);
            if ($contentModel && isset($contentModel->content_data['widget_type']) && $contentModel->content_data['widget_type'] === 'MenuWidget') {
                $widgetDataSource = $contentModel->content_data['data'] ?? [];
                $this->menu = $widgetDataSource['categories'] ?? [];
                $this->widgetTitle = $widgetDataSource['title'] ?? $contentModel->name;
                $this->lastUpdated = $contentModel->updated_at?->diffForHumans() ?? now()->diffForHumans();
            } else {
                // Handle error or set to empty state if content disappeared
                $this->menu = [];
                $this->widgetTitle = 'Menu Data Unavailable';
                $this->lastUpdated = now()->diffForHumans();
                $this->error = "Failed to refresh menu data for content ID {$this->contentId}.";
            }
        } else {
            // If no contentId, this implies it's either using initialData (already set in mount)
            // or should use its placeholder/demo data logic if it were defined for non-contentId scenarios.
            // For now, if no contentId, we assume data was passed via initialData or it's a placeholder state.
            // If we want refresh to work for placeholder data, we'd call loadPlaceholderData here.
            $this->loadPlaceholderData(); // Or decide if this should be an error state
        }
    }

    protected function loadPlaceholderData(): void
    {
        $this->menu = [
            [
                'name' => 'Appetizers',
                'description' => 'Start your meal with these delicious options',
                'items' => [
                    [
                        'name' => 'Bruschetta',
                        'description' => 'Toasted bread with tomatoes',
                        'price' => 8.99,
                        'calories' => 320,
                        'allergens' => ['gluten'],
                        'image' => '',
                        'special' => false
                    ],
                    [
                        'name' => 'Calamari',
                        'description' => 'Fried squid rings',
                        'price' => 12.99,
                        'calories' => 450,
                        'allergens' => ['shellfish', 'gluten'],
                        'image' => '',
                        'special' => true
                    ]
                ]
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Our signature dishes',
                'items' => [
                    [
                        'name' => 'Grilled Salmon',
                        'description' => 'Fresh salmon with seasonal vegetables',
                        'price' => 24.99,
                        'calories' => 650,
                        'allergens' => ['fish'],
                        'image' => '',
                        'special' => false
                    ],
                    [
                        'name' => 'Beef Tenderloin',
                        'description' => 'Tender beef with red wine sauce',
                        'price' => 29.99,
                        'calories' => 800,
                        'allergens' => ['gluten'],
                        'image' => '',
                        'special' => true
                    ]
                ]
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats to finish your meal',
                'items' => [
                    [
                        'name' => 'Chocolate Cake',
                        'description' => 'Rich chocolate cake with vanilla ice cream',
                        'price' => 7.99,
                        'calories' => 500,
                        'allergens' => ['gluten', 'dairy'],
                        'image' => '',
                        'special' => false
                    ],
                    [
                        'name' => 'Tiramisu',
                        'description' => 'Classic Italian dessert',
                        'price' => 8.99,
                        'calories' => 450,
                        'allergens' => ['gluten', 'dairy', 'eggs'],
                        'image' => '',
                        'special' => true
                    ]
                ]
            ]
        ];
        $this->widgetTitle = 'Sample Menu';
        $this->lastUpdated = now()->diffForHumans();
    }

    /**
     * Switch the active view template.
     */
    public function setView(string $viewKey): void
    {
        if (array_key_exists($viewKey, $this->availableViews)) {
            $this->activeView = $viewKey;
        }
    }

    /**
     * Toggle all display options (prices, calories, allergens)
     */
    public function toggleAllDisplayOptions(): void
    {
        $newValue = !($this->showPrices && $this->showCalories && $this->showAllergens);

        $this->showPrices = $newValue;
        $this->showCalories = $newValue;
        $this->showAllergens = $newValue;
    }

    /**
     * Reset refresh interval to default value
     */
    public function resetRefreshInterval(): void
    {
        $this->refreshInterval = 300; // Default 5 minutes
    }

    /**
     * Reload data manually
     */
    public function reloadData(): void
    {
        $this->loadData();
    }

    /**
     * Clear error message
     */
    public function clearError(): void
    {
        $this->error = null;
    }


    /**
     * Prepare data to be passed to the Blade views.
     */
    protected function getViewData(): array
    {
        return [
            'widgetId' => $this->getId(),
            'title' => $this->title, // BaseWidget title
            'category' => $this->category,
            'icon' => $this->icon,
            'widgetTitle' => $this->widgetTitle, // MenuWidget specific title
            'menu' => $this->menu,
            'lastUpdated' => $this->lastUpdated,
            'error' => $this->error,
            'isLoading' => $this->isLoading,
            'showPrices' => $this->showPrices,
            'showCalories' => $this->showCalories,
            'showAllergens' => $this->showAllergens,
            'currency' => $this->currency,
            // For the main wrapper view to manage tabs
            'availableViews' => $this->availableViews,
            'activeView' => $this->activeView,
        ];
    }

    public function render(): \Illuminate\View\View
    {
        // The main view will act as a container for tabs and the active template
        return view('livewire.content.widgets.menu-widget', $this->getViewData());
    }
}

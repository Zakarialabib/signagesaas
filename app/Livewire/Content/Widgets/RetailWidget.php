<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;
use App\Tenant\Models\Content;

 // Assuming you might load product content similarly to menu content

final class RetailWidget extends BaseWidget
{
    #[Locked]
    public array $products = []; // This will hold the product data

    #[Locked]
    public string $lastUpdated = '';

    #[Locked]
    public string $widgetTitle = 'Featured Products'; // Default title for the content within the widget

    public ?string $contentId = null; // For loading dynamic product sets from a Content model

    // Configurable settings
    public int $refreshInterval = 300; // 5 minutes, for potentially live stock/price updates
    public bool $showPrice = true;
    public bool $showAddToCartButton = false; // For display purposes in a signage context
    public string $currency = '$';
    public int $gridColumns = 3; // Default for grid views (e.g., 2, 3, 4)

    #[Locked]
    public array $availableViews = [
        'modern-grid' => [
            'name'      => 'Modern Grid',
            'view_path' => 'livewire.content.widgets.retail-templates.modern-grid',
        ],
        'detailed-list' => [
            'name'      => 'Detailed List',
            'view_path' => 'livewire.content.widgets.retail-templates.detailed-list',
        ],
        'minimalist-showcase' => [
            'name'      => 'Minimalist Showcase',
            'view_path' => 'livewire.content.widgets.retail-templates.minimalist-showcase',
        ],
    ];
    public string $activeView = 'modern-grid'; // Default view key

    public function mount(
        array $settings = [],
        string $title = 'Retail Widget', // BaseWidget title (widget card header)
        string $category = 'RETAIL', // BaseWidget category
        string $icon = 'heroicon-o-shopping-bag', // BaseWidget icon
        array $initialData = [],      // Fallback data for products
        ?string $contentId = null
    ): void {
        parent::mount($settings, $title, $category, $icon);

        $this->contentId = $contentId;
        $this->activeView = $settings['default_view'] ?? 'modern-grid';

        if ($this->contentId) {
            $contentModel = Content::find($this->contentId);

            // Assuming a similar structure to MenuWidget for loading data from a Content model
            // You'd adjust 'widget_type' and data keys (e.g., $contentModel->content_data['products'])
            if ($contentModel && isset($contentModel->content_data['widget_type']) && $contentModel->content_data['widget_type'] === 'RetailWidget') {
                $widgetDataSource = $contentModel->content_data['data'] ?? [];
                $this->products = $widgetDataSource['products'] ?? [];
                $this->widgetTitle = $widgetDataSource['title'] ?? $contentModel->name;
                $this->lastUpdated = $contentModel->updated_at?->diffForHumans() ?? now()->diffForHumans();
            } else {
                $this->loadPlaceholderData();
                $this->error ??= "Content ID {$this->contentId} not found or not a RetailWidget.";
            }
        } elseif ( ! empty($initialData['products'])) {
            $this->products = $initialData['products'] ?? [];
            $this->widgetTitle = $initialData['title'] ?? 'Featured Products';
            $this->lastUpdated = now()->diffForHumans();

            if (isset($initialData['active_view']) && array_key_exists($initialData['active_view'], $this->availableViews)) {
                $this->activeView = $initialData['active_view'];
            }
        } else {
            $this->loadPlaceholderData();
        }

        $this->applySettings($settings);
    }

    protected function applySettings(array $settings): void
    {
        $this->showPrice = $settings['show_price'] ?? $this->showPrice;
        $this->showAddToCartButton = $settings['show_add_to_cart_button'] ?? $this->showAddToCartButton;
        $this->currency = $settings['currency'] ?? $this->currency;
        $this->gridColumns = (int) ($settings['grid_columns'] ?? $this->gridColumns);
        $this->refreshInterval = (int) ($settings['refresh_interval'] ?? $this->refreshInterval);

        if ($this->title === 'Retail Widget' && isset($settings['title'])) {
            $this->title = $settings['title']; // BaseWidget's title
        }

        if ($this->widgetTitle === 'Featured Products' && isset($settings['widget_title'])) {
            $this->widgetTitle = $settings['widget_title']; // Title for content within the widget
        }

        if (isset($settings['active_view']) && array_key_exists($settings['active_view'], $this->availableViews)) {
            $this->activeView = $settings['active_view'];
        }
    }

    protected function loadData(): void
    {
        if ($this->contentId) {
            $contentModel = Content::find($this->contentId);

            if ($contentModel && isset($contentModel->content_data['widget_type']) && $contentModel->content_data['widget_type'] === 'RetailWidget') {
                $widgetDataSource = $contentModel->content_data['data'] ?? [];
                $this->products = $widgetDataSource['products'] ?? [];
                $this->widgetTitle = $widgetDataSource['title'] ?? $contentModel->name;
                $this->lastUpdated = $contentModel->updated_at?->diffForHumans() ?? now()->diffForHumans();
            } else {
                $this->products = [];
                $this->widgetTitle = 'Product Data Unavailable';
                $this->lastUpdated = now()->diffForHumans();
                $this->error = "Failed to refresh product data for content ID {$this->contentId}.";
            }
        } else {
            $this->loadPlaceholderData(); // Or apply sorting to existing placeholder data
        }
    }

    protected function loadPlaceholderData(): void
    {
        $this->products = [
            [
                'id'             => 'prod_001',
                'name'           => 'Premium Wireless Headphones',
                'description'    => 'Experience immersive sound with these comfortable, noise-cancelling headphones. Long battery life.',
                'price'          => 199.99,
                'original_price' => 249.99, // For showing discounts
                'image'          => 'https://placehold.co/600x400/purple/white?text=Headphones',
                'rating'         => 4.5,
                'review_count'   => 120,
                'tags'           => ['Audio', 'Electronics', 'Sale'],
                'stock_status'   => 'In Stock',
                'features'       => ['Noise Cancelling', '20-hour Battery', 'Bluetooth 5.0'],
            ],
            [
                'id'           => 'prod_002',
                'name'         => 'Smart Fitness Watch Series X',
                'description'  => 'Track your fitness goals, monitor your health, and stay connected. Waterproof design.',
                'price'        => 299.00,
                'image'        => 'https://placehold.co/600x400/teal/white?text=Smart+Watch',
                'rating'       => 4.8,
                'review_count' => 250,
                'tags'         => ['Wearable', 'Fitness', 'Tech'],
                'stock_status' => 'In Stock',
                'features'     => ['Heart Rate Monitor', 'GPS', 'Sleep Tracking', 'Waterproof'],
            ],
            [
                'id'           => 'prod_003',
                'name'         => 'Organic Blend Coffee Beans',
                'description'  => 'Start your day right with our ethically sourced, freshly roasted organic coffee beans.',
                'price'        => 22.50,
                'image'        => 'https://placehold.co/600x400/orange/white?text=Coffee+Beans',
                'rating'       => 4.9,
                'review_count' => 300,
                'tags'         => ['Coffee', 'Organic', 'Grocery'],
                'stock_status' => 'In Stock',
                'features'     => ['1kg Bag', 'Medium Roast', 'Fair Trade'],
            ],
            [
                'id'           => 'prod_004',
                'name'         => 'Portable Bluetooth Speaker',
                'description'  => 'Compact and powerful speaker for music on the go. Crystal clear sound and deep bass.',
                'price'        => 79.95,
                'image'        => 'https://placehold.co/600x400/blue/white?text=Speaker',
                'rating'       => 4.3,
                'review_count' => 95,
                'tags'         => ['Audio', 'Portable', 'Gadget'],
                'stock_status' => 'Low Stock',
                'features'     => ['10-hour Playtime', 'Water Resistant', 'Compact Design'],
            ],
        ];
        // Apply default sort to placeholder data if needed
        // $this->products = $this->sortProducts($this->products);
        $this->widgetTitle = 'Special Offers';
        $this->lastUpdated = now()->diffForHumans();
    }

    public function setView(string $viewKey): void
    {
        if (array_key_exists($viewKey, $this->availableViews)) {
            $this->activeView = $viewKey;
        }
    }

    protected function getViewData(): array
    {
        return [
            'widgetId'    => $this->getId(),
            'title'       => $this->title, // BaseWidget title
            'category'    => $this->category,
            'icon'        => $this->icon,
            'widgetTitle' => $this->widgetTitle,
            'products'    => $this->products, // Pass sorted products
            'lastUpdated' => $this->lastUpdated,
            'error'       => $this->error,
            'isLoading'   => $this->isLoading,
            // Retail specific settings
            'showPrice'           => $this->showPrice,
            'showAddToCartButton' => $this->showAddToCartButton,
            'currency'            => $this->currency,
            'gridColumns'         => $this->gridColumns,
            // For the main wrapper view to manage tabs
            'availableViews' => $this->availableViews,
            'activeView'     => $this->activeView,
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.retail-widget', $this->getViewData());
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;
use App\Tenant\Models\Content;

class RetailProductWidget extends BaseWidget
{
    // Data properties from content_data
    public string $widgetTitle = 'Featured Products';
    public array $products = [];
    public string $footerPromoText = '';
    public ?string $contentId = null;

    // Configurable settings with defaults
    public string $currencySymbol = '$';
    public string $defaultProductImage = 'images/placeholder_product.png';
    public int $gridColumns = 3;

    /**
     * Mount the component.
     *
     * @param array $settings Widget settings from the template zone or screen.
     * @param string $title Title for the widget (from BaseWidget perspective).
     * @param string $category Category for the widget (from BaseWidget perspective).
     * @param string $icon Icon for the widget (from BaseWidget perspective).
     * @param array $initialData Fallback data if contentId is not provided.
     * @param string|null $contentId The ID of the Content model to load data from.
     */
    public function mount(
        array $settings = [],
        string $title = 'Retail Product Showcase', // Default BaseWidget title
        string $category = 'RETAIL',              // Default BaseWidget category
        string $icon = 'heroicon-o-shopping-bag', // Default BaseWidget icon
        array $initialData = [],                  // Fallback data
        ?string $contentId = null
    ): void {
        parent::mount($settings, $title, $category, $icon); // Call BaseWidget's mount

        $this->contentId = $contentId;

        if ($this->contentId) {
            $contentModel = Content::find($this->contentId);
            if ($contentModel && isset($contentModel->content_data['widget_type']) && $contentModel->content_data['widget_type'] === 'RetailProductWidget') {
                $widgetDataSource = $contentModel->content_data['data'] ?? []; // Data is under 'data' key
                $this->widgetTitle = $widgetDataSource['title'] ?? $this->widgetTitle;
                $this->products = $widgetDataSource['products'] ?? [];
                $this->footerPromoText = $widgetDataSource['footer_promo_text'] ?? '';
            } else {
                $this->loadPlaceholderData();
                $this->error = $this->error ?? "Content ID {$this->contentId} not found or not a RetailProductWidget.";
            }
        } elseif (!empty($initialData)) {
            // Fallback to initialData if contentId is not provided
            $this->widgetTitle = $initialData['title'] ?? $this->widgetTitle;
            $this->products = $initialData['products'] ?? [];
            $this->footerPromoText = $initialData['footer_promo_text'] ?? '';
        } else {
             $this->loadPlaceholderData(); // Load placeholder if no data source
        }
        
        // Apply settings from template zone or screen settings
        $this->applySettings($settings);
    }

    protected function applySettings(array $settings): void
    {
        // Apply settings from the $settings array passed to mount
        $this->currencySymbol = $settings['currency_symbol'] ?? $this->currencySymbol;
        $this->defaultProductImage = $settings['default_product_image'] ?? $this->defaultProductImage;
        $this->gridColumns = $settings['grid_columns'] ?? $this->gridColumns;
        $this->refreshInterval = $settings['refresh_interval'] ?? $this->refreshInterval; // from BaseWidget
        
        // If BaseWidget's title wasn't overridden by content, use setting title
        if ($this->title === 'Retail Product Showcase' && isset($settings['title'])) {
             $this->title = $settings['title']; // This is BaseWidget's title
        }
        // If widgetTitle (specific to this class) wasn't set by content, use setting title
        if ($this->widgetTitle === 'Featured Products' && isset($settings['widget_title'])) {
            $this->widgetTitle = $settings['widget_title'];
        }
    }
    
    protected function loadPlaceholderData(): void
    {
        $this->widgetTitle = 'Sample Retail Showcase';
        $this->products = [
            [
                'name' => 'Sample Product 1',
                'price' => '29.99',
                'sale_price' => '19.99',
                'image' => $this->defaultProductImage,
                'description' => 'This is a sample product description.',
                'promotion_badge' => 'SALE'
            ],
        ];
        $this->footerPromoText = 'Sample promotion text here!';
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
            if ($contentModel && isset($contentModel->content_data['widget_type']) && $contentModel->content_data['widget_type'] === 'RetailProductWidget') {
                $widgetDataSource = $contentModel->content_data['data'] ?? [];
                $this->widgetTitle = $widgetDataSource['title'] ?? $this->widgetTitle;
                $this->products = $widgetDataSource['products'] ?? [];
                $this->footerPromoText = $widgetDataSource['footer_promo_text'] ?? '';
            } else {
                 // Handle error or set to empty state if content disappeared
                $this->products = [];
                $this->widgetTitle = 'Product Data Unavailable';
                $this->footerPromoText = '';
                $this->error = "Failed to refresh product data for content ID {$this->contentId}.";
            }
        } else {
            // If no contentId, implies using initialData (already set) or placeholder.
            // For refresh, we might reload placeholder or initial data if that's the desired behavior.
             $this->loadPlaceholderData();
        }
    }

    /**
     * Render the widget.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.retail-product-widget', [
            // Pass all public properties to the view
            'widgetTitle' => $this->widgetTitle,
            'products' => $this->products,
            'footerPromoText' => $this->footerPromoText,
            'currencySymbol' => $this->currencySymbol,
            'defaultProductImage' => $this->defaultProductImage,
            'gridColumns' => $this->gridColumns,
            'isLoading' => $this->isLoading, // From BaseWidget
            'error' => $this->error,         // From BaseWidget
        ]);
    }
}

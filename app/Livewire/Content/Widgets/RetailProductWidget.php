<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

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
        array $initialData = [],                  // Fallback data for non-preview, non-contentId scenarios
        ?string $contentId = null,
        ?array $previewContentData = null        // Added for preview functionality
    ): void {
        parent::mount($settings, $title, $category, $icon, $previewContentData); // Pass preview data to BaseWidget

        $this->contentId = $contentId;

        // Initial data loading if not preview and no contentId, or if previewContentData is NOT set.
        // If previewContentData is set, BaseWidget::initialize() -> loadData() will handle it.
        // If not preview, and contentId IS set, BaseWidget::initialize() -> loadData() will handle it.
        // This block now primarily handles $initialData if no contentId and no preview.
        if ($this->previewContentData === null && $this->contentId === null) {
            if (!empty($initialData)) {
                // Fallback to initialData if contentId is not provided and no preview
                $this->widgetTitle = $initialData['title'] ?? $this->widgetTitle;
                $this->products = $initialData['products'] ?? [];
                $this->footerPromoText = $initialData['footer_promo_text'] ?? '';
            } else {
                // If no preview, no contentId, and no initialData, then load placeholders.
                // This path will also be hit by loadData() if called without preview/contentId.
                $this->loadPlaceholderData();
            }
        }
        // Note: applySettings is called *after* data loading methods (initialize->loadData)
        // or after initialData population here.
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
        $this->widgetTitle = 'Grand Opening Specials';
        $this->products = [
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
        ];
        $this->footerPromoText = 'All offers valid while supplies last. Visit us in-store or online!';

        // Ensure defaultProductImage is used if a specific image is missing
        foreach ($this->products as &$product) {
            if (empty($product['image'])) {
                $product['image'] = $this->defaultProductImage;
            }
        }
    }

    /**
     * Load data for the widget.
     * This is called by BaseWidget's initialize method.
     * It now prioritizes previewContentData.
     */
    protected function loadData(): void
    {
        if ($this->previewContentData !== null) {
            // Populate from previewContentData
            $this->widgetTitle = $this->previewContentData['title'] ?? $this->widgetTitle;
            $this->products = $this->previewContentData['products'] ?? [];
            $this->footerPromoText = $this->previewContentData['footer_promo_text'] ?? '';
            // Ensure defaultProductImage is used if a specific image is missing in preview
            foreach ($this->products as &$product) {
                if (empty($product['image'])) {
                    $product['image'] = $this->defaultProductImage;
                }
            }
            // $this->isLoading is already false, $this->error is already null (set in BaseWidget)
            return;
        }

        if ($this->contentId) {
            $contentModel = Content::find($this->contentId);

            if ($contentModel && isset($contentModel->content_data['widget_type']) && $contentModel->content_data['widget_type'] === 'RetailProductWidget') {
                $widgetDataSource = $contentModel->content_data['data'] ?? [];
                $this->widgetTitle = $widgetDataSource['title'] ?? $this->widgetTitle;
                $this->products = $widgetDataSource['products'] ?? [];
                $this->footerPromoText = $widgetDataSource['footer_promo_text'] ?? '';
                // Ensure defaultProductImage is used if a specific image is missing
                foreach ($this->products as &$product) {
                    if (empty($product['image'])) {
                        $product['image'] = $this->defaultProductImage;
                    }
                }
            } else {
                // Handle error or set to empty state if content disappeared
                $this->loadPlaceholderData(); // Fallback to placeholder on error
                $this->error = $this->error ?? "Failed to load product data for content ID {$this->contentId}. Content not found or wrong type.";
            }
        } else {
            // If no contentId and no previewContentData, load placeholder data.
            // This also covers the case where initialData might have been expected but this is a refresh.
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
            'widgetTitle'         => $this->widgetTitle,
            'products'            => $this->products,
            'footerPromoText'     => $this->footerPromoText,
            'currencySymbol'      => $this->currencySymbol,
            'defaultProductImage' => $this->defaultProductImage,
            'gridColumns'         => $this->gridColumns,
            'isLoading'           => $this->isLoading, // From BaseWidget
            'error'               => $this->error,         // From BaseWidget
        ]);
    }
}

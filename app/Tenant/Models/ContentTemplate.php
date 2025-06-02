<?php

namespace App\Tenant\Models;

use App\Enums\ContentType;
use App\Enums\TemplateCategory;
use App\Enums\TemplateStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class ContentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'widget_type',
        'category',
        'status',
        'layout',
        'styles',
        'content_data',
        'settings',
        'thumbnail_url',
        'preview_url',
        'default_duration',
        'parent_id',
        'version',
        'is_default_version',
    ];

    protected $casts = [
        'layout' => 'array',
        'styles' => 'array',
        'content_data' => 'array',
        'settings' => 'array',
        'is_default_version' => 'boolean',
    ];

    protected $attributes = [
        'type' => 'template',
        'status' => 'draft',
        'version' => '1.0',
        'is_default_version' => true,
        'default_duration' => 10,
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ContentTemplate::class, 'parent_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ContentTemplate::class, 'parent_id');
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class, 'content_template', 'template_id', 'content_id')
                    ->withTimestamps();
    }

    public function screens(): BelongsToMany
    {
        return $this->belongsToMany(Screen::class, 'screen_template', 'template_id', 'screen_id')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeTemplates($query)
    {
        return $query->where('type', 'template');
    }

    public function scopeWidgets($query)
    {
        return $query->where('type', 'widget');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByWidgetType($query, $widgetType)
    {
        return $query->where('widget_type', $widgetType);
    }

    public function scopeDefaultVersions($query)
    {
        return $query->where('is_default_version', true);
    }

    // Accessors & Mutators
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : $this->getDefaultThumbnail(),
        );
    }

    protected function previewUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : null,
        );
    }

    // Helper Methods
    public function isTemplate(): bool
    {
        return $this->type === 'template';
    }

    public function isWidget(): bool
    {
        return $this->type === 'widget';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getDefaultThumbnail(): string
    {
        if ($this->isWidget()) {
            return match($this->widget_type) {
                'retail_product' => '/images/widgets/retail-product-thumb.svg',
                'menu' => '/images/widgets/menu-thumb.svg',
                'weather' => '/images/widgets/weather-thumb.svg',
                'clock' => '/images/widgets/clock-thumb.svg',
                'news' => '/images/widgets/news-thumb.svg',
                'social_media' => '/images/widgets/social-media-thumb.svg',
                'qr_code' => '/images/widgets/qr-code-thumb.svg',
                'calendar' => '/images/widgets/calendar-thumb.svg',
                'image_carousel' => '/images/widgets/image-carousel-thumb.svg',
                'video_player' => '/images/widgets/video-player-thumb.svg',
                'text_ticker' => '/images/widgets/text-ticker-thumb.svg',
                'analytics_chart' => '/images/widgets/analytics-chart-thumb.svg',
                default => '/images/widgets/default-widget-thumb.svg',
            };
        }

        return match($this->category) {
            'menu' => '/images/templates/menu-thumb.svg',
            'retail' => '/images/templates/retail-thumb.svg',
            'healthcare' => '/images/templates/healthcare-thumb.svg',
            'education' => '/images/templates/education-thumb.svg',
            'corporate' => '/images/templates/corporate-thumb.svg',
            default => '/images/templates/default-template-thumb.svg',
        };
    }

    public function getWidgetIcon(): string
    {
        return match($this->widget_type) {
            'retail_product' => 'shopping-bag',
            'menu' => 'clipboard-document-list',
            'weather' => 'cloud-sun',
            'clock' => 'clock',
            'news' => 'newspaper',
            'social_media' => 'share',
            'qr_code' => 'qr-code',
            'calendar' => 'calendar-days',
            'image_carousel' => 'photo',
            'video_player' => 'play-circle',
            'text_ticker' => 'chat-bubble-left-right',
            'analytics_chart' => 'chart-bar',
            default => 'squares-2x2',
        };
    }

    public function getWidgetTitle(): string
    {
        return match($this->widget_type) {
            'retail_product' => 'Retail Product Widget',
            'menu' => 'Menu Widget',
            'weather' => 'Weather Widget',
            'clock' => 'Clock Widget',
            'news' => 'News Widget',
            'social_media' => 'Social Media Widget',
            'qr_code' => 'QR Code Widget',
            'calendar' => 'Calendar Widget',
            'image_carousel' => 'Image Carousel Widget',
            'video_player' => 'Video Player Widget',
            'text_ticker' => 'Text Ticker Widget',
            'analytics_chart' => 'Analytics Chart Widget',
            default => 'Custom Widget',
        };
    }

    public function createVersion(array $data = []): self
    {
        $parentId = $this->parent_id ?? $this->id;
        $latestVersion = static::where('parent_id', $parentId)
            ->orderBy('version', 'desc')
            ->first();

        $newVersionNumber = $latestVersion 
            ? $this->incrementVersion($latestVersion->version)
            : '1.1';

        // Mark all other versions as non-default
        static::where('parent_id', $parentId)
            ->orWhere('id', $parentId)
            ->update(['is_default_version' => false]);

        return static::create(array_merge(
            $this->toArray(),
            $data,
            [
                'id' => null,
                'parent_id' => $parentId,
                'version' => $newVersionNumber,
                'is_default_version' => true,
                'created_at' => null,
                'updated_at' => null,
            ]
        ));
    }

    private function incrementVersion(string $version): string
    {
        $parts = explode('.', $version);
        $parts[1] = (int)$parts[1] + 1;
        return implode('.', $parts);
    }

    public function getContentPreviewData(): array
    {
        if (!$this->isWidget() || !$this->content_data) {
            return [];
        }

        return match($this->widget_type) {
            'retail_product' => $this->getRetailProductPreviewData(),
            'menu' => $this->getMenuPreviewData(),
            default => $this->content_data,
        };
    }

    private function getRetailProductPreviewData(): array
    {
        $contentData = $this->content_data;
        
        if (isset($contentData['content_id'])) {
            $content = Content::find($contentData['content_id']);
            if ($content && $content->data) {
                return array_merge($contentData, [
                    'products' => $content->data['products'] ?? [],
                ]);
            }
        }

        // Fallback to placeholder data
        return array_merge($contentData, [
            'products' => [
                ['name' => 'Sample Product 1', 'price' => 29.99, 'image' => '/images/placeholder-product.jpg'],
                ['name' => 'Sample Product 2', 'price' => 39.99, 'image' => '/images/placeholder-product.jpg'],
                ['name' => 'Sample Product 3', 'price' => 19.99, 'image' => '/images/placeholder-product.jpg'],
            ],
        ]);
    }

    private function getMenuPreviewData(): array
    {
        $contentData = $this->content_data;
        
        if (isset($contentData['content_id'])) {
            $content = Content::find($contentData['content_id']);
            if ($content && $content->data) {
                return array_merge($contentData, [
                    'categories' => $content->data['categories'] ?? [],
                ]);
            }
        }

        // Fallback to placeholder data
        return array_merge($contentData, [
            'categories' => [
                [
                    'name' => 'Appetizers',
                    'items' => [
                        ['name' => 'Caesar Salad', 'price' => 12.99, 'description' => 'Fresh romaine lettuce with parmesan'],
                        ['name' => 'Bruschetta', 'price' => 8.99, 'description' => 'Toasted bread with tomatoes and basil'],
                    ]
                ],
                [
                    'name' => 'Main Courses',
                    'items' => [
                        ['name' => 'Grilled Salmon', 'price' => 24.99, 'description' => 'Atlantic salmon with lemon butter'],
                        ['name' => 'Ribeye Steak', 'price' => 32.99, 'description' => 'Premium cut with garlic mashed potatoes'],
                    ]
                ],
            ],
        ]);
    }
}
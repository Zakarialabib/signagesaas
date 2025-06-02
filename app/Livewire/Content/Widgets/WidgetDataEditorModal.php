<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Models\Tenant; // Assuming Tenant model is in App\Models
use App\Services\OnboardingProgressService;
use App\Tenant\Models\Content;
use App\Tenant\Models\OnboardingProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Attributes\On;

class WidgetDataEditorModal extends Component
{
    public bool $showModal = false;
    public ?string $zoneId = null;
    public ?string $widgetType = null;
    public ?int $contentId = null; // Can be int or null
    public string $contentName = '';
    public array $widgetData = []; // This will store the ['data' => ..., 'widget_type' => ...] structure

    public string $currentView = 'edit'; // 'edit' or 'select'
    public $existingContents = [];
    public ?string $searchTerm = '';


    protected $listeners = [
        'openWidgetDataEditor' => 'handleOpenWidgetDataEditor',
    ];

    #[On('openWidgetDataEditor')]
    public function handleOpenWidgetDataEditor(?string $zoneId, string $widgetType, ?int $contentId = null): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->zoneId = $zoneId;
        $this->widgetType = $widgetType;
        $this->contentId = $contentId;
        $this->showModal = true;
        $this->contentName = ''; 
        $this->widgetData = [];  
        $this->searchTerm = '';
        $this->existingContents = [];

        if ($this->contentId) { // Editing existing content
            $content = Content::find($this->contentId);
            if ($content) {
                $this->contentName = $content->name;
                $this->widgetData = $content->content_data ?? []; // Expects ['widget_type' => ..., 'data' => ...]
            }
            $this->currentView = 'edit';
        } else { // Creating new content or assigning to zone
            $this->currentView = $this->zoneId ? 'select' : 'edit'; // Default to select if for a zone, else edit for direct creation
        }
        
        // Always load existing contents if a widgetType is specified, for the 'select' view
        if ($this->widgetType) {
            $this->loadExistingContents();
        }

        // Initialize widgetData for 'edit' view if it's empty (either new or problematic load)
        if ($this->currentView === 'edit' && (empty($this->widgetData) || empty($this->widgetData['data']))) {
            $this->initializeWidgetDataForEdit();
        }
    }

    public function initializeWidgetDataForEdit(): void
    {
        $initialData = match($this->widgetType) {
            'MenuWidget' => [
                'categories' => [],
                'settings' => [
                    'template_style' => 'grid',
                    'show_descriptions' => true,
                    'show_prices' => true,
                    'show_calories' => false,
                    'show_allergens' => false
                ]
            ],
            'RetailProductWidget' => [
                'title' => 'Featured Products',
                'products' => [],
                'settings' => [
                    'columns' => 2,
                    'show_images' => true,
                    'show_prices' => true,
                    'currency' => 'USD'
                ]
            ],
            'WeatherWidget' => [
                'location' => '',
                'settings' => [
                    'units' => 'metric',
                    'show_forecast' => true,
                    'refresh_interval' => 300
                ]
            ],
            'NewsWidget' => [
                'articles' => [],
                'settings' => [
                    'max_articles' => 5,
                    'source' => 'general',
                    'category' => 'general',
                    'refresh_interval' => 300
                ]
            ],
            default => []
        };
        
        $this->widgetData = [
            'widget_type' => $this->widgetType,
            'data' => $initialData
        ];
    }
    
    public function updatedSearchTerm(): void
    {
        $this->loadExistingContents();
    }

    private function loadExistingContents(): void
    {
        if (!$this->widgetType) return;

        $this->existingContents = Content::where('content_data->widget_type', $this->widgetType)
            ->when($this->searchTerm, function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('updated_at', 'desc')
            ->limit(50) // Prevent loading too much data
            ->get();
    }

    public function switchToEditView(): void
    {
        $this->currentView = 'edit';
        if (empty($this->widgetData) || empty($this->widgetData['data'])) {
            $this->initializeWidgetDataForEdit();
        }
    }

    public function switchToSelectView(): void
    {
        $this->currentView = 'select';
        $this->loadExistingContents(); // Ensure it's loaded if not already
    }

    public function selectExistingContent(int $selectedContentId): void
    {
        $this->contentId = $selectedContentId;
        // If a zoneId is present, we are assigning this existing content to the zone.
        if ($this->zoneId) {
            $this->dispatch('widgetContentSaved', zoneId: $this->zoneId, contentId: $this->contentId)
                 ->to('App.Livewire.Content.Templates.TemplateConfigurator');
            $this->closeModal();
        } else {
            // If no zoneId, it means we're just viewing/editing an existing content.
            // Load its data and switch to edit view.
            $content = Content::find($this->contentId);
            if ($content) {
                $this->contentName = $content->name;
                $this->widgetData = $content->content_data ?? [];
                $this->widgetType = $this->widgetData['widget_type'] ?? null; // Ensure widgetType is set from loaded content
            }
            $this->currentView = 'edit';
        }
    }

    public function save(): void
    {
        $validatedData = Validator::make(
            [
                'contentName' => $this->contentName,
                'widgetData' => $this->widgetData,
            ],
            [
                'contentName' => 'required|string|max:255',
                'widgetData' => 'required|array',
                'widgetData.widget_type' => 'required|string',
                'widgetData.data' => 'required|array',
            ]
        )->validate();

        $currentTenant = Auth::user()->tenant;
        if (!$currentTenant && class_exists(Tenant::class)) {
            $currentTenant = Tenant::current() ?? Tenant::first();
        }

        if (!$currentTenant) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Current tenant could not be determined.']);
            return;
        }

        $contentDataPrepared = $validatedData['widgetData'];
        
        // Ensure widget_type is set
        if (!isset($contentDataPrepared['widget_type'])) {
            $contentDataPrepared['widget_type'] = $this->widgetType;
        }

        // Ensure settings are present in the data
        if (!isset($contentDataPrepared['data']['settings'])) {
            $contentDataPrepared['data']['settings'] = [];
        }

        $contentDetails = [
            'tenant_id'    => $currentTenant->id,
            'name'         => $validatedData['contentName'],
            'type'         => ContentType::CUSTOM->value,
            'status'       => ContentStatus::ACTIVE->value,
            'content_data' => $contentDataPrepared,
            'template_id'  => null,
        ];

        if ($this->contentId) {
            $content = Content::find($this->contentId);
            if ($content) {
                $content->update($contentDetails);
            } else {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Content to update not found.']);
                return;
            }
        } else {
            $content = Content::create($contentDetails);

            // Mark onboarding progress
            if ($content) {
                $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $content->tenant_id]);
                if (!$onboardingProgress->first_widget_content_created) {
                    $onboardingProgress->markFirstWidgetContentCreatedCompleted();
                }
            }
        }

        // Dispatch to TemplateConfigurator
        $this->dispatch('widgetContentSaved', zoneId: $this->zoneId, contentId: $content->id)
            ->to('App.Livewire.Content.Templates.TemplateConfigurator');

        $this->closeModal();
    }

    public function addCategory(): void
    {
        if ($this->widgetType === 'MenuWidget') {
            if (!isset($this->widgetData['data']['categories']) || !is_array($this->widgetData['data']['categories'])) {
                $this->widgetData['data']['categories'] = [];
            }
            $this->widgetData['data']['categories'][] = ['name' => '', 'items' => []];
        }
    }

    public function removeCategory(int $categoryIndex): void
    {
        if ($this->widgetType === 'MenuWidget' && isset($this->widgetData['data']['categories'][$categoryIndex])) {
            unset($this->widgetData['data']['categories'][$categoryIndex]);
            $this->widgetData['data']['categories'] = array_values($this->widgetData['data']['categories']); // Re-index
        }
    }

    public function addItem(int $categoryIndex): void
    {
        if ($this->widgetType === 'MenuWidget' && isset($this->widgetData['data']['categories'][$categoryIndex])) {
            if (!isset($this->widgetData['data']['categories'][$categoryIndex]['items']) || !is_array($this->widgetData['data']['categories'][$categoryIndex]['items'])) {
                $this->widgetData['data']['categories'][$categoryIndex]['items'] = [];
            }
            $this->widgetData['data']['categories'][$categoryIndex]['items'][] = ['name' => '', 'price' => '', 'description' => '', 'calories' => ''];
        }
    }

    public function removeItem(int $categoryIndex, int $itemIndex): void
    {
        if ($this->widgetType === 'MenuWidget' && isset($this->widgetData['data']['categories'][$categoryIndex]['items'][$itemIndex])) {
            unset($this->widgetData['data']['categories'][$categoryIndex]['items'][$itemIndex]);
            $this->widgetData['data']['categories'][$categoryIndex]['items'] = array_values($this->widgetData['data']['categories'][$categoryIndex]['items']); // Re-index
        }
    }

    public function addProduct(): void
    {
        if ($this->widgetType === 'RetailProductWidget') {
            if (!isset($this->widgetData['data']['products']) || !is_array($this->widgetData['data']['products'])) {
                $this->widgetData['data']['products'] = [];
            }
            $this->widgetData['data']['products'][] = [
                'name' => '',
                'price' => '',
                'sale_price' => '',
                'image' => '', // Consider a default placeholder image path
                'description' => '',
                'promotion_badge' => ''
            ];
        }
    }

    public function removeProduct(int $productIndex): void
    {
        if ($this->widgetType === 'RetailProductWidget' && isset($this->widgetData['data']['products'][$productIndex])) {
            unset($this->widgetData['data']['products'][$productIndex]);
            $this->widgetData['data']['products'] = array_values($this->widgetData['data']['products']); // Re-index
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['zoneId', 'widgetType', 'contentId', 'contentName', 'widgetData']);
    }

    public function render()
    {
        // Ensure widgetData['data'] exists and has expected keys for reactive properties in Blade
        if ($this->widgetType === 'MenuWidget' && (!isset($this->widgetData['data']['categories']) || !is_array($this->widgetData['data']['categories']))) {
            $this->widgetData['data']['categories'] = [];
        }
        if ($this->widgetType === 'RetailProductWidget' && (!isset($this->widgetData['data']['products']) || !is_array($this->widgetData['data']['products']))) {
            $this->widgetData['data']['products'] = [];
        }
        if ($this->widgetType === 'WeatherWidget' && (!isset($this->widgetData['data']) || !is_array($this->widgetData['data']))) {
            $this->widgetData['data'] = ['location' => '', 'apiKey' => '', 'title' => 'Weather Forecast'];
        }
        if ($this->widgetType === 'ClockWidget' && (!isset($this->widgetData['data']) || !is_array($this->widgetData['data']))) {
            $this->widgetData['data'] = ['timezone' => 'Europe/London', 'showSeconds' => true, 'format' => 'H:i:s', 'showDate' => true, 'dateFormat' => 'l, F jS, Y', 'title' => 'Current Time'];
        }
        if ($this->widgetType === 'AnnouncementWidget' && (!isset($this->widgetData['data']) || !is_array($this->widgetData['data']))) {
            $this->widgetData['data'] = ['title' => 'Important Announcement', 'message' => 'Please be advised...', 'backgroundColor' => '#E0F2FE', 'textColor' => '#0C4A6E', 'titleColor' => '#075985'];
        }
        if ($this->widgetType === 'RssFeedWidget' && (!isset($this->widgetData['data']) || !is_array($this->widgetData['data']))) {
            $this->widgetData['data'] = ['feedUrl' => '', 'itemCount' => 5, 'title' => 'Latest News'];
        }
        if ($this->widgetType === 'CalendarWidget' && (!isset($this->widgetData['data']) || !is_array($this->widgetData['data']))) {
            $this->widgetData['data'] = ['calendarUrl' => '', 'title' => 'Upcoming Events'];
        }
        
        return view('livewire.content.widgets.widget-data-editor-modal');
    }
}

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
    public array $widgetData = [];

    protected $listeners = [
        'openWidgetDataEditor' => 'handleOpenWidgetDataEditor',
    ];

    #[On('openWidgetDataEditor')]
    public function handleOpenWidgetDataEditor(string $zoneId, string $widgetType, ?int $contentId = null): void
    {
        $this->zoneId = $zoneId;
        $this->widgetType = $widgetType;
        $this->contentId = $contentId;
        $this->showModal = true;
        $this->contentName = ''; // Reset
        $this->widgetData = [];  // Reset

        if ($this->contentId) {
            $content = Content::find($this->contentId);
            if ($content) {
                $this->contentName = $content->name;
                $this->widgetData = $content->content_data ?? [];
            }
        }

        if ($this->widgetType === 'MenuWidget' && empty($this->widgetData)) {
            $this->widgetData = ['categories' => []];
        } elseif ($this->widgetType === 'RetailProductWidget' && empty($this->widgetData)) {
            $this->widgetData = [
                'title' => 'Featured Products',
                'products' => [],
                'footer_promo_text' => 'Special offers end soon!'
            ];
        }
        // Add other widgetType initializations here if loading existing content and widgetData is empty
        // This part should be covered by the $content->content_data['data'] assignment.
        // However, if $content->content_data['data'] could be null or missing keys,
        // you might want to merge with defaults here as well.
        // For example:
        // if ($this->widgetType === 'RetailProductWidget' && $this->contentId && empty($this->widgetData['products'])) {
        //     $this->widgetData['products'] = []; // Ensure products array exists
        // }
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
                'widgetData' => 'present|array', // Basic validation, can be more specific per widgetType
            ]
        )->validate();

        $currentTenant = Auth::user()->tenant; // Assuming user is authenticated and has a tenant relationship
        if (!$currentTenant && class_exists(Tenant::class)) { // Fallback for single DB or if tenant comes from elsewhere
            $currentTenant = Tenant::current() ?? Tenant::first(); // Example: Get current tenant
        }

        if (!$currentTenant) {
            // Handle error: tenant not found
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Current tenant could not be determined.']);
            return;
        }

        $contentDataPrepared = [
            'widget_type' => $this->widgetType, // Store widget type for rendering
            'data' => $validatedData['widgetData'], // The actual structured data
        ];

        $contentDetails = [
            'tenant_id'    => $currentTenant->id,
            'name'         => $validatedData['contentName'],
            'type'         => ContentType::CUSTOM->value, // Or a new WIDGET_CONTENT type
            'status'       => ContentStatus::ACTIVE->value,
            'content_data' => $contentDataPrepared,
            'template_id'  => null, // Explicitly null as per instructions
        ];

        if ($this->contentId) {
            $content = Content::find($this->contentId);
            if ($content) {
                $content->update($contentDetails);
            } else {
                // Handle error: content to update not found
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Content to update not found.']);
                return;
            }
        } else {
            $content = Content::create($contentDetails);

            // Mark onboarding step as complete
            $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $currentTenant->id]);
            if (!$onboardingProgress->first_widget_content_created) {
                app(OnboardingProgressService::class)->completeStep($onboardingProgress, 'first_widget_content_created');
            }
        }

        // Dispatch to TemplateConfigurator or any other listener
        $this->dispatch('widgetContentSaved', zoneId: $this->zoneId, contentId: $content->id)
            ->to('App.Livewire.Content.Templates.TemplateConfigurator');

        $this->closeModal();
    }

    public function addCategory(): void
    {
        if ($this->widgetType === 'MenuWidget') {
            $this->widgetData['categories'][] = ['name' => '', 'items' => []];
        }
    }

    public function removeCategory(int $categoryIndex): void
    {
        if ($this->widgetType === 'MenuWidget' && isset($this->widgetData['categories'][$categoryIndex])) {
            unset($this->widgetData['categories'][$categoryIndex]);
            $this->widgetData['categories'] = array_values($this->widgetData['categories']); // Re-index
        }
    }

    public function addItem(int $categoryIndex): void
    {
        if ($this->widgetType === 'MenuWidget' && isset($this->widgetData['categories'][$categoryIndex])) {
            $this->widgetData['categories'][$categoryIndex]['items'][] = ['name' => '', 'price' => '', 'description' => ''];
        }
    }

    public function removeItem(int $categoryIndex, int $itemIndex): void
    {
        if ($this->widgetType === 'MenuWidget' && isset($this->widgetData['categories'][$categoryIndex]['items'][$itemIndex])) {
            unset($this->widgetData['categories'][$categoryIndex]['items'][$itemIndex]);
            $this->widgetData['categories'][$categoryIndex]['items'] = array_values($this->widgetData['categories'][$categoryIndex]['items']); // Re-index
        }
    }

    public function addProduct(): void
    {
        if ($this->widgetType === 'RetailProductWidget') {
            if (!isset($this->widgetData['products']) || !is_array($this->widgetData['products'])) {
                $this->widgetData['products'] = [];
            }
            $this->widgetData['products'][] = [
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
        if ($this->widgetType === 'RetailProductWidget' && isset($this->widgetData['products'][$productIndex])) {
            unset($this->widgetData['products'][$productIndex]);
            $this->widgetData['products'] = array_values($this->widgetData['products']); // Re-index
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['zoneId', 'widgetType', 'contentId', 'contentName', 'widgetData']);
    }

    public function render()
    {
        // Ensure widgetData has a 'products' key if the type is RetailProductWidget
        if ($this->widgetType === 'RetailProductWidget' && !isset($this->widgetData['products'])) {
            $this->widgetData['products'] = [];
        }
        // Ensure widgetData has a 'categories' key if the type is MenuWidget
        if ($this->widgetType === 'MenuWidget' && !isset($this->widgetData['categories'])) {
            $this->widgetData['categories'] = [];
        }
        return view('livewire.content.widgets.widget-data-editor-modal');
    }
}

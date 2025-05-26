<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Enums\ContentType;
use App\Tenant\Models\Content;
use App\Tenant\Models\OnboardingProgress;
use App\Services\OnboardingProgressService;
use App\Enums\OnboardingStep;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\Rule;

final class ProductListEditor extends Component
{
    public ?Content $content = null;

    #[Rule('required|string|max:255')]
    public string $listTitle = '';

    #[Rule('present|array')]
    public array $items = [];

    // Rules for individual items within the $items array
    #[Rule([
        'items.*.name' => 'required|string|max:255',
        'items.*.price' => 'nullable|string|max:50', // Flexible for currency symbols, /mo, etc.
        'items.*.description' => 'nullable|string|max:1000',
        'items.*.category' => 'nullable|string|max:100',
        'items.*.image_url' => 'nullable|url|max:2048',
    ])]
    public array $itemRules = []; // This is a placeholder for the attribute, actual rules are above.

    public function mount(?int $contentId = null): void
    {
        if ($contentId) {
            $this->content = Content::findOrFail($contentId);
            if (!in_array($this->content->type, [ContentType::PRODUCT_LIST, ContentType::MENU])) {
                abort(403, 'Invalid content type for this editor.');
            }
            $this->listTitle = $this->content->content_data['list_title'] ?? '';
            $this->items = $this->content->content_data['items'] ?? [];
        } else {
            $this->addItem();
        }
    }

    public function addItem(): void
    {
        $this->items[] = [
            'name' => '',
            'price' => '',
            'description' => '',
            'category' => '',
            'image_url' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Re-index array
        }
    }

    public function save(): void
    {
        $this->validate();

        $contentType = $this->content?->type ?? ContentType::PRODUCT_LIST;

        $data = [
            'tenant_id' => tenant('id') ?? Auth::user()->currentTenant->id,
            'name' => $this->listTitle ?: ($contentType === ContentType::MENU ? 'Untitled Menu' : 'Untitled Product List'),
            'type' => $contentType,
            'content_data' => [
                'list_title' => $this->listTitle,
                'items' => $this->items,
            ],
            'status' => $this->content?->status ?? \App\Enums\ContentStatus::DRAFT,
        ];

        $isNewContent = !$this->content;

        if ($this->content) {
            $this->content->update($data);
            session()->flash('message', $contentType->label() . ' updated successfully.');
        } else {
            $this->content = Content::create($data);
            session()->flash('message', $contentType->label() . ' created successfully.');
            
            if ($isNewContent && $this->content->tenant_id) {
                $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $this->content->tenant_id]);
                if (!$onboardingProgress->first_content_uploaded) {
                    app(OnboardingProgressService::class)->completeStep($onboardingProgress, OnboardingStep::FIRST_CONTENT_UPLOADED->value);
                }
            }
        }

        $this->dispatch('productListSaved', contentId: $this->content->id);
        $this->dispatch('content-created');
        $this->dispatch('content-updated');
    }

    public function render(): View
    {
        return view('livewire.content.product-list-editor');
    }
} 
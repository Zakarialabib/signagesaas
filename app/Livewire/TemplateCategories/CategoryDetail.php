<?php

namespace App\Livewire\TemplateCategories;

use Livewire\Component;
use App\Enums\TemplateCategory;
use App\Tenant\Models\Template;

class CategoryDetail extends Component
{
    public TemplateCategory $category;
    public $templates;
    public $relatedCategories;

    public function mount($category)
    {
        if ($category instanceof TemplateCategory) {
            $this->category = $category;
        } else {
            $this->category = TemplateCategory::from($category);
        }
        
        $this->templates = Template::where('category', $this->category->value)
            // ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        $this->relatedCategories = $this->getRelatedCategories();
    }

    protected function getRelatedCategories(): array
    {
        return match($this->category) {
            TemplateCategory::RESTAURANT => [
                TemplateCategory::MENU,
                TemplateCategory::HOSPITALITY,
                TemplateCategory::EVENTS,
            ],
            TemplateCategory::SOCIAL_MEDIA => [
                TemplateCategory::NEWS,
                TemplateCategory::ANNOUNCEMENTS,
            ],
            TemplateCategory::WEATHER => [
                TemplateCategory::NEWS,
                TemplateCategory::CALENDAR,
            ],
            default => [
                TemplateCategory::SOCIAL_MEDIA,
                TemplateCategory::NEWS,
                TemplateCategory::ANNOUNCEMENTS,
            ]
        };
    }

    public function render()
    {
        return view('livewire.template-categories.category-detail');
    }
} 
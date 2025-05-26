<?php

declare(strict_types=1);

namespace App\Livewire\TemplateCategories;

use Livewire\Component;
use App\Enums\TemplateCategory;
use Illuminate\Support\Collection;

class CategoryShowcase extends Component
{
    public Collection $featuredCategories;
    public Collection $industryCategories;
    public Collection $otherCategories;

    public function mount()
    {
        $this->featuredCategories = collect([
            TemplateCategory::SOCIAL_MEDIA,
            TemplateCategory::NEWS,
            TemplateCategory::WEATHER,
            TemplateCategory::MENU,
            TemplateCategory::ANNOUNCEMENTS,
        ]);

        $this->industryCategories = collect([
            TemplateCategory::RESTAURANT,
            TemplateCategory::RETAIL,
            TemplateCategory::HOSPITALITY,
            TemplateCategory::BANKING,
            TemplateCategory::HEALTHCARE,
            TemplateCategory::EDUCATION,
            TemplateCategory::GOVERNMENT,
            TemplateCategory::TRANSPORTATION,
        ]);

        $this->otherCategories = collect([
            TemplateCategory::CORPORATE,
            TemplateCategory::EVENTS,
            TemplateCategory::CUSTOM,
        ]);
    }

    public function render()
    {
        return view('livewire.template-categories.category-showcase');
    }
}

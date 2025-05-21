<?php

namespace App\Livewire\TemplateCategories;

use Livewire\Component;
use App\Tenant\Models\Template;
use App\Enums\TemplateCategory;

class TemplatePreview extends Component
{
    public Template $template;
    public TemplateCategory $category;
    public $relatedTemplates;

    public function mount(Template $template)
    {
        $this->template = $template;

        // Ensure $template->category is the string/int value for the enum
        if ($template->category instanceof TemplateCategory) {
             // If $template->category is already an enum instance (e.g. due to casting on model)
            $this->category = $template->category;
        } elseif (is_string($template->category) || is_int($template->category)) {
            $this->category = TemplateCategory::from($template->category);
        } else {
            // Fallback or error handling if $template->category is neither
            // This might indicate a data issue or a wrong property being accessed
            // For now, let's attempt to use the template's own category value if it's directly a string/int enum value
            // Or throw an exception if it's not a valid type for TemplateCategory::from()
            if (property_exists($template, 'category') && (is_string($template->category) || is_int($template->category))){
                 $this->category = TemplateCategory::from($template->category);
            } else {
                // Handle cases where $template->category is not a valid scalar or already an enum.
                // This might require a default or throwing an error, depending on expected $template structure.
                // For safety, let's throw an exception if it cannot be resolved.
                throw new \InvalidArgumentException("Template category could not be resolved to a valid TemplateCategory enum. Received: " . gettype($template->category));
            }
        }

        $this->relatedTemplates = Template::where('category', $this->category->value)
            ->where('id', '!=', $template->id)
            // ->where('is_active', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.template-categories.template-preview');
    }
} 
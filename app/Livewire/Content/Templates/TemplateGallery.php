<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates;

use App\Enums\TemplateCategory;
use App\Enums\TemplateStatus;
use App\Tenant\Models\Template;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

#[Layout('layouts.app')]
#[Title('Template Gallery')]
class TemplateGallery extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoryFilter = 'all';
    public bool $showPreviewModal = false;
    public bool $showCustomizeModal = false;
    public ?Template $selectedTemplate = null;
    public bool $showConfigurator = false;
    public ?string $selectedTemplateId = null;

    public array $customization = [
        'name'     => '',
        'settings' => [],
        'styles'   => [],
    ];

    protected array $baseTemplates = [
        'retail_promotion' => [
            'name'        => 'Retail Promotion',
            'description' => 'Promote products and sales in retail environments.',
            'category'    => TemplateCategory::RETAIL->value,
            'layout'      => [
                'type'  => 'grid',
                'zones' => [
                    [
                        'id'     => 'main',
                        'name'   => 'Main Content',
                        'type'   => 'content',
                        'x'      => 0,
                        'y'      => 0,
                        'width'  => 100,
                        'height' => 70,
                    ],
                    [
                        'id'     => 'sidebar',
                        'name'   => 'Sidebar',
                        'type'   => 'content',
                        'x'      => 0,
                        'y'      => 70,
                        'width'  => 100,
                        'height' => 30,
                    ],
                ],
            ],
        ],
        'transport_timetable' => [
            'name'        => 'Transport Timetable',
            'description' => 'Display schedules for buses, trains, or flights.',
            'category'    => TemplateCategory::TRANSPORTATION->value,
            'layout'      => [
                'type'  => 'grid',
                'zones' => [
                    [
                        'id'     => 'header',
                        'name'   => 'Header',
                        'type'   => 'content',
                        'x'      => 0,
                        'y'      => 0,
                        'width'  => 100,
                        'height' => 20,
                    ],
                    [
                        'id'     => 'timetable',
                        'name'   => 'Timetable',
                        'type'   => 'content',
                        'x'      => 0,
                        'y'      => 20,
                        'width'  => 100,
                        'height' => 80,
                    ],
                ],
            ],
        ],
        // Add more base templates...
    ];

    public function mount(): void
    {
        $this->resetCustomization();
    }

    private function resetCustomization(): void
    {
        $this->customization = [
            'name'     => '',
            'settings' => [
                'background'    => '#ffffff',
                'color'         => '#000000',
                'padding'       => '0',
                'margin'        => '0',
                'border-radius' => '0',
            ],
            'styles' => [],
        ];
    }

    public function preview(string $templateId): void
    {
        $this->selectedTemplate = Template::findOrFail($templateId);
        $this->showPreviewModal = true;
    }

    public function customize(string $templateId): void
    {
        $this->selectedTemplate = Template::findOrFail($templateId);
        $this->loadTemplateDefaults();
        $this->showCustomizeModal = true;
    }

    private function loadTemplateDefaults(): void
    {
        if ( ! $this->selectedTemplate) {
            return;
        }

        $this->customization['settings'] = $this->selectedTemplate->getDefaultSettings();
        $this->customization['styles'] = $this->selectedTemplate->getDefaultStyles();
    }

    public function useBaseTemplate(string $baseKey): void
    {
        if ( ! array_key_exists($baseKey, $this->baseTemplates)) {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'Base template not found.',
            ]);

            return;
        }

        $base = $this->baseTemplates[$baseKey];
        $template = Template::create([
            'name'        => $base['name'],
            'description' => $base['description'],
            'category'    => $base['category'],
            'layout'      => $base['layout'],
            'status'      => TemplateStatus::DRAFT,
        ]);

        $this->selectedTemplate = $template;
        $this->selectedTemplateId = $template->id;
        $this->showConfigurator = true;

        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => 'Base template created. You can now configure it.',
        ]);
    }

    public function saveCustomization(): void
    {
        $this->validate([
            'customization.name' => 'required|string|max:255',
        ]);

        $template = Template::create([
            'name'        => $this->customization['name'],
            'description' => $this->selectedTemplate->description,
            'category'    => $this->selectedTemplate->category,
            'layout'      => $this->selectedTemplate->layout,
            'settings'    => $this->customization['settings'],
            'styles'      => $this->customization['styles'],
            'status'      => TemplateStatus::DRAFT,
            'parent_id'   => $this->selectedTemplate->id,
        ]);

        $this->dispatch('template-created', ['id' => $template->id]);
        $this->showCustomizeModal = false;
        $this->resetCustomization();
    }

    public function closeModals(): void
    {
        $this->showPreviewModal = false;
        $this->showCustomizeModal = false;
        $this->selectedTemplate = null;
        $this->resetCustomization();
    }

    #[Computed]
    public function categories()
    {
        return TemplateCategory::cases();
    }

    public function render()
    {
        $templates = Template::query()
            ->when($this->categoryFilter !== 'all', function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->where('status', TemplateStatus::PUBLISHED)
            ->latest()
            ->paginate(12);

        return view('livewire.content.templates.template-gallery', [
            'templates'     => $templates,
            'baseTemplates' => $this->baseTemplates,
            'categories'    => $this->categories,
        ]);
    }
}

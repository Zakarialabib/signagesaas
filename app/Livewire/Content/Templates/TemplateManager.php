<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates;

use App\Enums\TemplateCategory;
use App\Enums\TemplateStatus;
use App\Tenant\Models\Template;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Templates')]
final class TemplateManager extends Component
{
    use WithPagination;

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
    ];

    public string $search = '';
    public string $categoryFilter = 'all';
    public string $statusFilter = 'all';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public bool $showDeleteModal = false;
    public ?Template $templateToDelete = null;
    public ?string $selectedTemplateId = null;
    public bool $showConfigurator = false;
    public bool $showPreview = false;

    protected $listeners = [
        'template-created'  => 'refreshTemplates',
        'template-updated'  => 'refreshTemplates',
        'template-deleted'  => 'refreshTemplates',
        'version-created'   => '$refresh',
        'variation-created' => '$refresh',
    ];

    #[Computed]
    public function categories(): Collection
    {
        return collect(TemplateCategory::cases())
            ->map(fn ($category) => [
                'value'       => $category->value,
                'label'       => $category->label(),
                'description' => $category->getDescription(),
                'icon'        => $category->getIcon(),
            ]);
    }

    #[Computed]
    public function statuses(): Collection
    {
        return collect(TemplateStatus::cases())
            ->map(fn ($status) => [
                'value' => $status->value,
                'label' => $status->label(),
                'color' => $status->getColor(),
            ]);
    }

    #[Computed]
    public function templates()
    {
        return Template::query()
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            }))
            ->when($this->categoryFilter !== 'all', fn ($query) => $query->where('category', $this->categoryFilter))
            ->when($this->statusFilter !== 'all', fn ($query) => $query->where('status', $this->statusFilter))
            ->withCount(['contents', 'versions'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(12);
    }

    public function confirmDelete(string $id): void
    {
        $this->templateToDelete = Template::withCount('contents')->find($id);
        $this->showDeleteModal = true;
    }

    public function deleteTemplate(): void
    {
        if ( ! $this->templateToDelete) {
            return;
        }

        // $this->authorize('delete', $this->templateToDelete);
        $this->templateToDelete->delete();

        $this->showDeleteModal = false;
        $this->templateToDelete = null;

        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => 'Template deleted successfully.',
        ]);
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->templateToDelete = null;
    }

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';
        $this->sortField = $field;
    }

    public function openConfigurator(string $templateId): void
    {
        $this->selectedTemplateId = $templateId;
        $this->showConfigurator = true;
    }

    public function closeConfigurator(): void
    {
        $this->showConfigurator = false;
        $this->selectedTemplateId = null;
    }

    public function openPreview(string $templateId): void
    {
        $this->selectedTemplateId = $templateId;
        $this->showPreview = true;
    }

    public function closePreview(): void
    {
        $this->showPreview = false;
        $this->selectedTemplateId = null;
    }

    public function refreshTemplates(): void
    {
        // The view will re-render automatically
    }

    #[On('duplicate-template')]
    public function duplicateTemplate(string $id): void
    {
        $template = Template::findOrFail($id);
        // $this->authorize('create', Template::class);

        $newTemplate = $template->replicate();
        $newTemplate->name = "{$template->name} (Copy)";
        $newTemplate->status = TemplateStatus::DRAFT;
        $newTemplate->save();

        foreach ($template->assets as $asset) {
            $newAsset = $asset->replicate();
            $newAsset->assetable_id = $newTemplate->id;
            $newAsset->save();
        }

        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => 'Template duplicated successfully.',
        ]);
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

    public function render()
    {
        return view('livewire.content.templates.template-manager', [
            'templates'        => $this->templates,
            'selectedTemplate' => $this->selectedTemplateId ? Template::find($this->selectedTemplateId) : null,
            'baseTemplates'    => $this->baseTemplates,
            'categories'       => $this->categories,
        ]);
    }
}
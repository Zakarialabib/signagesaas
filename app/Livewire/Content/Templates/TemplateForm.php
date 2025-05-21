<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates;

use App\Enums\TemplateCategory;
use App\Enums\TemplateStatus;
use App\Tenant\Models\Template;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

final class TemplateForm extends Component
{
    use WithFileUploads;

    public ?Template $template = null;

    #[Rule('required|string|min:3|max:255')]
    public string $name = '';

    #[Rule('nullable|string|max:1000')]
    public ?string $description = null;

    #[Rule('required|string')]
    public string $category = '';

    #[Rule('required|string')]
    public string $status = '';

    #[Rule('required|array')]
    public ?array $layout = null;

    #[Rule('nullable|array')]
    public ?array $styles = null;

    #[Rule('required|integer|min:1|max:3600')]
    public int $default_duration = 30;

    #[Rule('nullable|array')]
    public ?array $settings = null;

    #[Rule('nullable|image|max:5120')] // 5MB max
    public ?TemporaryUploadedFile $preview_image = null;

    public bool $showDeletePreviewModal = false;
    public bool $snapToGrid = true;
    public int $gridSize = 5;

    public function mount(?string $id = null): void
    {
        if ($id) {
            $this->template = Template::findOrFail($id);
            // $this->authorize('update', $this->template);

            $this->name = $this->template->name;
            $this->description = $this->template->description;
            $this->category = $this->template->category->value;
            $this->status = $this->template->status->value;
            $this->layout = $this->template->layout;
            $this->styles = $this->template->styles;
            $this->default_duration = $this->template->default_duration;
            $this->settings = $this->template->settings;
        } else {
            // $this->authorize('create', Template::class);
            $this->status = TemplateStatus::DRAFT->value;
            $this->initializeDefaultLayout();
        }
    }

    private function initializeDefaultLayout(): void
    {
        $this->layout = [
            'type'   => 'grid',
            'width'  => 1920,
            'height' => 1080,
            'zones'  => [],
        ];
    }

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

    public function addZone(): void
    {
        if ( ! $this->layout) {
            $this->initializeDefaultLayout();
        }

        $newZoneId = 'zone_'.(count($this->layout['zones'] ?? []) + 1);

        $this->layout['zones'][$newZoneId] = [
            'id'       => $newZoneId,
            'name'     => 'New Zone',
            'type'     => 'content',
            'x'        => 0,
            'y'        => 0,
            'width'    => 50,
            'height'   => 50,
            'settings' => [
                'duration'      => $this->default_duration,
                'transition'    => 'fade',
                'background'    => '#ffffff',
                'padding'       => '0',
                'border-radius' => '0',
            ],
        ];
    }

    public function deleteZone(string $zoneId): void
    {
        if (isset($this->layout['zones'][$zoneId])) {
            unset($this->layout['zones'][$zoneId]);
        }
    }

    public function updateZonePosition(string $zoneId, float $x, float $y, float $width, float $height): void
    {
        if ($this->snapToGrid) {
            $x = round($x / $this->gridSize) * $this->gridSize;
            $y = round($y / $this->gridSize) * $this->gridSize;
            $width = round($width / $this->gridSize) * $this->gridSize;
            $height = round($height / $this->gridSize) * $this->gridSize;
        }

        if (isset($this->layout['zones'][$zoneId])) {
            $this->layout['zones'][$zoneId]['x'] = $x;
            $this->layout['zones'][$zoneId]['y'] = $y;
            $this->layout['zones'][$zoneId]['width'] = $width;
            $this->layout['zones'][$zoneId]['height'] = $height;
        }
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->preview_image) {
            $preview_path = $this->preview_image->store('previews', 'public');
            $validated['preview_image'] = $preview_path;
        }

        if ($this->template) {
            $this->template->update($validated);
            $message = 'Template updated successfully.';
            $event = 'template-updated';
        } else {
            Template::create($validated);
            $message = 'Template created successfully.';
            $event = 'template-created';
        }

        $this->dispatch($event);
        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => $message,
        ]);

        if ( ! $this->template) {
            $this->reset(['name', 'description', 'category', 'layout', 'styles', 'settings', 'preview_image']);
            $this->status = TemplateStatus::DRAFT->value;
            $this->default_duration = 30;
        }
    }

    public function confirmDeletePreview(): void
    {
        $this->showDeletePreviewModal = true;
    }

    public function deletePreview(): void
    {
        if ($this->template && $this->template->preview_image) {
            // Delete the file
            unlink(storage_path('app/public/'.$this->template->preview_image));

            // Update the model
            $this->template->update(['preview_image' => null]);

            $this->dispatch('notify', [
                'type'    => 'success',
                'message' => 'Preview image deleted successfully.',
            ]);
        }

        $this->showDeletePreviewModal = false;
    }

    public function render()
    {
        return view('livewire.content.templates.template-form');
    }
}

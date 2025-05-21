<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates\Components;

use App\Enums\ContentType;
use App\Tenant\Models\Content;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

final class ZoneContentSelector extends Component
{
    use WithPagination;

    public string $zoneId;
    public string $selectedContentType = '';
    public ?string $selectedContentId = null;

    protected $listeners = [
        'contentCreated' => 'handleContentCreated',
    ];

    public function mount(string $zoneId): void
    {
        $this->zoneId = $zoneId;
    }

    #[Computed]
    public function contentTypes(): array
    {
        return ContentType::options();
    }

    #[Computed]
    public function availableContent()
    {
        return Content::query()
            ->when($this->selectedContentType, function ($query) {
                $query->where('type', $this->selectedContentType);
            })
            ->where('status', 'active')
            ->latest()
            ->paginate(8);
    }

    #[Computed]
    public function selectedContent(): ?Content
    {
        if ( ! $this->selectedContentId) {
            return null;
        }

        return Content::find($this->selectedContentId);
    }

    public function filterByType(string $type): void
    {
        $this->selectedContentType = $type;
        $this->resetPage();
    }

    public function selectContent(string $contentId): void
    {
        $this->selectedContentId = $contentId;
    }

    public function clearSelection(): void
    {
        $this->selectedContentId = null;
    }

    public function assignContent(): void
    {
        if ( ! $this->selectedContentId) {
            return;
        }

        $this->dispatch('content-assigned', [
            'zoneId'    => $this->zoneId,
            'contentId' => $this->selectedContentId,
        ]);

        $this->clearSelection();
    }

    public function handleContentCreated(string $contentId): void
    {
        $this->selectContent($contentId);
    }

    public function render()
    {
        return view('livewire.content.templates.components.zone-content-selector');
    }
}

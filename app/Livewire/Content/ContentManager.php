<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Tenant\Models\Content;
use App\Tenant\Models\Screen;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;

#[Layout('layouts.app')]
final class ContentManager extends Component
{
    use WithPagination;

    #[Validate('nullable|string|max:255')]
    public ?string $search = null;

    #[Validate('nullable|string')]
    public string $statusFilter = 'all';

    #[Validate('nullable|string')]
    public string $typeFilter = 'all';

    #[Validate('nullable|string')]
    public string $screenFilter = 'all';

    #[Validate('nullable|string')]
    public string $sortField = 'created_at';

    #[Validate('nullable|string|in:asc,desc')]
    public string $sortDirection = 'desc';

    #[Locked]
    public ?Content $selectedContent = null;

    public bool $deleteContentModal = false;

    public bool $bulkActionModal = false;

    #[Locked]
    public ?string $contentToDelete = null;

    #[Locked]
    public int $perPage = 10;

    public string $dateFilter = 'all';

    public array $selected = [];
    public string $bulkAction = '';
    public bool $selectAll = false;

    public function mount(): void
    {
        // $this->authorize('viewAny', Content::class);
    }

    public function render()
    {
        $query = Content::query()
            ->with(['screen' => fn ($query) => $query->with('device')])
            ->when(
                $this->search,
                fn ($query) => $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas(
                        'screen',
                        fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                    )
            )
            ->when(
                $this->statusFilter !== 'all',
                fn ($query) => $query->where('status', $this->statusFilter)
            )
            ->when(
                $this->typeFilter !== 'all',
                fn ($query) => $query->where('type', $this->typeFilter)
            )
            ->when(
                $this->screenFilter !== 'all',
                fn ($query) => $query->where('screen_id', $this->screenFilter)
            )
            ->orderBy($this->sortField, $this->sortDirection);

        $contents = $query->paginate($this->perPage);

        // Handle select all functionality
        if ($this->selectAll) {
            $this->selected = $query->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        }

        return view('livewire.content.content-manager', [
            'contents'         => $contents,
            'contentTypes'     => ContentType::options(),
            'contentStatuses'  => ContentStatus::options(),
            'screens'          => $this->getScreensForFilter(),
            'hasSelectedItems' => count($this->selected) > 0,
            'selectedCount'    => count($this->selected),
        ]);
    }

    #[Computed]
    public function getScreensForFilter()
    {
        return Screen::where('status', 'active')
            ->with('device')
            ->get()
            ->mapWithKeys(function ($screen) {
                return [$screen->id => $screen->name.' ('.$screen->device->name.')'];
            });
    }

    #[Computed]
    public function bulkActionOptions()
    {
        return [
            'activate'   => 'Activate Selected',
            'deactivate' => 'Deactivate Selected',
            'delete'     => 'Delete Selected',
        ];
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
        $this->resetPage();
    }

    public function refreshContents(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'typeFilter', 'screenFilter']);
        $this->resetPage();
    }

    #[On('confirm-delete-content')]
    public function confirmDeleteContent(string $id): void
    {
        $content = Content::findOrFail($id);
        $this->authorize('delete', $content);

        $this->contentToDelete = $id;
        $this->deleteContentModal = true;
    }

    #[On('content-created')]
    public function handleContentCreated(): void
    {
        $this->refreshContents();

        // Show confirmation message
        session()->flash('flash.banner', 'Content created successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }

    #[On('content-updated')]
    public function handleContentUpdated(): void
    {
        $this->refreshContents();

        // Show confirmation message
        session()->flash('flash.banner', 'Content updated successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }

    public function deleteContent(): void
    {
        if ( ! $this->contentToDelete) {
            return;
        }

        $content = Content::findOrFail($this->contentToDelete);
        $this->authorize('delete', $content);

        $content->delete();

        session()->flash('flash.banner', 'Content deleted successfully.');
        session()->flash('flash.bannerStyle', 'success');

        $this->refreshContents();
    }

    public function toggleSelectAll(): void
    {
        $this->selectAll = ! $this->selectAll;

        if ( ! $this->selectAll) {
            $this->selected = [];
        }
    }

    public function openBulkActionModal(): void
    {
        if (empty($this->selected)) {
            session()->flash('flash.banner', 'Please select at least one content item.');
            session()->flash('flash.bannerStyle', 'danger');

            return;
        }

        $this->bulkActionModal = true;
    }

    public function executeBulkAction(): void
    {
        if (empty($this->selected) || empty($this->bulkAction)) {
            return;
        }

        $contents = Content::whereIn('id', $this->selected)->get();

        switch ($this->bulkAction) {
            case 'activate':
                foreach ($contents as $content) {
                    if ($this->authorize('update', $content)) {
                        $content->status = ContentStatus::ACTIVE;
                        $content->save();
                    }
                }
                session()->flash('flash.banner', count($this->selected).' content items activated.');

                break;

            case 'deactivate':
                foreach ($contents as $content) {
                    if ($this->authorize('update', $content)) {
                        $content->status = ContentStatus::INACTIVE;
                        $content->save();
                    }
                }
                session()->flash('flash.banner', count($this->selected).' content items deactivated.');

                break;

            case 'delete':
                $deletedCount = 0;

                foreach ($contents as $content) {
                    if ($this->authorize('delete', $content)) {
                        $content->delete();
                        $deletedCount++;
                    }
                }
                session()->flash('flash.banner', $deletedCount.' content items deleted.');

                break;
        }

        session()->flash('flash.bannerStyle', 'success');
        $this->selected = [];
        $this->selectAll = false;
        $this->bulkAction = '';

        $this->refreshContents();
    }
}

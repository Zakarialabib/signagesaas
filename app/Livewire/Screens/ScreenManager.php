<?php

declare(strict_types=1);

namespace App\Livewire\Screens;

use App\Tenant\Models\Screen;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

#[Layout('layouts.app')]
final class ScreenManager extends Component
{
    use WithPagination;

    /** Search term for filtering screens */
    #[Validate('nullable|string|max:255')]
    public ?string $search = null;

    /** Filter screens by status */
    #[Validate('nullable|string|in:active,inactive,all')]
    public string $statusFilter = 'all';

    /** Filter screens by orientation */
    #[Validate('nullable|string|in:landscape,portrait,all')]
    public string $orientationFilter = 'all';

    /** Currently selected screen */
    #[Locked]
    public ?Screen $selectedScreen = null;

    /** Controls visibility of delete confirmation modal */
    public bool $deleteScreenModal = false;

    /** ID of screen marked for deletion */
    #[Locked]
    public ?string $screenToDelete = null;

    /** Mount the component and check authorization */
    public function mount(): void
    {
        $this->authorize('viewAny', Screen::class);
    }

    /** Mark search input for debounce to reduce server calls */
    protected function queryString(): array
    {
        return [
            'search'            => ['except' => '', 'as' => 'q'],
            'statusFilter'      => ['except' => 'all', 'as' => 'status'],
            'orientationFilter' => ['except' => 'all', 'as' => 'orientation'],
        ];
    }

    /** Get filtered screens query */
    protected function getScreensQuery(): Builder
    {
        $query = Screen::query()
            ->select(['id', 'name', 'status', 'orientation', 'device_id', 'resolution', 'created_at'])
            ->with(['device:id,name,status'])
            ->withCount('contents');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('device', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply orientation filter
        if ($this->orientationFilter !== 'all') {
            $query->where('orientation', $this->orientationFilter);
        }

        return $query->latest();
    }

    /** Render the screen manager view */
    public function render(): View
    {
        return view('livewire.screens.screen-manager', [
            'screens' => $this->getScreensQuery()->paginate(10),
        ]);
    }

    /** Reset the pagination when filters change */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedOrientationFilter(): void
    {
        $this->resetPage();
    }

    /** Refresh the screens list */
    public function refreshScreens(): void
    {
        $this->resetPage();
    }

    /** Open the delete confirmation modal */
    #[On('confirmDeleteScreen')]
    public function openModal(string $id): void
    {
        $this->screenToDelete = $id;
        $this->deleteScreenModal = true;
    }

    /** Handle screen created/updated events */
    #[On('screen-created')]
    #[On('screen-updated')]
    public function handleScreenChange(): void
    {
        $this->closeAllModals();
        $this->refreshScreens();

        // Banner is handled by the event-triggering component
    }

    /** Delete a screen and its associated content */
    public function deleteScreen(): void
    {
        if ( ! $this->screenToDelete) {
            return;
        }

        $screen = Screen::findOrFail($this->screenToDelete);
        $this->authorize('delete', $screen);

        // Use transaction to ensure atomicity
        DB::transaction(function () use ($screen) {
            // Delete all content associated with this screen
            $screen->contents()->delete();
            // Delete the screen
            $screen->delete();
        });

        session()->flash('flash.banner', 'Screen and associated content deleted successfully.');
        session()->flash('flash.bannerStyle', 'success');

        $this->closeAllModals();
        $this->refreshScreens();
    }

    /** Cancel the screen deletion */
    public function cancelDelete(): void
    {
        $this->screenToDelete = null;
        $this->deleteScreenModal = false;
    }

    /** Close all modals */
    private function closeAllModals(): void
    {
        $this->deleteScreenModal = false;
        $this->screenToDelete = null;
    }
}

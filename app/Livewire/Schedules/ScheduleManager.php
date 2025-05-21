<?php

declare(strict_types=1);

namespace App\Livewire\Schedules;

use App\Tenant\Models\Screen;
use App\Tenant\Models\Schedule;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Schedule Manager')]
#[Layout('layouts.app')]
final class ScheduleManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $screenFilter = 'all';
    public string $dateFilter = 'all';
    public string $sortField = 'start_date';
    public string $sortDirection = 'desc';

    public bool $showDeleteModal = false;
    public ?Schedule $scheduleToDelete = null;

    public function mount(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function screens(): Collection
    {
        return Screen::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id');
    }

    public function sortBy(string $field): void
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function confirmDelete(Schedule $schedule): void
    {
        $this->scheduleToDelete = $schedule;
        $this->showDeleteModal = true;
    }

    public function deleteSchedule(): void
    {
        if ($this->scheduleToDelete) {
            $this->scheduleToDelete->delete();
            $this->showDeleteModal = false;
            $this->scheduleToDelete = null;
            $this->dispatch('schedule-deleted');
        }
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->scheduleToDelete = null;
    }

    public function render()
    {
        $schedules = Schedule::query()
            ->with(['screen', 'contents'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->screenFilter !== 'all', function ($query) {
                $query->where('screen_id', $this->screenFilter);
            })
            ->when($this->dateFilter !== 'all', function ($query) {
                match ($this->dateFilter) {
                    'today' => $query->whereDate('start_date', today()),
                    'week'  => $query->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $query->whereMonth('start_date', now()->month),
                    default => null
                };
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.schedules.schedule-manager', [
            'schedules' => $schedules,
        ]);
    }
}

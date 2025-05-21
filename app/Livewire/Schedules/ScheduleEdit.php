<?php

declare(strict_types=1);

namespace App\Livewire\Schedules;

use App\Enums\ScheduleStatus;
use App\Tenant\Models\Content;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Screen;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

final class ScheduleEdit extends Component
{
    public bool $showModal = false;
    public ?Schedule $schedule = null;
    public string $name = '';
    public string $description = '';
    public ?string $screen_id = null;
    public string $status = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $start_time = '';
    public string $end_time = '';
    public array $days_of_week = [];
    public int $priority = 0;
    public array $selected_contents = [];

    protected array $rules = [
        'name'                => 'required|string|max:255',
        'description'         => 'nullable|string',
        'screen_id'           => 'required|exists:screens,id',
        'status'              => 'required|string',
        'start_date'          => 'required|date',
        'end_date'            => 'nullable|date|after_or_equal:start_date',
        'start_time'          => 'required',
        'end_time'            => 'required|after:start_time',
        'days_of_week'        => 'array',
        'days_of_week.*'      => 'integer|between:0,6',
        'priority'            => 'integer|min:0',
        'selected_contents'   => 'array',
        'selected_contents.*' => 'exists:contents,id',
    ];

    #[On('editSchedule')]
    public function open(string $id): void
    {
        $this->schedule = Schedule::with('contents')->findOrFail($id);
        $this->name = $this->schedule->name;
        $this->description = $this->schedule->description ?? '';
        $this->screen_id = $this->schedule->screen_id;
        $this->status = $this->schedule->status->value;
        $this->start_date = $this->schedule->start_date->format('Y-m-d');
        $this->end_date = $this->schedule->end_date?->format('Y-m-d') ?? '';
        $this->start_time = $this->schedule->start_time->format('H:i');
        $this->end_time = $this->schedule->end_time->format('H:i');
        $this->days_of_week = $this->schedule->days_of_week;
        $this->priority = $this->schedule->priority;
        $this->selected_contents = $this->schedule->contents->pluck('id')->toArray();

        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->reset();
    }

    #[Computed]
    public function screens(): Collection
    {
        return Screen::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableContents(): Collection
    {
        if ( ! $this->screen_id) {
            return collect();
        }

        return Content::query()
            ->where('screen_id', $this->screen_id)
            ->select(['id', 'name', 'type'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function statuses(): array
    {
        return collect(ScheduleStatus::cases())
            ->map(fn ($status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ])
            ->toArray();
    }

    public function update(): void
    {
        $this->validate();

        if ( ! $this->schedule) {
            return;
        }

        $this->schedule->update([
            'name'         => $this->name,
            'description'  => $this->description,
            'screen_id'    => $this->screen_id,
            'status'       => $this->status,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date ?: null,
            'start_time'   => $this->start_time,
            'end_time'     => $this->end_time,
            'days_of_week' => $this->days_of_week,
            'priority'     => $this->priority,
        ]);

        $contentData = collect($this->selected_contents)
            ->mapWithKeys(fn ($id, $index) => [
                $id => ['order' => $index, 'duration' => 10],
            ])
            ->toArray();

        $this->schedule->contents()->sync($contentData);

        $this->dispatch('schedule-updated');
        $this->close();
    }

    public function render()
    {
        return view('livewire.schedules.schedule-edit');
    }
}

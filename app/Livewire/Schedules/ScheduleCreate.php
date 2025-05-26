<?php

declare(strict_types=1);

namespace App\Livewire\Schedules;

use App\Enums\ScheduleStatus;
use App\Services\OnboardingProgressService;
use App\Tenant\Models\Content;
use App\Tenant\Models\OnboardingProgress;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Screen;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

final class ScheduleCreate extends Component
{
    public bool $showModal = false;
    public string $name = '';
    public string $description = '';
    public ?string $screen_id = null;
    public string $status = 'draft';
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

    #[On('createSchedule')]
    public function open(): void
    {
        $this->resetExcept('showModal');
        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->resetExcept('showModal');
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

    public function create(): void
    {
        $this->validate();

        $schedule = Schedule::create([
            'tenant_id'    => tenant('id'),
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

        if ( ! empty($this->selected_contents)) {
            $contentData = collect($this->selected_contents)
                ->mapWithKeys(fn ($id, $index) => [
                    $id => ['order' => $index, 'duration' => 10],
                ])
                ->toArray();

            $schedule->contents()->attach($contentData);
        }

        // Mark onboarding step as complete
        $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $schedule->tenant_id]);
        if (!$onboardingProgress->first_schedule_created) {
            app(OnboardingProgressService::class)->completeStep($onboardingProgress, 'first_schedule_created');
        }

        $this->dispatch('schedule-created');
        $this->close();
    }

    public function render()
    {
        return view('livewire.schedules.schedule-create');
    }
}

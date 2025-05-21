<?php

declare(strict_types=1);

namespace App\Livewire\Schedules;

use App\Tenant\Models\Schedule;
use Livewire\Attributes\On;
use Livewire\Component;

final class ScheduleShow extends Component
{
    public bool $showModal = false;
    public ?Schedule $schedule = null;

    #[On('showSchedule')]
    public function open(string $id): void
    {
        $this->schedule = Schedule::with(['screen', 'contents'])->findOrFail($id);
        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.schedules.schedule-show');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates\Components;

use App\Tenant\Models\Content;
use App\Tenant\Models\Template;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

final class ZoneScheduler extends Component
{
    public Template $template;
    public string $zoneId;
    public array $schedule = [];
    public ?string $selectedContentId = null;
    public ?string $startTime = null;
    public ?string $endTime = null;
    public array $days = [];

    public function mount(Template $template, string $zoneId): void
    {
        $this->template = $template;
        $this->zoneId = $zoneId;
        $this->loadSchedule();
    }

    private function loadSchedule(): void
    {
        $layout = $this->template->layout;
        $this->schedule = $layout['zones'][$this->zoneId]['schedule'] ?? [];
    }

    #[Computed]
    public function availableContent(): Collection
    {
        return Content::query()
            ->where('status', 'active')
            ->whereIn('type', $this->template->layout['zones'][$this->zoneId]['allowed_types'] ?? [])
            ->get();
    }

    public function addScheduleItem(): void
    {
        $this->validate([
            'selectedContentId' => 'required|exists:contents,id',
            'startTime'         => 'required|date_format:H:i',
            'endTime'           => 'required|date_format:H:i|after:startTime',
            'days'              => 'required|array|min:1',
        ]);

        $scheduleItem = [
            'content_id' => $this->selectedContentId,
            'start_time' => $this->startTime,
            'end_time'   => $this->endTime,
            'days'       => $this->days,
        ];

        $layout = $this->template->layout;
        $layout['zones'][$this->zoneId]['schedule'][] = $scheduleItem;

        $this->template->update(['layout' => $layout]);

        $this->reset(['selectedContentId', 'startTime', 'endTime', 'days']);
        $this->loadSchedule();
    }

    public function removeScheduleItem(int $index): void
    {
        $layout = $this->template->layout;
        unset($layout['zones'][$this->zoneId]['schedule'][$index]);
        $layout['zones'][$this->zoneId]['schedule'] = array_values($layout['zones'][$this->zoneId]['schedule']);

        $this->template->update(['layout' => $layout]);
        $this->loadSchedule();
    }

    public function render()
    {
        return view('livewire.content.templates.components.zone-scheduler');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Component;
use Carbon\Carbon;

class ClockWidget extends Component
{
    public string $currentTime = '';
    public string $currentDate = '';
    public string $timezone = 'UTC';
    public bool $showSeconds = true;
    public string $format = 'H:i:s'; // Default 24-hour with seconds
    public bool $showDate = true;
    public string $dateFormat = 'l, F j, Y'; // Default: Monday, January 1, 2024

    public function mount(string $timezone = 'UTC', bool $showSeconds = true, string $format = 'H:i:s', bool $showDate = true, string $dateFormat = 'l, F j, Y')
    {
        $this->timezone = $timezone;
        $this->showSeconds = $showSeconds;
        $this->format = $format;
        $this->showDate = $showDate;
        $this->dateFormat = $dateFormat;
        $this->updateTime();
    }

    public function updateTime()
    {
        try {
            $now = Carbon::now($this->timezone);
            $this->currentTime = $now->format($this->format);
            if ($this->showDate) {
                $this->currentDate = $now->format($this->dateFormat);
            }
        } catch (\Exception $e) {
            // Handle invalid timezone
            $now = Carbon::now('UTC');
            $this->currentTime = $now->format($this->format) . ' (UTC TZ Error)';
            if ($this->showDate) {
                $this->currentDate = $now->format($this->dateFormat);
            }
        }
    }

    public function render()
    {
        return view('livewire.content.widgets.clock-widget');
    }
}

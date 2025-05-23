<?php

declare(strict_types=1);

namespace App\Livewire\Content\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Enums\TemplateCategory;
use Illuminate\Support\Facades\Cache;

#[Layout('layouts.tv')]
#[Title('Live Digital Signage')]
final class TvDisplay extends Component
{
    public array $layoutConfig = [];
    public bool $isEditMode = false;
    public string $currentTime = '';
    public string $currentDate = '';
    public int $refreshInterval = 30; // seconds

    protected $listeners = ['widgetUpdated', 'refreshDisplay'];

    public function mount(): void
    {
        // Load configuration from cache or database
        $this->layoutConfig = Cache::get('signage_layout', $this->getDefaultLayout());
        
        // Initialize time
        $this->updateTime();
    }

    private function getDefaultLayout(): array
    {
        return [
            'grid' => [
                'columns' => 12,
                'rows' => 8,
                'gap' => '4',
            ],
            'widgets' => [
                [
                    'id' => 'main_announcement',
                    'component' => 'content.widgets.announcement-widget',
                    'position' => [
                        'col' => [1, 9],
                        'row' => [1, 5]
                    ],
                    'settings' => [
                        'title' => 'Welcome to Our Company HQ!',
                        'message' => 'We are excited to have you here. Check out the latest updates and events happening this week. Don\'t forget the team lunch on Friday!',
                        'backgroundColor' => '#0F172A',
                        'textColor' => '#E2E8F0',
                        'titleColor' => '#38BDF8',
                        'animation' => 'slide-up'
                    ],
                    'priority' => 1
                ],
                [
                    'id' => 'weather_london',
                    'component' => 'content.widgets.weather-widget',
                    'position' => [
                        'col' => [10, 12],
                        'row' => [1, 3]
                    ],
                    'settings' => [
                        'apiKey' => config('services.openweathermap.key', env('OPENWEATHERMAP_API_KEY')),
                        'location' => 'London, UK',
                        'refreshInterval' => 600,
                        'animation' => 'fade'
                    ],
                    'priority' => 2
                ],
                [
                    'id' => 'clock_local',
                    'component' => 'content.widgets.clock-widget',
                    'position' => [
                        'col' => [10, 12],
                        'row' => [4, 6]
                    ],
                    'settings' => [
                        'timezone' => 'Europe/London',
                        'showSeconds' => true,
                        'format' => 'H:i:s',
                        'showDate' => true,
                        'dateFormat' => 'l, F jS',
                        'animation' => 'zoom'
                    ],
                    'priority' => 3
                ],
                [
                    'id' => 'news_feed',
                    'component' => 'content.widgets.rss-feed-widget',
                    'position' => [
                        'col' => [10, 12],
                        'row' => [6, 8]
                    ],
                    'settings' => [
                        'feedUrl' => 'https://feeds.bbci.co.uk/news/world/rss.xml',
                        'itemCount' => 3,
                        'refreshInterval' => 900,
                        'animation' => 'slide-left'
                    ],
                    'priority' => 4
                ],
                [
                    'id' => 'custom_message',
                    'component' => 'content.widgets.custom-text-widget',
                    'position' => [
                        'col' => [1, 12],
                        'row' => [6, 8]
                    ],
                    'settings' => [
                        'text' => "Follow us on @OurCompany • #Innovation • #FutureTech",
                        'fontSize' => '1.5rem',
                        'textColor' => '#CBD5E1',
                        'backgroundColor' => '#1E293B',
                        'textAlign' => 'center',
                        'padding' => '20px',
                        'animation' => 'marquee'
                    ],
                    'priority' => 5
                ]
            ]
        ];
    }

    public function updateTime(): void
    {
        $now = now();
        $this->currentTime = $now->format('H:i:s');
        $this->currentDate = $now->format('l, F jS, Y');
    }

    public function refreshDisplay(): void
    {
        $this->layoutConfig = $this->getDefaultLayout();
        $this->updateTime();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.pages.tv-display', [
            'layoutConfig' => $this->layoutConfig,
            'isEditMode' => $this->isEditMode,
            'currentTime' => $this->currentTime,
            'currentDate' => $this->currentDate,
            'refreshInterval' => $this->refreshInterval,
        ]);
    }
}

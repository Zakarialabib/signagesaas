<?php

declare(strict_types=1);

namespace App\Livewire\Content\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Enums\TemplateCategory;

#[Layout('layouts.tv')]
#[Title('Digital Signage Display')]
final class TvDisplay extends Component
{
    public array $activeWidgets = [];

    public function mount(): void
    {
        // In a real implementation, this would load from tenant configuration
        // For demo, we'll show a few default widgets
        $this->activeWidgets = [
            'weather' => [
                'category' => TemplateCategory::WEATHER,
                'settings' => [
                    'api_key' => env('DEMO_WEATHER_API_KEY', 'demo_key'),
                    'location' => 'London'
                ]
            ],
            'announcements' => [
                'category' => TemplateCategory::ANNOUNCEMENTS,
                'settings' => []
            ],
            'news' => [
                'category' => TemplateCategory::NEWS,
                'settings' => []
            ],
            'social' => [
                'category' => TemplateCategory::SOCIAL,
                'settings' => []
            ]
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.pages.tv-display', [
            'activeWidgets' => $this->activeWidgets
        ]);
    }
}

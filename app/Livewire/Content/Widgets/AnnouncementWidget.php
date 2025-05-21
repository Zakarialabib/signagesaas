<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Component;
use Livewire\Attributes\Locked;

final class AnnouncementWidget extends Component
{
    #[Locked]
    public array $settings;

    public array $announcements = [];
    public array $marqueeItems = [];

    #[Locked]
    public string $lastUpdated;

    public int $refreshInterval = 300; // 5 minutes
    public int $maxItems = 5;
    public bool $enableScrolling = true;
    public int $scrollSpeed = 5; // seconds per announcement
    public string $scrollDirection = 'vertical'; // vertical or horizontal

    public function mount(array $settings = []): void
    {
        $this->settings = $settings;
        
        // In a real implementation, these would come from tenant configuration
        // For demo, we'll use static data
        $this->announcements = [
            'title' => 'Company All-Hands Meeting',
            'time' => '3:00 PM',
            'location' => 'Main Auditorium',
            'description' => 'All employees are required to attend'
        ];

        $this->marqueeItems = [
            '🚀 New product launch next week!',
            '🏆 Employee of the month: John Smith',
            '📅 Don\'t forget: Benefits enrollment ends Friday'
        ];

        $this->loadData();
    }

    protected function loadData(): void
    {
        // Replace with your actual announcement data source
        // Example: fetch from database, API, etc.
        /*
        try {
            $this->announcements = Announcement::where('active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orderBy('priority', 'desc')
                ->limit($this->maxItems)
                ->get()
                ->map(function ($announcement) {
                    return [
                        'title' => $announcement->title,
                        'content' => $announcement->content,
                        'priority' => $announcement->priority,
                        'type' => $announcement->type,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            throw new \Exception('Error fetching announcements: ' . $e->getMessage());
        }
        */

        // Placeholder / Demo data
        $this->announcements = [
            [
                'title' => 'System Maintenance',
                'content' => 'Scheduled maintenance will be performed this weekend. Please save your work.',
                'priority' => 'high',
                'type' => 'warning'
            ],
            [
                'title' => 'New Feature Release',
                'content' => 'Check out our latest digital signage features in the new update!',
                'priority' => 'medium',
                'type' => 'info'
            ],
            [
                'title' => 'Office Closure',
                'content' => 'The office will be closed for the upcoming holiday.',
                'priority' => 'medium',
                'type' => 'notice'
            ],
            [
                'title' => 'Team Meeting',
                'content' => 'All hands meeting scheduled for tomorrow at 10 AM in the main conference room.',
                'priority' => 'low',
                'type' => 'info'
            ]
        ];

        $this->lastUpdated = now()->diffForHumans();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.announcement-widget', [
            'title' => 'Announcements',
            'category' => 'ANNOUNCEMENT',
            'icon' => '<svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>',
            'announcements' => $this->announcements,
            'lastUpdated' => $this->lastUpdated,
            'error' => $this->error,
            'isLoading' => $this->isLoading,
            'enableScrolling' => $this->enableScrolling,
            'scrollSpeed' => $this->scrollSpeed,
            'scrollDirection' => $this->scrollDirection,
        ]);
    }
} 
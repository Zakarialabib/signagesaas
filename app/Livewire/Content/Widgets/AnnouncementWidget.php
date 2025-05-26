<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Exception;

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

        // Improved demo data with additional fields
        $this->announcements = [
            [
                'title'       => 'Company All-Hands Meeting',
                'time'        => '3:00 PM',
                'location'    => 'Main Auditorium',
                'description' => 'All employees are required to attend',
                'priority'    => 'high',
                'type'        => 'meeting',
            ],
            [
                'title'    => 'System Maintenance',
                'content'  => 'Scheduled maintenance will be performed this weekend. Please save your work.',
                'priority' => 'high',
                'type'     => 'warning',
            ],
            [
                'title'    => 'New Feature Release',
                'content'  => 'Check out our latest digital signage features in the new update!',
                'priority' => 'medium',
                'type'     => 'info',
            ],
            [
                'title'    => 'Office Closure',
                'content'  => 'The office will be closed for the upcoming holiday.',
                'priority' => 'medium',
                'type'     => 'notice',
            ],
            [
                'title'    => 'Team Meeting',
                'content'  => 'All hands meeting scheduled for tomorrow at 10 AM in the main conference room.',
                'priority' => 'low',
                'type'     => 'info',
            ],
        ];

        $this->marqueeItems = [
            '🚀 New product launch next week!',
            '🏆 Employee of the month: John Smith',
            '📅 Don\'t forget: Benefits enrollment ends Friday',
        ];

        $this->loadData();
    }

    protected function loadData(): void
    {
        // Improved error handling and data fetching
        try {
            // $this->announcements = Announcement::where('active', true)
            //     ->where('start_date', '<=', now())
            //     ->where('end_date', '>=', now())
            //     ->orderBy('priority', 'desc')
            //     ->limit($this->maxItems)
            //     ->get()
            //     ->map(function ($announcement) {
            //         return [
            //             'title' => $announcement->title,
            //             'content' => $announcement->content,
            //             'priority' => $announcement->priority,
            //             'type' => $announcement->type,
            //         ];
            //     })
            //     ->toArray();
            // dummy data
            $this->announcements = [
                [
                    'title'       => 'Company All-Hands Meeting',
                    'time'        => '3:00 PM',
                    'location'    => 'Main Auditorium',
                    'description' => 'All employees are required to attend.',
                    'priority'    => 'high',
                    'type'        => 'meeting',
                ],
                [
                    'title'    => 'System Maintenance Scheduled',
                    'content'  => 'Scheduled maintenance will be performed this weekend. Please save your work before Friday evening.',
                    'priority' => 'high',
                    'type'     => 'warning',
                ],
                [
                    'title'    => 'New Feature Release!',
                    'content'  => 'Check out our latest digital signage features in the new update available now!',
                    'priority' => 'medium',
                    'type'     => 'info',
                ],
                [
                    'title'    => 'Office Closure Notice',
                    'content'  => 'The office will be closed on Monday, July 4th for the national holiday.',
                    'priority' => 'medium',
                    'type'     => 'notice',
                ],
                [
                    'title'       => 'Weekly Team Sync',
                    'time'        => '10:00 AM',
                    'location'    => 'Conference Room B',
                    'description' => 'Regular weekly sync meeting for the development team.',
                    'priority'    => 'low',
                    'type'        => 'meeting',
                ],
                [
                    'title'    => 'Reminder: Expense Reports Due',
                    'content'  => 'All expense reports for the last month are due by end of day today.',
                    'priority' => 'low',
                    'type'     => 'notice',
                ],
            ];
        } catch (Exception $e) {
            throw new Exception('Error fetching announcements: '.$e->getMessage());
        }

        $this->lastUpdated = now()->diffForHumans();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.announcement-widget', [
            'title'           => 'Announcements',
            'category'        => 'ANNOUNCEMENT',
            'icon'            => '<svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>',
            'announcements'   => $this->announcements,
            'lastUpdated'     => $this->lastUpdated,
            'enableScrolling' => $this->enableScrolling,
            'scrollSpeed'     => $this->scrollSpeed,
            'scrollDirection' => $this->scrollDirection,
        ]);
    }
}

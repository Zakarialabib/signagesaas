<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;

final class SocialMediaWidget extends BaseWidget
{
    #[Locked]
    public array $feeds = [];

    #[Locked]
    public string $lastUpdated;

    public array $platforms = ['twitter', 'instagram', 'linkedin'];
    public int $refreshInterval = 300; // 5 minutes
    public int $maxItems = 5;
    public array $apiKeys = [];

    protected function loadData(): void
    {
        if (empty($this->platforms)) {
            throw new \Exception('Social Media widget: No platforms selected.');
        }

        // Replace with your actual social media API calls
        // Example implementation would fetch from multiple platforms:
        /*
        $feeds = [];
        foreach ($this->platforms as $platform) {
            if (empty($this->apiKeys[$platform])) {
                continue;
            }

            try {
                switch ($platform) {
                    case 'twitter':
                        // Twitter API v2 call
                        break;
                    case 'instagram':
                        // Instagram Graph API call
                        break;
                    case 'linkedin':
                        // LinkedIn API call
                        break;
                }
            } catch (\Exception $e) {
                Log::error("Error fetching {$platform} feed: " . $e->getMessage());
            }
        }
        */

        // Placeholder / Demo data
        $this->feeds = [
            [
                'platform' => 'twitter',
                'author' => 'TechNews',
                'handle' => '@technews',
                'content' => 'Breaking: New AI breakthrough in digital signage technology! #DigitalSignage #AI',
                'time' => '15m ago',
                'engagement' => ['likes' => 45, 'retweets' => 12]
            ],
            [
                'platform' => 'instagram',
                'author' => 'Digital Marketing Pro',
                'handle' => '@digitalmarketing',
                'content' => 'Check out our latest digital signage installation at Times Square! 🌆 #Marketing',
                'time' => '1h ago',
                'engagement' => ['likes' => 234, 'comments' => 18]
            ],
            [
                'platform' => 'linkedin',
                'author' => 'Sarah Johnson',
                'handle' => 'sarah-johnson',
                'content' => 'Excited to announce our new partnership with leading digital signage providers!',
                'time' => '2h ago',
                'engagement' => ['likes' => 89, 'comments' => 15]
            ]
        ];

        $this->lastUpdated = now()->diffForHumans();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.social-media-widget', [
            'title' => 'Social Media Feed',
            'category' => 'SOCIAL_MEDIA',
            'icon' => '<svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>',
            'feeds' => $this->feeds,
            'lastUpdated' => $this->lastUpdated,
            'error' => $this->error,
            'isLoading' => $this->isLoading,
        ]);
    }
} 
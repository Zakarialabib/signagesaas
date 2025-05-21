<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;

final class NewsWidget extends BaseWidget
{
    #[Locked]
    public array $newsItems = [];

    #[Locked]
    public string $lastUpdated;

    public int $refreshInterval = 300; // 5 minutes
    public ?string $apiKey = null;
    public string $category = 'general';
    public int $maxItems = 5;

    protected function loadData(): void
    {
        if (empty($this->apiKey)) {
            throw new \Exception('News widget: API Key is missing.');
        }

        // Replace with your actual news API call logic
        // Example (requires Guzzle or Laravel HTTP Client):
        /*
        try {
            $response = Http::get("https://newsapi.org/v2/top-headlines", [
                'country' => 'ma', // Morocco
                'category' => $this->category,
                'apiKey' => $this->apiKey,
                'pageSize' => $this->maxItems,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->newsItems = array_map(function($article) {
                    return [
                        'title' => $article['title'],
                        'source' => $article['source']['name'],
                        'time' => \Carbon\Carbon::parse($article['publishedAt'])->diffForHumans(),
                    ];
                }, $data['articles']);
                $this->lastUpdated = now()->diffForHumans();
            } else {
                throw new \Exception('Could not fetch news (' . $response->status() . ')');
            }
        } catch (\Exception $e) {
            throw new \Exception('Error fetching news data: ' . $e->getMessage());
        }
        */

        // Placeholder / Demo data
        $this->newsItems = [
            [
                'title' => 'Tech Conference Announces Keynote Speakers',
                'source' => 'Tech News',
                'time' => '2h ago'
            ],
            [
                'title' => 'Local Community Raises Funds for New Park',
                'source' => 'Community Times',
                'time' => '4h ago'
            ],
            [
                'title' => 'Stock Markets Reach Record Highs',
                'source' => 'Financial Daily',
                'time' => '6h ago'
            ],
            [
                'title' => 'New Study Reveals Benefits of Remote Work',
                'source' => 'Business Insider',
                'time' => '8h ago'
            ]
        ];

        $this->lastUpdated = now()->diffForHumans();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.news-widget', [
            'title' => 'Latest News',
            'category' => 'NEWS',
            'icon' => '<svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8" /></svg>',
            'newsItems' => $this->newsItems,
            'lastUpdated' => $this->lastUpdated,
            'error' => $this->error,
            'isLoading' => $this->isLoading,
        ]);
    }
} 
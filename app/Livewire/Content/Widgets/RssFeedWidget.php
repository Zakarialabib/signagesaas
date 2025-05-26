<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

 // For parsing XML

class RssFeedWidget extends Component
{
    public string $feedUrl = '';
    public int $itemCount = 5;
    public array $feedItems = [];
    public string $error = '';
    public int $refreshInterval = 900; // 15 minutes

    public function mount(string $feedUrl, int $itemCount = 5, int $refreshInterval = 900)
    {
        $this->feedUrl = $feedUrl;
        $this->itemCount = $itemCount > 0 ? $itemCount : 5;
        $this->refreshInterval = $refreshInterval > 0 ? $refreshInterval : 900;
        $this->fetchFeed();
    }

    public function fetchFeed()
    {
        $this->error = '';
        $this->feedItems = [];

        if (empty($this->feedUrl)) {
            $this->error = 'RSS Feed URL is not configured.';

            return;
        }

        try {
            $response = Http::timeout(15)->get($this->feedUrl);

            if ($response->successful()) {
                $xmlString = $response->body();
                // Suppress errors during XML parsing, then check result
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($xmlString);
                libxml_clear_errors();

                if ($xml === false) {
                    $this->error = 'Failed to parse XML from feed.';
                    Log::error("RSS Feed XML Parse Error for URL: {$this->feedUrl}");

                    return;
                }

                $items = [];

                // Common RSS/Atom structures
                if (isset($xml->channel->item)) { // RSS
                    foreach ($xml->channel->item as $item) {
                        $items[] = [
                            'title'       => (string) $item->title,
                            'link'        => (string) $item->link,
                            'description' => strip_tags((string) ($item->description ?? '')),
                            'pubDate'     => isset($item->pubDate) ? (string) $item->pubDate : null,
                        ];

                        if (count($items) >= $this->itemCount) {
                            break;
                        }
                    }
                } elseif (isset($xml->entry)) { // Atom
                    foreach ($xml->entry as $entry) {
                        $items[] = [
                            'title'       => (string) $entry->title,
                            'link'        => (string) ($entry->link['href'] ?? $entry->link[0]['href'] ?? ''),
                            'description' => strip_tags((string) ($entry->summary ?? $entry->content ?? '')),
                            'pubDate'     => isset($entry->updated) ? (string) $entry->updated : (isset($entry->published) ? (string) $entry->published : null),
                        ];

                        if (count($items) >= $this->itemCount) {
                            break;
                        }
                    }
                } else {
                    $this->error = 'Unsupported feed format or no items found.';
                }
                $this->feedItems = $items;
            } else {
                $this->error = 'Failed to fetch RSS feed. Status: '.$response->status();
                Log::warning("RSS Feed Fetch Error ({$response->status()}) for URL: {$this->feedUrl}");
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error = 'Connection error while fetching RSS feed.';
            Log::error("RSS Feed Connection Exception for URL {$this->feedUrl}: ".$e->getMessage());
        } catch (Exception $e) {
            $this->error = 'An unexpected error occurred while fetching the RSS feed.';
            Log::error("RSS Feed General Exception for URL {$this->feedUrl}: ".$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.content.widgets.rss-feed-widget');
    }
}

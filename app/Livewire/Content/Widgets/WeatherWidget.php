<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Http;

final class WeatherWidget extends BaseWidget
{
    #[Locked]
    public string $weather = 'Loading weather...';

    public ?string $apiKey = null;
    public string $location = 'DefaultCity';
    public int $refreshInterval = 300; // 5 minutes

    protected function loadData(): void
    {
        if (empty($this->apiKey)) {
            throw new \Exception('Weather widget: API Key is missing.');
        }
        if (empty($this->location)) {
            throw new \Exception('Weather widget: Location is missing.');
        }

        // Replace with your actual weather API call logic
        // Example (requires Guzzle or Laravel HTTP Client):
        /*
        try {
            // Ensure you have a valid API endpoint and key structure
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $this->location,
                'appid' => $this->apiKey,
                'units' => 'metric' // For Celsius
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $description = $data['weather'][0]['description'] ?? 'N/A';
                $temp = $data['main']['temp'] ?? 'N/A';
                $this->weather = ucfirst($description) . ', ' . $temp . '°C';
            } else {
                throw new \Exception('Could not fetch weather (' . $response->status() . ')');
            }
        } catch (\Illuminate\Http\Client\RequestException | \Exception $e) {
            throw new \Exception('Error fetching weather data: ' . $e->getMessage());
        }
        */

        // Placeholder / Demo data for now if no real API call
        $this->weather = "Fake Weather for {$this->location}: Sunny, 25°C";
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.weather-widget', [
            'title' => 'Weather',
            'category' => 'WEATHER',
            'icon' => '<svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h10a4 4 0 100-8 5.978 5.978 0 00-1.528.2A6 6 0 003 15z" /></svg>',
            'weather' => $this->weather,
            'error' => $this->error,
            'isLoading' => $this->isLoading,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Content\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Enums\TemplateCategory;

#[Layout('layouts.tv')]
final class WidgetPage extends Component
{
    public TemplateCategory $category;
    public ?string $weatherApiKey = null;
    public ?string $weatherLocation = null;
    // You can add more properties here if other widgets need specific data passed from the page
    public string $rssFeedUrl = 'https://feeds.bbci.co.uk/news/world/rss.xml'; // Example default for RSS
    public int $rssItemCount = 5;

    public string $defaultAnnouncementTitle = "Important Update";
    public string $defaultAnnouncementMessage = "Please be advised of the new company policy effective next Monday. Details will be shared via email.";
    public string $defaultCustomText = "Welcome to Our Digital Signage Display!";
  
    public bool $isWeatherWidget = false;
    public ?string $announcementText = null;
    public bool $isAnnouncementWidget = false;
    
    public function mount($category): void
    {
        $this->category = TemplateCategory::from($category->value);
    
        // if ($this->category === TemplateCategory::WEATHER) {
        //     $this->isWeatherWidget = true;
        //     $this->weatherApiKey = session('demo_weather_api_key', 'YOUR_FALLBACK_API_KEY');
        //     $this->weatherLocation = session('demo_weather_location', 'London');
        // }
    
        if ($this->category === TemplateCategory::ANNOUNCEMENT) {
            $this->isAnnouncementWidget = true;
            $this->announcementText = session('demo_announcement_text', 'Welcome to our Digital Signage!');
        }

        if ($this->category === TemplateCategory::WEATHER) {
            $this->isWeatherWidget = true;
            // Find a template (e.g., a default "single weather widget" template for the tenant)
            // This logic needs to be defined: How do we pick THE template to show for this category?
            // For simplicity, let's assume we have a way to get a relevant $template object.
            // $template = Template::where('category', TemplateCategory::WEATHER)
            //                    ->where('is_default_for_category', true) // Hypothetical
            //                    ->first(); 

            // For demo, let's assume $template is loaded.
            // You'll need to implement actual template loading logic.
            // If a $template is loaded:
            // foreach ($template->layout['zones'] ?? [] as $zone) {
            //     // Check if this zone is configured as a weather widget
            //     // This check needs to match how you define it in TemplateConfigurator
            //     $isConfiguredAsWeather = ($zone['settings']['widget_type'] ?? null) === TemplateCategory::WEATHER->value || 
            //                              ($zone['type'] ?? null) === \App\Enums\ContentType::WEATHER->value;

            //     if ($isConfiguredAsWeather) {
            //         $this->weatherApiKey = $zone['settings']['weather_api_key'] ?? null;
            //         $this->weatherLocation = $zone['settings']['weather_location'] ?? null;
            //         break; // Found the first weather zone
            //     }
            // }

            // --- SIMPLIFIED DEMO FOR NOW ---
            // Manually set for demo until template loading logic is solid
            // In a real scenario, these would come from the $template's zone settings
            $this->weatherApiKey = session('demo_weather_api_key', 'YOUR_FALLBACK_API_KEY');
            $this->weatherLocation = session('demo_weather_location', 'London');
            // You'd need to ensure the TemplateConfigurator actually saves 'widget_type'
            // for the zone to be identified here.
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.pages.widget-page', [
            'category' => $this->category,
            'isWeatherWidget' => $this->isWeatherWidget,
            'weatherApiKey' => $this->weatherApiKey,
            'weatherLocation' => $this->weatherLocation,
        ]);
    }
}

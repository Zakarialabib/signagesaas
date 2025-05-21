<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Tenant\Models\Content;
use App\Tenant\Models\Screen;
use App\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        $screen = Screen::factory()->create();

        return [
            'tenant_id'   => fn () => $screen->tenant_id,
            'name'        => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'type'        => fake()->randomElement(ContentType::cases()),
            'screen_id'   => $screen->id,
            'status'      => fake()->randomElement(ContentStatus::cases()),
            'duration'    => fake()->numberBetween(5, 60),
            'order'       => fake()->numberBetween(1, 20),
            'start_date'  => fake()->optional()->dateTimeBetween('-1 week', '+1 week'),
            'end_date'    => fake()->optional()->dateTimeBetween('+1 week', '+4 weeks'),
            'settings'    => [
                'transition_in'       => fake()->randomElement(['fade', 'slide', 'zoom']),
                'transition_out'      => fake()->randomElement(['fade', 'slide', 'zoom']),
                'transition_duration' => fake()->numberBetween(300, 1000),
            ],
            'content_data' => [],
        ];
    }

    public function configure(): self
    {
        return $this->afterMaking(function (Content $content) {
            // Set content_data based on type
            $content->content_data = match ($content->type) {
                ContentType::IMAGE   => $this->generateImageData(),
                ContentType::VIDEO   => $this->generateVideoData(),
                ContentType::HTML    => $this->generateHtmlData(),
                ContentType::URL     => $this->generateUrlData(),
                ContentType::TEXT    => $this->generateTextData(),
                ContentType::WEATHER => $this->generateWeatherData(),
                ContentType::RSS     => $this->generateRssData(),
                ContentType::SOCIAL  => $this->generateSocialFeedData(),
                default              => [],
            };
        });
    }

    public function forTenant(Tenant $tenant): self
    {
        return $this->state([
            'tenant_id' => $tenant->id,
        ]);
    }

    public function active(): self
    {
        return $this->state([
            'status'     => ContentStatus::ACTIVE,
            'start_date' => now()->subDay(),
            'end_date'   => now()->addDays(30),
        ]);
    }

    public function inactive(): self
    {
        return $this->state([
            'status' => ContentStatus::INACTIVE,
        ]);
    }

    public function draft(): self
    {
        return $this->state([
            'status' => ContentStatus::DRAFT,
        ]);
    }

    public function scheduled(): self
    {
        return $this->state([
            'status'     => ContentStatus::SCHEDULED,
            'start_date' => now()->addDays(5),
            'end_date'   => now()->addDays(15),
        ]);
    }

    public function expired(): self
    {
        return $this->state([
            'status'     => ContentStatus::EXPIRED,
            'start_date' => now()->subDays(30),
            'end_date'   => now()->subDays(1),
        ]);
    }

    public function forScreen(Screen $screen): self
    {
        return $this->state([
            'screen_id' => $screen->id,
            'tenant_id' => $screen->tenant_id,
        ]);
    }

    public function image(): self
    {
        return $this->state([
            'type'         => ContentType::IMAGE,
            'content_data' => $this->generateImageData(),
            'duration'     => fake()->numberBetween(5, 15),
        ]);
    }

    public function video(): self
    {
        return $this->state([
            'type'         => ContentType::VIDEO,
            'content_data' => $this->generateVideoData(),
            'duration'     => fake()->numberBetween(15, 90),
        ]);
    }

    public function html(): self
    {
        return $this->state([
            'type'         => ContentType::HTML,
            'content_data' => $this->generateHtmlData(),
            'duration'     => fake()->numberBetween(10, 30),
        ]);
    }

    public function url(): self
    {
        return $this->state([
            'type'         => ContentType::URL,
            'content_data' => $this->generateUrlData(),
            'duration'     => fake()->numberBetween(20, 60),
        ]);
    }

    public function text(): self
    {
        return $this->state([
            'type'         => ContentType::TEXT,
            'content_data' => $this->generateTextData(),
            'duration'     => fake()->numberBetween(5, 20),
        ]);
    }

    public function weather(): self
    {
        return $this->state([
            'type'         => ContentType::WEATHER,
            'content_data' => $this->generateWeatherData(),
            'duration'     => fake()->numberBetween(10, 20),
        ]);
    }

    public function rss(): self
    {
        return $this->state([
            'type'         => ContentType::RSS,
            'content_data' => $this->generateRssData(),
            'duration'     => fake()->numberBetween(15, 45),
        ]);
    }

    public function socialFeed(): self
    {
        return $this->state([
            'type'         => ContentType::SOCIAL,
            'content_data' => $this->generateSocialFeedData(),
            'duration'     => fake()->numberBetween(20, 40),
        ]);
    }

    public function withDuration(int $seconds): self
    {
        return $this->state([
            'duration' => $seconds,
        ]);
    }

    public function withOrder(int $order): self
    {
        return $this->state([
            'order' => $order,
        ]);
    }

    private function generateImageData(): array
    {
        return [
            'url'      => fake()->imageUrl(1920, 1080),
            'alt_text' => fake()->sentence(),
            'caption'  => fake()->optional()->sentence(),
        ];
    }

    private function generateVideoData(): array
    {
        return [
            'url'       => 'https://www.example.com/videos/'.fake()->slug().'.mp4',
            'thumbnail' => fake()->imageUrl(640, 480),
            'duration'  => fake()->numberBetween(10, 120),
            'autoplay'  => true,
            'loop'      => true,
            'muted'     => true,
        ];
    }

    private function generateHtmlData(): array
    {
        return [
            'html' => '<div style="text-align:center;padding:30px;background-color:#'.
                fake()->hexColor().'"><h1>'.fake()->words(3, true).'</h1><p>'.
                fake()->paragraph().'</p></div>',
            'css' => 'h1 { color: #'.fake()->hexColor().'; }',
            'js'  => 'console.log("Content loaded");',
        ];
    }

    private function generateUrlData(): array
    {
        return [
            'url'              => fake()->url(),
            'refresh_interval' => fake()->numberBetween(30, 300),
            'zoom_level'       => fake()->randomFloat(1, 0.5, 2.0),
        ];
    }

    private function generateTextData(): array
    {
        return [
            'title'            => fake()->sentence(),
            'body'             => fake()->paragraphs(3, true),
            'font_size'        => fake()->numberBetween(14, 36),
            'font_family'      => fake()->randomElement(['Arial', 'Roboto', 'Helvetica', 'Open Sans']),
            'background_color' => '#'.fake()->hexColor(),
            'text_color'       => '#'.fake()->hexColor(),
        ];
    }

    private function generateWeatherData(): array
    {
        return [
            'location'      => fake()->city(),
            'api_key'       => 'sample_key_'.fake()->md5(),
            'units'         => fake()->randomElement(['metric', 'imperial']),
            'show_forecast' => fake()->boolean(),
            'days_to_show'  => fake()->numberBetween(1, 5),
        ];
    }

    private function generateRssData(): array
    {
        return [
            'feed_url'         => 'https://example.com/rss/'.fake()->slug(),
            'item_count'       => fake()->numberBetween(3, 10),
            'show_images'      => fake()->boolean(),
            'show_description' => fake()->boolean(),
            'refresh_interval' => fake()->numberBetween(300, 3600),
        ];
    }

    private function generateSocialFeedData(): array
    {
        return [
            'platform'         => fake()->randomElement(['twitter', 'instagram', 'facebook']),
            'handle'           => '@'.fake()->userName(),
            'hashtag'          => '#'.fake()->word(),
            'post_count'       => fake()->numberBetween(3, 8),
            'refresh_interval' => fake()->numberBetween(300, 1800),
            'show_images'      => true,
            'show_profile'     => fake()->boolean(),
        ];
    }
}

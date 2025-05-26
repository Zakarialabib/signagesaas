<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Tenant\Models\Asset;
use App\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['image', 'video', 'audio', 'document']);
        $path = match ($type) {
            'image' => fake()->imageUrl(1920, 1080, 'cats', true, 'Placeholder'),
            'video' => 'https://example.com/videos/'.fake()->uuid().'.mp4',
            'audio' => 'https://example.com/audio/'.fake()->uuid().'.mp3',
            default => fake()->filePath(),
        };

        return [
            'tenant_id' => Tenant::factory(),
            'name'      => fake()->words(2, true),
            'type'      => $type,
            'path'      => $path,
            'disk'      => 'public',
            'mime_type' => $this->getMimeType(),
            'size'      => fake()->numberBetween(1000, 10000000),
            'metadata'  => [
                'dimensions'    => $this->getDimensions(),
                'duration'      => $this->getDuration(),
                'created_by'    => fake()->name(),
                'last_modified' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            ],
        ];
    }

    private function getMimeType(): string
    {
        $mimeTypes = [
            'image'    => ['image/jpeg', 'image/png', 'image/gif'],
            'video'    => ['video/mp4', 'video/webm'],
            'audio'    => ['audio/mpeg', 'audio/wav'],
            'document' => ['application/pdf', 'application/msword'],
        ];

        return fake()->randomElement($mimeTypes[fake()->randomElement(array_keys($mimeTypes))]);
    }

    private function getDimensions(): ?array
    {
        if (in_array($this->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm'])) {
            return [
                'width'  => fake()->numberBetween(800, 1920),
                'height' => fake()->numberBetween(600, 1080),
            ];
        }

        return null;
    }

    private function getDuration(): ?int
    {
        if (in_array($this->getMimeType(), ['video/mp4', 'video/webm', 'audio/mpeg', 'audio/wav'])) {
            return fake()->numberBetween(10, 300);
        }

        return null;
    }
}

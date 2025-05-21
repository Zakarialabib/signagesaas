<?php

declare(strict_types=1);

namespace App\AI\DTOs;

final readonly class ContentGenerationRequest
{
    public function __construct(
        public string $contentType,
        public string $prompt,
        public string $language,
        public array $parameters = [],
        public ?string $tone = null,
        public ?string $audience = null,
    ) {
    }
}

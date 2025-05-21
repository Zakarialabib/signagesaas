<?php

declare(strict_types=1);

namespace App\AI\Services;

use App\AI\DTOs\ContentGenerationRequest;
use App\AI\DTOs\ContentGenerationResponse;
use App\Contracts\AI\ContentGeneratorInterface;
use App\AI\Prompts\MarketingPrompts;
use Illuminate\Support\Facades\Cache;
use Throwable;

final readonly class ContentGenerationService implements ContentGeneratorInterface
{
    public function __construct(
        private LLMClientInterface $llmClient,
        private PromptTemplateService $promptService
    ) {
    }

    public function generate(ContentGenerationRequest $request): ContentGenerationResponse
    {
        try {
            // Check cache first
            $cacheKey = $this->generateCacheKey($request);

            return Cache::remember($cacheKey, now()->addHours(24), function () use ($request) {
                $prompt = $this->promptService->compile(
                    MarketingPrompts::BANNER_GENERATION,
                    $request->parameters
                );

                $response = $this->llmClient->complete([
                    'prompt'      => $prompt,
                    'temperature' => 0.7,
                    'max_tokens'  => 500,
                ]);

                return new ContentGenerationResponse(
                    content: $response->content,
                    metadata: $response->metadata,
                    promptTokens: $response->usage->promptTokens,
                    completionTokens: $response->usage->completionTokens
                );
            });
        } catch (Throwable $e) {
            report($e);

            throw new AIGenerationException(
                'Failed to generate content: '.$e->getMessage(),
                previous: $e
            );
        }
    }

    private function generateCacheKey(ContentGenerationRequest $request): string
    {
        return sprintf(
            'ai:content:%s:%s',
            md5($request->prompt),
            $request->contentType
        );
    }
}

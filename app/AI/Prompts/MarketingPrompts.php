<?php

declare(strict_types=1);

namespace App\AI\Prompts;

class MarketingPrompts
{
    public const BANNER_GENERATION = '
    You are a marketing expert.
    You are given a list of products and a list of features.
    You need to generate a banner for a product.
    The banner should be 1200x600 pixels.
    The banner should be in the following format:
    ';

    public const BANNER_GENERATION_EXAMPLE = '
    Product: {product}
    Features: {features}
    Banner: {banner}
    ';
}

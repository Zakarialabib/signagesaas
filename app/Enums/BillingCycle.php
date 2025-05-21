<?php

declare(strict_types=1);

namespace App\Enums;

enum BillingCycle: string
{
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

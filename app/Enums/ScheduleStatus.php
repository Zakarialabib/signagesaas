<?php

declare(strict_types=1);

namespace App\Enums;

enum ScheduleStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case COMPLETED = 'completed';
    case SCHEDULED = 'scheduled';
    case PAUSED = 'paused';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT     => 'Draft',
            self::ACTIVE    => 'Active',
            self::INACTIVE  => 'Inactive',
            self::COMPLETED => 'Completed',
            self::SCHEDULED => 'Scheduled',
            self::PAUSED    => 'Paused',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT     => 'gray',
            self::ACTIVE    => 'green',
            self::INACTIVE  => 'red',
            self::COMPLETED => 'blue',
            self::SCHEDULED => 'indigo',
            self::PAUSED    => 'yellow',
        };
    }
}

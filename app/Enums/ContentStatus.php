<?php

declare(strict_types=1);

namespace App\Enums;

enum ContentStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SCHEDULED = 'scheduled';
    case ARCHIVED = 'archived';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT     => 'Draft',
            self::ACTIVE    => 'Active',
            self::INACTIVE  => 'Inactive',
            self::SCHEDULED => 'Scheduled',
            self::ARCHIVED  => 'Archived',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT     => 'gray',
            self::ACTIVE    => 'green',
            self::INACTIVE  => 'red',
            self::SCHEDULED => 'blue',
            self::ARCHIVED  => 'yellow',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::DRAFT     => 'Content is being edited and not ready for display',
            self::ACTIVE    => 'Content is currently active and can be displayed',
            self::INACTIVE  => 'Content is temporarily disabled',
            self::SCHEDULED => 'Content is scheduled for future display',
            self::ARCHIVED  => 'Content is no longer in use but preserved',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = str_replace('_', ' ', ucwords(strtolower($case->name), '_'));
        }

        return $options;
    }
}

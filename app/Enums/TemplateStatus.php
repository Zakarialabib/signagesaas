<?php

declare(strict_types=1);

namespace App\Enums;

enum TemplateStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case DEPRECATED = 'deprecated';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT      => 'Draft',
            self::PUBLISHED  => 'Published',
            self::ARCHIVED   => 'Archived',
            self::DEPRECATED => 'Deprecated',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT      => 'gray',
            self::PUBLISHED  => 'green',
            self::ARCHIVED   => 'red',
            self::DEPRECATED => 'yellow',
        };
    }
}

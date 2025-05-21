<?php

declare(strict_types=1);

namespace App\Enums;

enum ContentType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
    case HTML = 'html';
    case URL = 'url';
    case RSS = 'rss';
    case WEATHER = 'weather';
    case SOCIAL = 'social';
    case CALENDAR = 'calendar';
    case CUSTOM = 'custom';
    case SLIDESHOW = 'slideshow';
    case AUDIO = 'audio';
    case PDF = 'pdf';
    case DOCUMENT = 'document';
    case EXCEL = 'excel';
    case POWERPOINT = 'powerpoint';
    case TEXT = 'text';

    public function label(): string
    {
        return match ($this) {
            self::IMAGE    => 'Image',
            self::VIDEO    => 'Video',
            self::HTML     => 'HTML',
            self::URL      => 'URL/Website',
            self::RSS      => 'RSS Feed',
            self::WEATHER  => 'Weather Widget',
            self::SOCIAL   => 'Social Media Feed',
            self::CALENDAR => 'Calendar/Events',
            self::CUSTOM   => 'Custom Content',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::IMAGE    => 'Display static images like photos, graphics, or advertisements',
            self::VIDEO    => 'Play video content with or without audio',
            self::HTML     => 'Custom HTML content with styling and interactivity',
            self::URL      => 'Display external websites or web applications',
            self::RSS      => 'Show dynamic content from RSS feeds',
            self::WEATHER  => 'Display weather information and forecasts',
            self::SOCIAL   => 'Show social media feeds and updates',
            self::CALENDAR => 'Display events, schedules and calendars',
            self::CUSTOM   => 'Custom content type for specific needs',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::IMAGE    => 'image',
            self::VIDEO    => 'video',
            self::HTML     => 'code',
            self::URL      => 'link',
            self::RSS      => 'rss',
            self::WEATHER  => 'cloud',
            self::SOCIAL   => 'share',
            self::CALENDAR => 'calendar',
            self::CUSTOM   => 'extension',
        };
    }

    public function getAllowedFileTypes(): array
    {
        return match ($this) {
            self::IMAGE => ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
            self::VIDEO => ['video/mp4', 'video/webm', 'video/ogg'],
            self::HTML  => ['text/html', 'text/plain'],
            default     => [],
        };
    }

    public function getMaxFileSize(): int
    {
        return match ($this) {
            self::IMAGE => 5 * 1024 * 1024, // 5MB
            self::VIDEO => 100 * 1024 * 1024, // 100MB
            default     => 1 * 1024 * 1024, // 1MB
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

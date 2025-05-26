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
    case PRODUCT_LIST = 'product_list';
    case MENU = 'menu';

    public function label(): string
    {
        return match ($this) {
            self::IMAGE        => 'Image',
            self::VIDEO        => 'Video',
            self::HTML         => 'HTML',
            self::URL          => 'URL/Website',
            self::RSS          => 'RSS Feed',
            self::WEATHER      => 'Weather Widget',
            self::SOCIAL       => 'Social Media Feed',
            self::CALENDAR     => 'Calendar/Events',
            self::CUSTOM       => 'Custom Content',
            self::SLIDESHOW    => 'Slideshow',
            self::AUDIO        => 'Audio',
            self::PDF          => 'PDF Document',
            self::DOCUMENT     => 'Document (Word, etc.)',
            self::EXCEL        => 'Excel Spreadsheet',
            self::POWERPOINT   => 'PowerPoint Presentation',
            self::TEXT         => 'Plain Text',
            self::PRODUCT_LIST => 'Product List',
            self::MENU         => 'Menu/Service List',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::IMAGE        => 'Display static images like photos, graphics, or advertisements',
            self::VIDEO        => 'Play video content with or without audio',
            self::HTML         => 'Custom HTML content with styling and interactivity',
            self::URL          => 'Display external websites or web applications',
            self::RSS          => 'Show dynamic content from RSS feeds',
            self::WEATHER      => 'Display weather information and forecasts',
            self::SOCIAL       => 'Show social media feeds and updates',
            self::CALENDAR     => 'Display events, schedules and calendars',
            self::CUSTOM       => 'Custom content type for specific needs',
            self::SLIDESHOW    => 'A sequence of images or slides',
            self::AUDIO        => 'Audio content like music or podcasts',
            self::PDF          => 'Embeddable PDF documents',
            self::DOCUMENT     => 'General document files (e.g., .doc, .docx)',
            self::EXCEL        => 'Spreadsheet files (e.g., .xls, .xlsx)',
            self::POWERPOINT   => 'Presentation files (e.g., .ppt, .pptx)',
            self::TEXT         => 'Simple plain text content',
            self::PRODUCT_LIST => 'Structured list of products with details like name, price, description.',
            self::MENU         => 'Structured list for menus, services, or offers with name, price, description.',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::IMAGE        => 'photograph', // Using Heroicons names for consistency
            self::VIDEO        => 'video-camera',
            self::HTML         => 'code-bracket',
            self::URL          => 'link',
            self::RSS          => 'rss',
            self::WEATHER      => 'cloud',
            self::SOCIAL       => 'share',
            self::CALENDAR     => 'calendar-days',
            self::CUSTOM       => 'puzzle-piece',
            self::SLIDESHOW    => 'rectangle-stack',
            self::AUDIO        => 'musical-note',
            self::PDF          => 'document-text',
            self::DOCUMENT     => 'document',
            self::EXCEL        => 'table-cells',
            self::POWERPOINT   => 'presentation-chart-line',
            self::TEXT         => 'bars-3-bottom-left',
            self::PRODUCT_LIST => 'list-bullet',
            self::MENU         => 'bars-4', // Or 'clipboard-document-list'
        };
    }

    public function getAllowedFileTypes(): array
    {
        return match ($this) {
            self::IMAGE      => ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'],
            self::VIDEO      => ['video/mp4', 'video/webm', 'video/ogg'],
            self::HTML       => ['text/html', 'text/plain'],
            self::AUDIO      => ['audio/mpeg', 'audio/ogg', 'audio/wav'],
            self::PDF        => ['application/pdf'],
            self::DOCUMENT   => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text'],
            self::EXCEL      => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.oasis.opendocument.spreadsheet'],
            self::POWERPOINT => ['application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.presentation'],
            self::TEXT       => ['text/plain'],
            // For JSON-based types, file upload isn't the primary input method, but we can allow .json
            self::PRODUCT_LIST, self::MENU, self::CUSTOM, self::WEATHER, self::SOCIAL, self::CALENDAR, self::URL, self::RSS, self::SLIDESHOW => ['application/json'],
            default => [],
        };
    }

    public function getMaxFileSize(): int // in bytes
    {
        return match ($this) {
            self::IMAGE => 10 * 1024 * 1024, // 10MB
            self::VIDEO => 200 * 1024 * 1024, // 200MB
            self::AUDIO => 50 * 1024 * 1024, // 50MB
            self::PDF   => 25 * 1024 * 1024, // 25MB
            self::DOCUMENT, self::EXCEL, self::POWERPOINT => 25 * 1024 * 1024, // 25MB
            self::TEXT => 1 * 1024 * 1024, // 1MB
            // For JSON-based types, file size is less critical if entered via UI, but for uploads:
            self::PRODUCT_LIST, self::MENU, self::CUSTOM => 5 * 1024 * 1024, // 5MB for JSON data
            default => 2 * 1024 * 1024, // 2MB for others like URL, RSS (config data)
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
            // Use the label() method for consistency if available, otherwise fallback
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}

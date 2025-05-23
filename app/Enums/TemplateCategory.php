<?php

declare(strict_types=1);

namespace App\Enums;

enum TemplateCategory: string
{
    case MENU = 'menu';
    case ANNOUNCEMENT = 'announcement';
    case RESTAURANT = 'restaurant';
    case CORPORATE = 'corporate';
    case SOCIAL_MEDIA = 'social_media';
    case NEWS = 'news';
    case WEATHER = 'weather';
    case CALENDAR = 'calendar';
    case CUSTOM = 'custom';
    case RETAIL = 'retail';
    case EVENTS = 'events';
    case HOSPITALITY = 'hospitality';
    case TRANSPORTATION = 'transportation';
    case BANKING = 'banking';
    case HEALTHCARE = 'healthcare';
    case EDUCATION = 'education';
    case GOVERNMENT = 'government';
    case OTHER = 'other';
    case SOCIAL = 'social';
    case ANNOUNCEMENTS = 'announcements';
    case CLOCK = 'clock';
    case RSS_FEED = 'rss_feed';

    public function label(): string
    {
        return match ($this) {
            self::MENU           => 'Menu Board',
            self::ANNOUNCEMENT   => 'Announcement',
            self::RESTAURANT     => 'Restaurant Menu Board',
            self::CORPORATE      => 'Corporate',
            self::SOCIAL_MEDIA   => 'Social Media Wall',
            self::NEWS           => 'News Feed',
            self::WEATHER        => 'Weather Display',
            self::CALENDAR       => 'Calendar/Events',
            self::CUSTOM         => 'Custom Template',
            self::RETAIL         => 'Retail Template',
            self::EVENTS         => 'Events',
            self::HOSPITALITY    => 'Hospitality',
            self::TRANSPORTATION => 'Transportation',
            self::BANKING        => 'Banking',
            self::HEALTHCARE     => 'Healthcare',
            self::EDUCATION      => 'Education',
            self::GOVERNMENT     => 'Government',
            self::OTHER          => 'Other',
            self::SOCIAL         => 'Social Media',
            self::ANNOUNCEMENTS  => 'Announcements',
            self::CLOCK          => 'Clock',
            self::RSS_FEED       => 'RSS Feed',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::MENU           => 'Display menu items with prices and descriptions',
            self::ANNOUNCEMENT   => 'Share important announcements and updates',
            self::RESTAURANT     => 'Display restaurant menu items with prices and descriptions',
            self::CORPORATE      => 'Display corporate information and updates',
            self::SOCIAL_MEDIA   => 'Show social media feeds and updates',
            self::NEWS           => 'Display news headlines and articles',
            self::WEATHER        => 'Show weather information and forecasts',
            self::CALENDAR       => 'Display events, schedules and calendars',
            self::CUSTOM         => 'Custom template for specific needs',
            self::RETAIL         => 'Retail template for specific needs',
            self::EVENTS         => 'Display events and schedules',
            self::HOSPITALITY    => 'Display hospitality information and updates',
            self::TRANSPORTATION => 'Display transportation information and updates',
            self::BANKING        => 'Display banking information and updates',
            self::HEALTHCARE     => 'Display healthcare information and updates',
            self::EDUCATION      => 'Display education information and updates',
            self::GOVERNMENT     => 'Display government information and updates',
            self::OTHER          => 'Other template category',
            self::SOCIAL         => 'Display social media feeds and updates',
            self::ANNOUNCEMENTS  => 'Show important announcements and alerts',
            self::CLOCK          => 'Display a clock',
            self::RSS_FEED       => 'Display RSS Feed',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::MENU           => 'menu',
            self::ANNOUNCEMENT   => 'announcement',
            self::RESTAURANT     => 'restaurant',
            self::CORPORATE      => 'corporate',
            self::SOCIAL_MEDIA   => 'share',
            self::NEWS           => 'newspaper',
            self::WEATHER        => 'cloud',
            self::CALENDAR       => 'calendar',
            self::CUSTOM         => 'code',
            self::RETAIL         => 'retail',
            self::EVENTS         => 'calendar',
            self::HOSPITALITY    => 'hotel',
            self::TRANSPORTATION => 'car',
            self::BANKING        => 'bank',
            self::HEALTHCARE     => 'heart',
            self::EDUCATION      => 'school',
            self::GOVERNMENT     => 'building',
            self::OTHER          => 'dots-horizontal',
            self::SOCIAL         => '📱',
            self::ANNOUNCEMENTS  => '📢',
            self::CLOCK          => '🕒',
            self::RSS_FEED       => 'rss',
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

enum DeviceType: string
{
    case MEDIA_PLAYER = 'media-player';
    case SMART_TV = 'smart-tv';
    case LED_DISPLAY = 'led-display';
    case KIOSK = 'kiosk';
    case TABLET = 'tablet';
    case RASPBERRY_PI = 'raspberry-pi';
    case ANDROID = 'android';
    case ANDROID_TV = 'android-tv';
    case IPAD = 'ipad';
    case IPHONE = 'iphone';
    case WINDOWS = 'windows';

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

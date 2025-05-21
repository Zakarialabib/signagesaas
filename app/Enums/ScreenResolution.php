<?php

declare(strict_types=1);

namespace App\Enums;

enum ScreenResolution: string
{
    case FULL_HD = '1920x1080';
    case UHD_4K = '3840x2160';
    case HD = '1280x720';
    case HD_PLUS = '1366x768';
    case XGA = '1024x768';
    case PORTRAIT_FULL_HD = '1080x1920';
    case PORTRAIT_HD = '720x1280';
    case PORTRAIT_HD_PLUS = '768x1366';

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
            $options[$case->value] = match ($case) {
                self::FULL_HD          => 'Full HD (1920x1080)',
                self::UHD_4K           => '4K (3840x2160)',
                self::HD               => 'HD (1280x720)',
                self::HD_PLUS          => 'HD+ (1366x768)',
                self::XGA              => 'XGA (1024x768)',
                self::PORTRAIT_FULL_HD => 'Portrait Full HD (1080x1920)',
                self::PORTRAIT_HD      => 'Portrait HD (720x1280)',
                self::PORTRAIT_HD_PLUS => 'Portrait HD+ (768x1366)',
            };
        }

        return $options;
    }

    /** Get the orientation based on resolution */
    public function getOrientation(): ScreenOrientation
    {
        return match ($this) {
            self::PORTRAIT_FULL_HD, self::PORTRAIT_HD, self::PORTRAIT_HD_PLUS => ScreenOrientation::PORTRAIT,
            default => ScreenOrientation::LANDSCAPE,
        };
    }

    /** Get landscape resolutions */
    public static function landscapeOptions(): array
    {
        $options = [];

        foreach ([self::FULL_HD, self::UHD_4K, self::HD, self::HD_PLUS, self::XGA] as $case) {
            $options[$case->value] = self::options()[$case->value];
        }

        return $options;
    }

    /** Get portrait resolutions */
    public static function portraitOptions(): array
    {
        $options = [];

        foreach ([self::PORTRAIT_FULL_HD, self::PORTRAIT_HD, self::PORTRAIT_HD_PLUS] as $case) {
            $options[$case->value] = self::options()[$case->value];
        }

        return $options;
    }
}

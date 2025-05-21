<?php

declare(strict_types=1);

namespace App\Enums;

enum DeviceMetricType: string
{
    case PERFORMANCE = 'performance';
    case HARDWARE = 'hardware';
    case DISPLAY = 'display';
    case TEMPERATURE = 'temperature';
    case NETWORK = 'network';
    case SYSTEM = 'system';
    case STORAGE = 'storage';
    case SCREEN_STATUS = 'screen_status';
    case ERRORS = 'errors';
    case HEALTH_SCORE = 'health_score';
    case ALERT = 'alert';

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

    public static function getThresholds(): array
    {
        return [
            self::PERFORMANCE->value => [
                'cpu_usage_percent'    => 90,
                'memory_usage_percent' => 90,
                'disk_usage_percent'   => 90,
            ],
            self::TEMPERATURE->value => [
                'cpu_temp' => 80,
                'gpu_temp' => 85,
            ],
            self::STORAGE->value => [
                'free_space_percent' => 10,
            ],
            self::HEALTH_SCORE->value => [
                'minimum_score' => 60,
            ],
        ];
    }
}

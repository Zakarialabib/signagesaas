<?php

declare(strict_types=1);

namespace App\Enums;

enum DeviceStatus: string
{
    case PENDING = 'pending';
    case PROVISIONING = 'provisioning';
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case MAINTENANCE = 'maintenance';
    case ERROR = 'error';
    case REBOOTING = 'rebooting';
    case UPDATING = 'updating';
    case STANDBY = 'standby';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';

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

    public function getDescription(): string
    {
        return match ($this) {
            self::PENDING      => 'Device registered but not yet claimed',
            self::PROVISIONING => 'Device is being set up and configured',
            self::ONLINE       => 'Device is connected and operating normally',
            self::OFFLINE      => 'Device is not connected',
            self::MAINTENANCE  => 'Device is under maintenance',
            self::ERROR        => 'Device has reported an error',
            self::REBOOTING    => 'Device is currently rebooting',
            self::UPDATING     => 'Device is installing updates',
            self::STANDBY      => 'Device is in power-saving mode',
            self::INACTIVE     => 'Device has been deactivated',
            self::SUSPENDED    => 'Device access has been suspended',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING      => 'yellow',
            self::PROVISIONING => 'blue',
            self::ONLINE       => 'green',
            self::OFFLINE      => 'red',
            self::MAINTENANCE  => 'orange',
            self::ERROR        => 'red',
            self::REBOOTING    => 'purple',
            self::UPDATING     => 'blue',
            self::STANDBY      => 'gray',
            self::INACTIVE     => 'gray',
            self::SUSPENDED    => 'red',
        };
    }

    public function canReceiveContent(): bool
    {
        return in_array($this, [
            self::ONLINE,
            self::STANDBY,
            self::MAINTENANCE,
        ]);
    }
}

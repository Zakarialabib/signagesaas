<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\DeviceStatus;
use App\Events\DeviceRegistered;
use App\Tenant\Models\Device;
use App\Exceptions\DeviceRegistrationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

final class DeviceRegistrationService
{
    public function registerDevice(array $attributes): Device
    {
        return DB::transaction(function () use ($attributes) {
            // Generate a unique registration code
            $registrationCode = $this->generateUniqueRegistrationCode();

            $device = Device::create([
                'name'              => $attributes['name'],
                'status'            => DeviceStatus::PENDING,
                'registration_code' => $registrationCode,
                'type'              => $attributes['type'] ?? null,
                'hardware_id'       => $attributes['hardware_id'] ?? Str::uuid(),
                'orientation'       => $attributes['orientation'] ?? 'landscape',
                'screen_resolution' => $attributes['screen_resolution'] ?? null,
                'os_version'        => $attributes['os_version'] ?? null,
                'app_version'       => $attributes['app_version'] ?? null,
                'settings'          => $attributes['settings'] ?? [],
            ]);

            event(new DeviceRegistered($device));

            return $device;
        });
    }

    public function claimDevice(string $registrationCode, string $tenantId): Device
    {
        $device = Device::where('registration_code', $registrationCode)
            ->whereNull('tenant_id')
            ->first();

        if ( ! $device) {
            throw new DeviceRegistrationException('Invalid registration code or device already claimed');
        }

        $device->update([
            'tenant_id'  => $tenantId,
            'status'     => DeviceStatus::ONLINE,
            'claimed_at' => now(),
        ]);

        return $device;
    }

    public function generateUniqueRegistrationCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Device::where('registration_code', $code)->exists());

        return $code;
    }

    public function generateQrCode(Device $device): string
    {
        $data = [
            'code' => $device->registration_code,
            'id'   => $device->id,
            'type' => $device->type,
        ];

        return QrCode::size(300)
            ->format('svg')
            ->generate(json_encode($data));
    }
}

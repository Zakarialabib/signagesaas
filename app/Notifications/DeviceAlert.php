<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Tenant\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeviceAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Device $device,
        private readonly string $message,
        private readonly array $context = []
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject("Device Alert: {$this->device->name}")
            ->line("There is an alert for device: {$this->device->name}")
            ->line($this->message)
            ->line('Device Details:')
            ->line("- Type: {$this->device->type->value}")
            ->line("- Status: {$this->device->status->value}")
            ->line('- Last Seen: '.($this->device->last_ping_at?->diffForHumans() ?? 'Never'))
            ->action('View Device', url("/devices/{$this->device->id}"));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'device_id'   => $this->device->id,
            'device_name' => $this->device->name,
            'message'     => $this->message,
            'context'     => $this->context,
            'type'        => 'device_alert',
            'severity'    => 'warning',
            'timestamp'   => now(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Tenant\Models\Device;
use App\Tenant\Models\Content;
use App\Tenant\Models\Screen;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class ContentSyncService
{
    /**
     * Check if device needs content sync
     */
    public function checkSyncNeeded(Device $device): bool
    {
        $lastSync = $device->last_sync_at;
        if (!$lastSync) {
            return true;
        }

        // Check if any content has been updated since last sync
        $hasContentUpdates = Content::where('tenant_id', $device->tenant_id)
            ->where('updated_at', '>', $lastSync)
            ->exists();

        // Check if any screens have been updated since last sync
        $hasScreenUpdates = Screen::where('tenant_id', $device->tenant_id)
            ->where('updated_at', '>', $lastSync)
            ->exists();

        return $hasContentUpdates || $hasScreenUpdates;
    }

    /**
     * Get sync data for a device
     */
    public function getSyncData(Device $device): array
    {
        $screens = Screen::where('tenant_id', $device->tenant_id)
            ->with(['contents' => function ($query) {
                $query->orderBy('order');
            }])
            ->get()
            ->map(function ($screen) use ($device) {
                return [
                    'screen_id' => $screen->id,
                    'screen_name' => $screen->name,
                    'resolution' => $screen->resolution,
                    'orientation' => $screen->orientation,
                    'settings' => $screen->settings ?? [],
                    'contents' => $screen->contents->map(function ($content) use ($device) {
                        return [
                            'id' => $content->id,
                            'name' => $content->name,
                            'type' => $content->type,
                            'content_data' => $content->content_data,
                            'duration' => $content->duration,
                            'order' => $content->order,
                            'settings' => $content->settings ?? [],
                            'rendered_html' => $content->rendered_html,
                            'media_url' => $this->generateSignedMediaUrl($device, $content),
                        ];
                    }),
                ];
            });

        // Update device's last sync timestamp
        $device->update(['last_sync_at' => Carbon::now()]);

        return [
            'screens' => $screens,
            'ota_update' => $this->getOtaUpdate($device),
        ];
    }

    /**
     * Get content for download
     */
    public function getContentForDownload(Device $device, string $contentId): ?array
    {
        $content = Content::where('tenant_id', $device->tenant_id)
            ->where('id', $contentId)
            ->first();

        if (!$content) {
            return null;
        }

        return [
            'id' => $content->id,
            'name' => $content->name,
            'type' => $content->type,
            'content_data' => $content->content_data,
            'media_url' => $this->generateSignedMediaUrl($device, $content),
        ];
    }

    /**
     * Get OTA update information
     */
    public function getOtaUpdate(Device $device): ?array
    {
        // TODO: Implement OTA update logic
        // For now, return null as OTA updates are not implemented
        return null;
    }

    /**
     * Generate signed URL for media content
     */
    protected function generateSignedMediaUrl(Device $device, Content $content): string
    {
        return URL::temporarySignedRoute(
            'api.device.media',
            now()->addMinutes(30),
            ['device' => $device->id, 'content' => $content->id]
        );
    }
} 
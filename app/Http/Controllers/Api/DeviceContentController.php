<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Device;
use App\Services\ContentSyncService;
use Illuminate\Http\JsonResponse;

class DeviceContentController extends Controller
{
    protected ContentSyncService $contentSyncService;

    public function __construct(ContentSyncService $contentSyncService)
    {
        $this->contentSyncService = $contentSyncService;
    }

    /**
     * Sync content for a device
     */
    public function sync(Device $device): JsonResponse
    {
        $syncData = $this->contentSyncService->getSyncData($device);

        return response()->json([
            'success' => true,
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'type' => $device->type,
                'settings' => $device->settings ?? []
            ],
            'screens' => $syncData['screens'],
            'ota_update' => $syncData['ota_update'],
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Download media content
     */
    public function downloadMedia(Device $device, string $contentId): JsonResponse
    {
        $content = $this->contentSyncService->getContentForDownload($device, $contentId);
        
        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'content' => $content
        ]);
    }
} 
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Device;
use App\Services\ContentSyncService;
use Illuminate\Http\JsonResponse;

class DeviceUpdateController extends Controller
{
    protected ContentSyncService $contentSyncService;

    public function __construct(ContentSyncService $contentSyncService)
    {
        $this->contentSyncService = $contentSyncService;
    }

    /**
     * Check for available updates
     */
    public function checkUpdates(Device $device): JsonResponse
    {
        $update = $this->contentSyncService->getOtaUpdate($device);
        
        if (!$update) {
            return response()->json([
                'success' => true,
                'has_update' => false,
                'message' => 'No update available'
            ]);
        }

        return response()->json([
            'success' => true,
            'has_update' => true,
            'update' => $update
        ]);
    }

    /**
     * Download OTA update
     */
    public function downloadUpdate(Device $device): JsonResponse
    {
        $update = $this->contentSyncService->getOtaUpdate($device);
        
        if (!$update) {
            return response()->json([
                'success' => false,
                'message' => 'No update available'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'update' => $update
        ]);
    }
} 
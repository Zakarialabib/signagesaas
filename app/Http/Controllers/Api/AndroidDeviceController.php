<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Device;
use App\Tenant\Models\Tenant;
use App\Services\DeviceMetricsService;
use App\Services\ContentSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AndroidDeviceController extends Controller
{
    protected DeviceMetricsService $metricsService;
    protected ContentSyncService $contentSyncService;

    public function __construct(
        DeviceMetricsService $metricsService,
        ContentSyncService $contentSyncService
    ) {
        $this->metricsService = $metricsService;
        $this->contentSyncService = $contentSyncService;
    }

    /**
     * Authenticate a device and return a token
     */
    public function authenticate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'hardware_id' => 'required|string',
            'tenant_id' => 'required|string|exists:tenants,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenant = Tenant::findOrFail($request->tenant_id);
        $device = Device::firstOrCreate(
            ['hardware_id' => $request->hardware_id],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Android Device',
                'type' => 'android',
                'status' => 'active',
            ]
        );

        // Generate a new token
        $token = $device->createToken('android-device')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'device_id' => $device->id,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Handle device heartbeat
     */
    public function heartbeat(Request $request, Device $device): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:online,offline',
            'ip_address' => 'required|ip',
            'metrics' => 'required|array',
            'metrics.cpu_usage' => 'required|numeric',
            'metrics.memory_usage' => 'required|numeric',
            'metrics.uptime' => 'required|integer',
            'app_version' => 'required|string',
            'screen_status' => 'required|array',
            'screen_status.power' => 'required|string|in:on,off',
            'screen_status.brightness' => 'required|integer|min:0|max:100',
            'storage_info' => 'required|array',
            'storage_info.total' => 'required|integer',
            'storage_info.free' => 'required|integer',
            'network_info' => 'required|array',
            'network_info.type' => 'required|string',
            'network_info.signal_strength' => 'required|integer|min:0|max:100',
            'system_info' => 'required|array',
            'system_info.os_version' => 'required|string',
            'system_info.model' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update device metrics
        $this->metricsService->recordMetrics($device, $request->all());

        // Check if content sync is needed
        $needsSync = $this->contentSyncService->checkSyncNeeded($device);

        return response()->json([
            'success' => true,
            'timestamp' => now()->toIso8601String(),
            'needs_sync' => $needsSync
        ]);
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
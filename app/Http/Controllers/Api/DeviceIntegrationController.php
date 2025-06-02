<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\DeviceHeartbeatRequest;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Device;
use App\Services\DeviceIntegrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class DeviceIntegrationController extends Controller
{
    public function __construct(
        private readonly DeviceIntegrationService $deviceIntegrationService
    ) {
    }

    /** Authenticate a device and return a token */
    public function authenticate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'hardware_id' => 'required|string',
            'tenant_id'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Find device by hardware ID and tenant ID
        $device = Device::where('hardware_id', $request->hardware_id)
            ->where('tenant_id', $request->tenant_id)
            ->first();

        if ( ! $device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found or not authorized',
            ], 401);
        }

        // Generate token for device
        $token = $this->deviceIntegrationService->generateDeviceToken($device);

        return response()->json([
            'success'   => true,
            'token'     => $token,
            'device_id' => $device->id,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /** Process device heartbeat/ping */
    public function heartbeat(Request $request, Device $device): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status'        => 'sometimes|string|in:'.implode(',', DeviceHeartbeatRequest::getValidStatuses()),
            'ip_address'    => 'sometimes|string',
            'metrics'       => 'sometimes|array',
            'app_version'   => 'sometimes|string',
            'screen_status' => 'sometimes|array',
            'storage_info'  => 'sometimes|array',
            'network_info'  => 'sometimes|array',
            'system_info'   => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Validate data structure using DTO validation
        if ( ! DeviceHeartbeatRequest::validate($request->all())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid heartbeat data structure',
            ], 422);
        }

        // Create heartbeat DTO from request data
        $heartbeatData = DeviceHeartbeatRequest::fromArray($request->all());

        // Process heartbeat data
        $this->deviceIntegrationService->processHeartbeat($device, $heartbeatData);

        // Check if device needs content sync
        $needsSync = $this->deviceIntegrationService->deviceNeedsSync($device);

        return response()->json([
            'success'    => true,
            'timestamp'  => now()->toIso8601String(),
            'needs_sync' => $needsSync,
        ]);
    }

    /** Sync media content to device */
    public function sync(Request $request, Device $device): JsonResponse
    {
        // Mark device as synced
        $this->deviceIntegrationService->markDeviceSynced($device);

        // Load screens with contents (similar to DeviceController::getContent)
        $device->load(['screens' => function ($query) {
            $query->where('status', 'active');
        }, 'screens.contents' => function ($query) {
            // Get only active contents
            $query->where('status', 'active')
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('start_date')
                        ->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', $now);
                })
                ->orderBy('order');
        }]);

        // Prepare the response with content data
        $contents = [];

        foreach ($device->screens as $screen) {
            $screenContents = [];

            foreach ($screen->contents as $content) {
                $screenContents[] = [
                    'id'            => $content->id,
                    'name'          => $content->name,
                    'type'          => $content->type,
                    'content_data'  => $content->content_data,
                    'duration'      => $content->duration,
                    'order'         => $content->order,
                    'settings'      => $content->settings,
                    'rendered_html' => $content->getRenderedHtml(),
                    'media_url'     => $content->media_url ? URL::signedRoute(
                        'api.device.media',
                        ['device' => $device->id, 'content' => $content->id],
                        now()->addDay()
                    ) : null,
                ];
            }

            $contents[] = [
                'screen_id'   => $screen->id,
                'screen_name' => $screen->name,
                'resolution'  => $screen->resolution,
                'orientation' => $screen->orientation,
                'settings'    => $screen->settings,
                'contents'    => $screenContents,
            ];
        }

        // Check if there are any OTA updates available
        $otaUpdate = null;

        if ($device->app_version && $device->type) {
            // Logic to check for available updates based on device type and current version
            // This would typically check against a releases table or file storage
            $updatePath = "updates/{$device->type->value}/latest.zip";

            if (Storage::exists($updatePath)) {
                $otaUpdate = [
                    'version'       => 'latest', // This would be the actual version number
                    'download_url'  => $this->deviceIntegrationService->generateSignedUpdateUrl($device, $updatePath),
                    'checksum'      => hash_file('sha256', Storage::path($updatePath)),
                    'release_notes' => 'Latest update with bug fixes and improvements', // This would be actual release notes
                ];
            }
        }

        return response()->json([
            'success' => true,
            'device'  => [
                'id'       => $device->id,
                'name'     => $device->name,
                'type'     => $device->type,
                'settings' => $device->settings,
            ],
            'screens'    => $contents,
            'ota_update' => $otaUpdate,
            'timestamp'  => now()->toIso8601String(),
        ]);
    }

    /** Download OTA update file */
    public function downloadUpdate(Request $request, Device $device): BinaryFileResponse
    {
        $filePath = $request->file;

        if ( ! Storage::exists($filePath)) {
            abort(404, 'Update file not found');
        }

        return response()->download(Storage::path($filePath));
    }

    /** Download media file with signed URL */
    public function downloadMedia(Request $request, Device $device, $contentId): BinaryFileResponse
    {
        $content = $device->screens()->with('contents')->get()
            ->pluck('contents')
            ->flatten()
            ->firstWhere('id', $contentId);

        if ( ! $content || ! $content->media_path || ! Storage::exists($content->media_path)) {
            abort(404, 'Media file not found');
        }

        return response()->download(Storage::path($content->media_path));
    }
}

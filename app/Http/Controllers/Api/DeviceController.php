<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Device;
use App\Tenant\Models\Content;
use App\Services\DeviceIntegrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function __construct(
        private readonly DeviceIntegrationService $deviceIntegrationService
    ) {
    }

    /** Display a listing of devices for the tenant. */
    public function index(): JsonResponse
    {
        $devices = Device::all();

        return response()->json([
            'devices' => $devices,
        ]);
    }

    /** Store a new device. */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|string|in:android,raspberry-pi,windows',
            'status'   => 'nullable|string|in:online,offline,pending',
            'settings' => 'nullable|array',
            'hardware_id' => 'required|string|unique:devices,hardware_id',
        ]);

        $device = Device::create($validated);

        return response()->json([
            'device'  => $device,
            'message' => 'Device created successfully',
        ], 201);
    }

    /** Display the specified device. */
    public function show(Device $device): JsonResponse
    {
        // Note: Route model binding will automatically use the tenant model binding
        // to get the correct tenant-specific device.

        return response()->json([
            'device' => $device,
        ]);
    }

    /** Update the specified device. */
    public function update(Request $request, Device $device): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'type'     => 'sometimes|string|in:android,raspberry-pi,windows',
            'status'   => 'sometimes|string|in:online,offline,pending',
            'settings' => 'sometimes|array',
        ]);

        $device->update($validated);

        return response()->json([
            'device'  => $device,
            'message' => 'Device updated successfully',
        ]);
    }

    /** Remove the specified device. */
    public function destroy(Device $device): JsonResponse
    {
        $device->delete();

        return response()->json([
            'message' => 'Device deleted successfully',
        ]);
    }

    /** Get the content for a specific device. */
    public function getContent(Request $request, Device $device): JsonResponse
    {
        // Validate the device is active
        if ($device->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Device is not active',
            ], 403);
        }

        // Update last sync timestamp
        $device->update([
            'last_sync_at' => now(),
        ]);

        // Load screens with contents
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

        // Prepare the response
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

        return response()->json([
            'success' => true,
            'device'  => [
                'id'   => $device->id,
                'name' => $device->name,
                'type' => $device->type,
            ],
            'screens'   => $contents,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /** Update device ping status. */
    public function ping(Request $request, Device $device): JsonResponse
    {
        // Validate the request
        $validated = $request->validate([
            'status'        => 'sometimes|string',
        ]);

        // Update device
        $device->update([
            'last_ping_at' => now(),
            'status'       => $validated['status'] ?? $device->status,
        ]);

        return response()->json([
            'success'   => true,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function authenticate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'hardware_id'       => 'required|string|max:255',
            'name'              => 'required|string|max:255',
            'type'              => 'required|string|max:50',
            'ip_address'        => 'nullable|ip',
            'screen_resolution' => 'nullable|string|max:50',
            'os_version'        => 'nullable|string|max:50',
            'app_version'       => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        // Consolidate device attributes for creation/update
        $deviceAttributes = [
            'name'              => $validatedData['name'],
            'type'              => $validatedData['type'],
            'ip_address'        => $validatedData['ip_address'],
            'screen_resolution' => $validatedData['screen_resolution'],
            'os_version'        => $validatedData['os_version'],
            'app_version'       => $validatedData['app_version'],
            'status'            => 'online',
            'last_ping_at'      => now(),
        ];

        $device = Device::firstOrCreate(
            ['hardware_id' => $validatedData['hardware_id']],
            $deviceAttributes
        );

        // If device was found, update its info
        if (!$device->wasRecentlyCreated) {
            $device->update($deviceAttributes);
        }

        $token = $device->createToken('device-token', ['device-token'])->plainTextToken;

        return response()->json([
            'success'   => true,
            'token'     => $token,
            'device_id' => (string) $device->id,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function heartbeat(Request $request, Device $device): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status'     => 'required|string|max:50',
            'ip_address' => 'nullable|ip',
            'metrics'    => 'nullable|array',
            'metrics.cpu' => 'nullable|integer|min:0|max:100',
            'metrics.memory' => 'nullable|integer|min:0',
            'metrics.storage' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();
        $currentSettings = $device->settings ?? [];

        $deviceDataToUpdate = [
            'status'            => $validatedData['status'],
            'last_heartbeat_at' => now(),
            'ip_address'        => $validatedData['ip_address'] ?? $device->ip_address,
            'settings'          => array_merge($currentSettings, ['metrics' => $validatedData['metrics'] ?? null]),
        ];

        $device->update($deviceDataToUpdate);

        $needsSync = $device->needs_content_sync ?? true;

        return response()->json([
            'success'    => true,
            'timestamp'  => now()->toIso8601String(),
            'needs_sync' => (bool) $needsSync,
        ]);
    }

    public function sync(Request $request, Device $device): JsonResponse
    {
        $device->update([
            'last_sync_at' => now(),
            'needs_content_sync' => false,
        ]);

        $device->load(['screens' => function ($query) {
            $query->where('status', 'active')->orderBy('pivot_order', 'asc');
        }, 'screens.contents' => function ($query) {
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

        $screensData = $device->screens->map(function ($screen) use ($device) {
            return [
                'screen_id'   => (string) $screen->id,
                'screen_name' => $screen->name,
                'resolution'  => $screen->resolution,
                'orientation' => $screen->orientation,
                'settings'    => $screen->settings ?? (object)[],
                'contents'    => $screen->contents->map(function ($content) use ($device) {
                    $renderedHtml = method_exists($content, 'getRenderedHtml') ? $content->getRenderedHtml() : (isset($content->content_data['html']) && $content->type === 'html' ? $content->content_data['html'] : null);

                    return [
                        'id'            => (string) $content->id,
                        'name'          => $content->name,
                        'type'          => $content->type,
                        'content_data'  => $content->content_data ?? (object)[],
                        'duration'      => $content->duration,
                        'order'         => $content->order,
                        'settings'      => $content->settings ?? (object)[],
                        'rendered_html' => $renderedHtml,
                        'media_url'     => $content->media_url ? URL::signedRoute(
                            'tenant.api.device.media',
                            ['device' => $device->id, 'content' => $content->id], now()->addDay()
                        ) : null,
                    ];
                }),
            ];
        });

        $otaUpdate = null;
        $updatePath = "updates/{$device->type}/latest.zip";
        
        if (Storage::disk('local')->exists($updatePath)) {
            $otaUpdate = [
                'version'       => 'latest',
                'download_url'  => URL::signedRoute('tenant.api.device.download', ['device' => $device->id], now()->addHour()),
                'checksum'      => hash_file('sha256', Storage::disk('local')->path($updatePath)),
                'release_notes' => 'Latest stability and performance improvements.',
            ];
        }

        return response()->json([
            'success' => true,
            'device'  => [
                'id'       => (string) $device->id,
                'name'     => $device->name,
                'type'     => $device->type,
                'settings' => $device->settings ?? (object)[],
            ],
            'screens'    => $screensData,
            'ota_update' => $otaUpdate,
            'timestamp'  => now()->toIso8601String(),
        ]);
    }

    public function downloadUpdate(Request $request, Device $device): BinaryFileResponse
    {
        // Signed URL validation is assumed to be handled by middleware ('signed') on the route

        $updatePath = "updates/{$device->type}/latest.zip"; // e.g., updates/android/latest.zip

        if (!Storage::disk('local')->exists($updatePath)) {
            abort(404, 'Update file not found.');
        }
        return response()->download(Storage::disk('local')->path($updatePath));
    }

    public function downloadMedia(Request $request, Device $device, Content $content): BinaryFileResponse
    {
        // Signed URL validation is assumed to be handled by middleware ('signed') on the route

        if (!$content->media_url || !Storage::disk('public')->exists($content->media_url)) { // Assuming media is in 'public' disk
            abort(404, 'Media file not found.');
        }
        return response()->download(Storage::disk('public')->path($content->media_url));
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
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
            'location' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
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
            'location' => 'sometimes|string|max:255',
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
            'hardware_info' => 'sometimes|array',
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
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Device;
use App\Tenant\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceAuthController extends Controller
{
    /**
     * Register a new device
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|string|exists:tenants,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:android,raspberry-pi,windows',
            'hardware_id' => 'required|string|unique:devices,hardware_id',
            'ip_address' => 'nullable|ip',
            'screen_resolution' => 'nullable|string|max:50',
            'orientation' => 'nullable|string|in:landscape,portrait',
            'os_version' => 'nullable|string|max:50',
            'app_version' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tenant = Tenant::findOrFail($request->tenant_id);
        
        $device = Device::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'type' => $request->type,
            'hardware_id' => $request->hardware_id,
            'ip_address' => $request->ip_address,
            'screen_resolution' => $request->screen_resolution,
            'orientation' => $request->orientation ?? 'landscape',
            'os_version' => $request->os_version,
            'app_version' => $request->app_version,
            'status' => 'active',
        ]);

        // Generate a new token
        $token = $device->createToken('device-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'device_id' => $device->id,
            'timestamp' => now()->toIso8601String()
        ], 201);
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
} 
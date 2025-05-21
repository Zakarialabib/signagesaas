<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Facades\Settings;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Get all settings for the current tenant.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $settings = Settings::getAll();

        return response()->json([
            'data' => $settings,
        ]);
    }

    /**
     * Update one or more settings for the current tenant.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ( ! $user || ! $user->hasPermissionTo('update-settings')) {
            return response()->json([
                'message' => 'Unauthorized to update settings',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $settingsToUpdate = $request->input('settings');

        // Validate specific settings if needed
        // This could be enhanced to validate specific settings based on their keys

        $success = Settings::update($settingsToUpdate);

        if ( ! $success) {
            return response()->json([
                'message' => 'Failed to update settings',
            ], 500);
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'data'    => Settings::getAll(),
        ]);
    }

    /**
     * Get a specific setting by key.
     *
     * @param  string  $key
     * @return JsonResponse
     */
    public function show(string $key): JsonResponse
    {
        if ( ! Settings::has($key)) {
            return response()->json([
                'message' => 'Setting not found',
            ], 404);
        }

        return response()->json([
            'data' => [
                'key'   => $key,
                'value' => Settings::get($key),
            ],
        ]);
    }
}

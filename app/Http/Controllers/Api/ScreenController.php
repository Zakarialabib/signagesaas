<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Screen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function index(): JsonResponse
    {
        $screens = Screen::with('contents')->get();

        return response()->json([
            'screens' => $screens,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device_id' => 'required|exists:devices,id',
            'resolution' => 'required|string',
            'orientation' => 'required|string|in:landscape,portrait',
            'settings' => 'nullable|array',
            'status' => 'required|string|in:active,inactive',
        ]);

        $screen = Screen::create($validated);

        return response()->json([
            'screen' => $screen,
            'message' => 'Screen created successfully',
        ], 201);
    }

    public function show(Screen $screen): JsonResponse
    {
        return response()->json([
            'screen' => $screen->load('contents'),
        ]);
    }

    public function update(Request $request, Screen $screen): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'device_id' => 'sometimes|exists:devices,id',
            'resolution' => 'sometimes|string',
            'orientation' => 'sometimes|string|in:landscape,portrait',
            'settings' => 'nullable|array',
            'status' => 'sometimes|string|in:active,inactive',
        ]);

        $screen->update($validated);

        return response()->json([
            'screen' => $screen->load('contents'),
            'message' => 'Screen updated successfully',
        ]);
    }

    public function destroy(Screen $screen): JsonResponse
    {
        $screen->delete();

        return response()->json([
            'message' => 'Screen deleted successfully',
        ]);
    }
} 
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Content;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ContentController extends Controller
{
    public function index(): JsonResponse
    {
        $contents = Content::with('screens')->get();

        return response()->json([
            'contents' => $contents,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:html,image,video,webpage',
            'content_data' => 'required|array',
            'duration' => 'required|integer|min:1',
            'order' => 'required|integer|min:0',
            'settings' => 'nullable|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'screen_ids' => 'required|array',
            'screen_ids.*' => 'exists:screens,id',
        ]);

        $content = Content::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'content_data' => $validated['content_data'],
            'duration' => $validated['duration'],
            'order' => $validated['order'],
            'settings' => $validated['settings'] ?? [],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'active',
        ]);

        // Attach content to screens
        $content->screens()->attach($validated['screen_ids']);

        // Handle media upload if present
        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('content/media');
            $content->update(['media_url' => $path]);
        }

        return response()->json([
            'content' => $content->load('screens'),
            'message' => 'Content created successfully',
        ], 201);
    }

    public function show(Content $content): JsonResponse
    {
        return response()->json([
            'content' => $content->load('screens'),
        ]);
    }

    public function update(Request $request, Content $content): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|in:html,image,video,webpage',
            'content_data' => 'sometimes|array',
            'duration' => 'sometimes|integer|min:1',
            'order' => 'sometimes|integer|min:0',
            'settings' => 'nullable|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'screen_ids' => 'sometimes|array',
            'screen_ids.*' => 'exists:screens,id',
            'status' => 'sometimes|string|in:active,inactive',
        ]);

        $content->update($validated);

        // Update screen associations if provided
        if ($request->has('screen_ids')) {
            $content->screens()->sync($validated['screen_ids']);
        }

        // Handle media upload if present
        if ($request->hasFile('media')) {
            // Delete old media if exists
            if ($content->media_url) {
                Storage::delete($content->media_url);
            }
            
            $path = $request->file('media')->store('content/media');
            $content->update(['media_url' => $path]);
        }

        return response()->json([
            'content' => $content->load('screens'),
            'message' => 'Content updated successfully',
        ]);
    }

    public function destroy(Content $content): JsonResponse
    {
        // Delete media file if exists
        if ($content->media_url) {
            Storage::delete($content->media_url);
        }

        $content->delete();

        return response()->json([
            'message' => 'Content deleted successfully',
        ]);
    }
} 
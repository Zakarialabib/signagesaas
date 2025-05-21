<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

// Tenant-specific API routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Device API endpoints
    // Route::apiResource('devices', App\Http\Controllers\Api\DeviceController::class);

    /* Placeholder API endpoints - implement controllers as needed

    // Content API endpoints
    Route::apiResource('content', \App\Http\Controllers\Api\ContentController::class);

    // Screen API endpoints
    Route::apiResource('screens', \App\Http\Controllers\Api\ScreenController::class);

    // Schedule API endpoints
    Route::apiResource('schedules', \App\Http\Controllers\Api\ScheduleController::class);

    // User management endpoints
    Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
    */

    // Settings API endpoints
    // Route::get('settings', [SettingController::class, 'index']);
    // Route::get('settings/{key}', [SettingController::class, 'show']);
    // Route::patch('settings', [SettingController::class, 'update']);
});

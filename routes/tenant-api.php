<?php

declare(strict_types=1);

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\ScreenController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

// Public device authentication endpoint
Route::post('authenticate', [DeviceController::class, 'authenticate']);

// Protected device API endpoints
Route::middleware(['auth:sanctum', 'device'])->group(function () {
    // Device endpoints
    Route::prefix('device')->group(function () {
        Route::post('heartbeat/{device}', [DeviceController::class, 'heartbeat']);
        Route::get('sync/{device}', [DeviceController::class, 'sync']);
        Route::get('download/{device}', [DeviceController::class, 'downloadUpdate'])
            ->middleware('signed');
        Route::get('media/{device}/{content}', [DeviceController::class, 'downloadMedia'])
            ->middleware('signed');
    });

    // Content management
    Route::apiResource('content', ContentController::class);
    
    // Screen management
    Route::apiResource('screens', ScreenController::class);
    
    // Settings API endpoints
    Route::get('settings', [SettingController::class, 'index']);
    Route::get('settings/{key}', [SettingController::class, 'show']);
    Route::patch('settings', [SettingController::class, 'update']);
});

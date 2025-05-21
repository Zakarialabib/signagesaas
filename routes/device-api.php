<?php

declare(strict_types=1);

use App\Http\Controllers\Api\DeviceIntegrationController;
use App\Http\Controllers\Api\DeviceMetricsController;
use Illuminate\Support\Facades\Route;

// Public device API endpoints (no authentication required)
Route::post('authenticate', [DeviceIntegrationController::class, 'authenticate'])->name('api.device.authenticate');
Route::post('/device/register', [DeviceIntegrationController::class, 'authenticate']);
Route::post('/device/claim', [DeviceIntegrationController::class, 'claim']);

// Protected device API endpoints (require device authentication)
Route::middleware(['auth:sanctum', 'App\Http\Middleware\DeviceAuthentication'])->group(function () {
    // Device heartbeat/ping endpoint
    Route::post('heartbeat/{device}', [DeviceIntegrationController::class, 'heartbeat'])->name('api.device.heartbeat');
    Route::post('/device/heartbeat', [DeviceIntegrationController::class, 'heartbeat']);

    // Content sync endpoint
    Route::get('sync/{device}', [DeviceIntegrationController::class, 'sync'])->name('api.device.sync');
    Route::get('/device/content', [DeviceIntegrationController::class, 'getContent']);

    // OTA update check endpoint
    Route::get('/device/ota', [DeviceIntegrationController::class, 'checkUpdates']);

    // Signed URL endpoints
    Route::get('download/{device}', [DeviceIntegrationController::class, 'downloadUpdate'])
        ->middleware('signed')
        ->name('api.device.download');
    Route::get('/device/download/{file}', [DeviceIntegrationController::class, 'downloadUpdate'])
        ->name('api.device.download');

    Route::get('media/{device}/{content}', [DeviceIntegrationController::class, 'downloadMedia'])
        ->middleware('signed')
        ->name('api.device.media');

    // Device metrics endpoints
    Route::prefix('metrics')->group(function () {
        Route::get('/{device}', [DeviceMetricsController::class, 'index']);
        Route::get('/{device}/latest', [DeviceMetricsController::class, 'latest']);
        Route::get('/{device}/summary', [DeviceMetricsController::class, 'summary']);
    });
});

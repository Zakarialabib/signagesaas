<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Tenant\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $device = $request->user();

        if (!$device instanceof Device) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid device token'
            ], 401);
        }

        // Check if device is active
        if ($device->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Device is not active'
            ], 403);
        }

        // Add device to request for easy access in controllers
        $request->merge(['device' => $device]);

        return $next($request);
    }
}

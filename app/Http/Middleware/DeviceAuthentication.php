<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Tenant\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated via Sanctum
        if ( ! Auth::guard('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user = Auth::guard('sanctum')->user();

        // Check if the token belongs to a device
        if ( ! $user instanceof Device) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid device token',
            ], 403);
        }

        // Check if the device is active
        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Device is not active',
            ], 403);
        }

        // Set the authenticated device on the request
        $request->merge(['device' => $user]);

        return $next($request);
    }
}

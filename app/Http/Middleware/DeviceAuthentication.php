<?php

declare(strict_types=1);

namespace App\Http\Middleware;

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
        if (!$request->user() || !$request->user()->tokenCan('device-token')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid device token',
            ], 401);
        }

        return $next($request);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DeviceMetricsController extends Controller
{
    public function index(Device $device, Request $request): JsonResponse
    {
        $request->validate([
            'type'       => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $metrics = $device->getMetricsForPeriod(
            $request->type,
            $request->start_date,
            $request->end_date
        );

        return response()->json([
            'success' => true,
            'metrics' => $metrics,
        ]);
    }

    public function latest(Device $device, Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        $metric = $device->getLatestMetric($request->type);

        return response()->json([
            'success' => true,
            'metric'  => $metric,
        ]);
    }

    public function summary(Device $device): JsonResponse
    {
        $summaryData = [
            'performance'   => $device->getLatestMetric('performance')?->data,
            'hardware'      => $device->hardware_info,
            'system'        => $device->system_info,
            'storage'       => $device->storage_info,
            'network'       => $device->getLatestMetric('network')?->data,
            'temperature'   => $device->getLatestMetric('temperature')?->data,
            'display'       => $device->getLatestMetric('display')?->data,
            'screen_status' => $device->getLatestMetric('screen_status')?->data,
            'last_error'    => $device->getLatestMetric('errors')?->data,
        ];

        return response()->json([
            'success'         => true,
            'device_id'       => $device->id,
            'name'            => $device->name,
            'status'          => $device->status,
            'last_ping'       => $device->last_ping_at,
            'metrics_summary' => $summaryData,
        ]);
    }
}

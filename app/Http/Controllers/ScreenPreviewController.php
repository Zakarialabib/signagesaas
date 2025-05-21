<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Tenant\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ScreenPreviewController extends Controller
{
    /** Handle the incoming request and show the screen preview. */
    public function __invoke(Request $request, Screen $screen): View
    {
        // Load the screen with its contents
        $screen->load(['contents' => function ($query) {
            // Get only active contents
            $query->where('status', 'active')
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('start_date')
                        ->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) {
                    $now = now();
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', $now);
                })
                ->orderBy('order');
        }]);

        return view('screens.preview', [
            'screen'   => $screen,
            'contents' => $screen->contents,
        ]);
    }
}

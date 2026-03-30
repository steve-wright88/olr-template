<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Services\AnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AnalysisController extends Controller
{
    public function __invoke(): View
    {
        $season = Season::where('loft_id', config('olr.loft_id'))
            ->where('is_active', true)
            ->latest()
            ->first();

        return view('site.analysis', [
            'season' => $season,
        ]);
    }

    public function data(AnalysisService $analysisService): JsonResponse
    {
        $season = Season::where('loft_id', config('olr.loft_id'))
            ->where('is_active', true)
            ->latest()
            ->first();

        if (! $season) {
            return response()->json(['error' => 'No active season'], 404);
        }

        return response()->json($analysisService->analyse($season->id));
    }
}

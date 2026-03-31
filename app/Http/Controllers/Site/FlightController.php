<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Season;
use App\Models\Setting;
use App\Services\AnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlightController extends Controller
{
    private array $excludeKeywords = [
        'loft report', 'loft-list', 'loft list', 'inventar', 'inventario',
        'loft scan', 'loft fly', 'hand scan',
    ];

    public function index(Request $request): View
    {
        $loftId = config('olr.loft_id');

        // All seasons for year picker
        $seasons = Season::where('loft_id', $loftId)
            ->orderByDesc('name')
            ->get();

        // Pick season by query param, or default to active/latest
        $season = null;
        if ($request->has('season')) {
            $season = Season::where('loft_id', $loftId)
                ->where('id', $request->input('season'))
                ->first();
        }

        if (! $season) {
            $season = $seasons->firstWhere('is_active', true) ?? $seasons->first();
        }

        $flights = $season
            ? Flight::where('season_id', $season->id)
                ->with(['results' => fn ($q) => $q->orderBy('arrival_order')->limit(10)->select('flight_id', 'speed')])
                ->orderByDesc('release_time')
                ->get()
                ->each(fn ($f) => $f->top10_speed = $f->results->avg('speed'))
                ->filter(fn (Flight $f) => ! $this->isExcluded($f))
                ->groupBy(fn (Flight $f) => match ($f->status) {
                    'live', 'running' => 'live',
                    'stopped' => 'completed',
                    default => 'upcoming',
                })
            : collect();

        return view('site.flights.index', [
            'season' => $season,
            'seasons' => $seasons,
            'flights' => $flights,
        ]);
    }

    public function show(Flight $flight): View
    {
        $flight->load(['results' => fn ($q) => $q->orderBy('arrival_order'), 'results.pigeon.team']);

        $winnerTime = $flight->results->first()?->arrival_time;

        $results = $flight->results->map(function ($result) use ($winnerTime) {
            $result->behind_winner = $winnerTime && $result->arrival_time
                ? strtotime($result->arrival_time) - strtotime($winnerTime)
                : null;

            return $result;
        });

        $top10Speed = $flight->results->take(10)->avg('speed');

        // Match flight to race map point for weather coordinates
        $racePoints = json_decode(Setting::get('race_map_points', '[]'), true) ?: [];
        $loftLat = Setting::get('race_map_loft_lat', '53.05');
        $loftLng = Setting::get('race_map_loft_lng', '-1.48');
        $matchedPoint = null;
        $flightName = strtolower($flight->name);
        $flightDistance = $flight->distance;

        foreach ($racePoints as $point) {
            $pointName = strtolower($point['name'] ?? '');
            if ($pointName && str_contains($flightName, $pointName)) {
                $matchedPoint = $point; break;
            }
        }
        if (! $matchedPoint && preg_match('/\bfinal\b/i', $flight->name) && !str_contains($flightName, 'semi')) {
            foreach ($racePoints as $point) {
                if (($point['type'] ?? '') === 'final') { $matchedPoint = $point; break; }
            }
        }
        if (! $matchedPoint && str_contains($flightName, 'semi')) {
            foreach ($racePoints as $point) {
                if (($point['type'] ?? '') === 'semi') { $matchedPoint = $point; break; }
            }
        }
        if (! $matchedPoint && $flightDistance && $flightDistance > 5) {
            $bestDiff = PHP_FLOAT_MAX;
            foreach ($racePoints as $point) {
                if (! empty($point['distance']) && preg_match('/(\d+)\s*km/i', $point['distance'], $m)) {
                    $diff = abs((float) $m[1] - $flightDistance);
                    if ($diff < $bestDiff && $diff < 30) {
                        $bestDiff = $diff;
                        $matchedPoint = $point;
                    }
                }
            }
        }

        return view('site.flights.show', [
            'flight' => $flight,
            'results' => $results,
            'winnerTime' => $winnerTime,
            'top10Speed' => $top10Speed,
            'matchedPoint' => $matchedPoint,
            'loftLat' => $loftLat,
            'loftLng' => $loftLng,
        ]);
    }

    public function analysisData(Request $request, AnalysisService $analysisService): JsonResponse
    {
        $loftId = config('olr.loft_id');

        $season = null;
        if ($request->has('season')) {
            $season = Season::where('loft_id', $loftId)
                ->where('id', $request->input('season'))
                ->first();
        }

        if (! $season) {
            $season = Season::where('loft_id', $loftId)
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        if (! $season) {
            return response()->json(['error' => 'No season found'], 404);
        }

        return response()->json($analysisService->analyse($season->id));
    }

    private function isExcluded(Flight $flight): bool
    {
        $name = strtolower($flight->name);

        foreach ($this->excludeKeywords as $keyword) {
            if (str_contains($name, $keyword)) {
                return true;
            }
        }

        return false;
    }
}

<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Loft;
use App\Models\Post;
use App\Models\Season;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $loft = Loft::find(config('olr.loft_id'));

        $season = Season::where('loft_id', config('olr.loft_id'))
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();

        $homepageMode = Setting::get('homepage_mode', 'pre-season'); // pre-season or race-season

        $posts = Post::published()->latest()->limit(3)->get();

        $upcomingFlights = collect();
        $latestFlights = collect();

        if ($homepageMode === 'race-season' && $season) {
            $upcomingFlights = Flight::where('season_id', $season->id)
                ->where('status', 'upcoming')
                ->orderBy('release_time')
                ->get();

            $latestFlights = Flight::where('season_id', $season->id)
                ->where('status', 'stopped')
                ->orderByDesc('release_time')
                ->limit(5)
                ->get();
        }

        $livestream = Post::where('post_type', 'livestream')
            ->where('is_pinned', true)
            ->latest()
            ->first();

        // Manual stats (admin-editable) or fall back to API data
        $pigeonCount = Setting::get('homepage_pigeon_count', $season?->pigeon_count);
        $teamCount = Setting::get('homepage_team_count', $season?->team_count);
        $homepageContent = Setting::get('homepage_content');

        // Live / Next event card
        $liveEvent = null;
        if ($season) {
            // Priority 1: running flight (LIVE)
            $runningFlight = Flight::where('season_id', $season->id)
                ->where('status', 'running')
                ->first();

            if ($runningFlight) {
                $liveEvent = [
                    'mode' => 'live',
                    'flight' => $runningFlight,
                ];
            } else {
                // Priority 2: next upcoming flight
                $nextFlight = Flight::where('season_id', $season->id)
                    ->where('status', 'upcoming')
                    ->orderBy('release_time')
                    ->first();

                if ($nextFlight) {
                    $liveEvent = [
                        'mode' => 'next',
                        'flight' => $nextFlight,
                    ];
                } else {
                    // Priority 3: last completed race (not training/scans)
                    $lastFlight = Flight::where('season_id', $season->id)
                        ->where('status', 'stopped')
                        ->where('distance', '>', 10) // skip loft scans/short trainers
                        ->orderByDesc('release_time')
                        ->first();

                    // Priority 4: admin-selected flight or most recent race from any season
                    if (! $lastFlight) {
                        $manualFlightId = Setting::get('homepage_latest_flight_id');
                        if ($manualFlightId) {
                            $lastFlight = Flight::find($manualFlightId);
                        }
                    }
                    if (! $lastFlight) {
                        $lastFlight = Flight::where('status', 'stopped')
                            ->where('distance', '>', 50)
                            ->where('flight_type', 'race')
                            ->orderByDesc('release_time')
                            ->first();
                    }

                    if ($lastFlight) {
                        $liveEvent = [
                            'mode' => 'result',
                            'flight' => $lastFlight,
                        ];
                    }
                }
            }

            // Try to match flight to a race map point for weather coordinates
            if ($liveEvent) {
                $racePoints = json_decode(Setting::get('race_map_points', '[]'), true) ?: [];
                $flightName = strtolower($liveEvent['flight']->name);
                $flightDistance = $liveEvent['flight']->distance; // km
                $matchedPoint = null;

                // Strategy 1: name match (e.g. flight "Warwick" matches point "Warwick")
                foreach ($racePoints as $point) {
                    if (! empty($point['name']) && str_contains($flightName, strtolower($point['name']))) {
                        $matchedPoint = $point;
                        break;
                    }
                }

                // Strategy 2: type match for finals (flight name contains "final"/"semi")
                if (! $matchedPoint) {
                    if (str_contains($flightName, 'grand final') || (str_contains($flightName, 'final') && ! str_contains($flightName, 'semi') && ! str_contains($flightName, 'super'))) {
                        foreach ($racePoints as $point) {
                            if (($point['type'] ?? '') === 'final') { $matchedPoint = $point; break; }
                        }
                    } elseif (str_contains($flightName, 'super')) {
                        foreach ($racePoints as $point) {
                            if (($point['type'] ?? '') === 'super') { $matchedPoint = $point; break; }
                        }
                    } elseif (str_contains($flightName, 'semi')) {
                        foreach ($racePoints as $point) {
                            if (($point['type'] ?? '') === 'semi') { $matchedPoint = $point; break; }
                        }
                    }
                }

                // Strategy 3: closest distance match (for "Hot Spot 1" → nearest race point by km)
                if (! $matchedPoint && $flightDistance && $flightDistance > 5) {
                    $bestDiff = PHP_FLOAT_MAX;
                    foreach ($racePoints as $point) {
                        if (! empty($point['distance'])) {
                            // Extract km from distance string like "51 Miles / 82 km"
                            if (preg_match('/(\d+)\s*km/i', $point['distance'], $m)) {
                                $pointKm = (float) $m[1];
                                $diff = abs($pointKm - $flightDistance);
                                if ($diff < $bestDiff && $diff < 30) { // within 30km tolerance
                                    $bestDiff = $diff;
                                    $matchedPoint = $point;
                                }
                            }
                        }
                    }
                }

                $liveEvent['point'] = $matchedPoint;
                $liveEvent['loft_lat'] = Setting::get('race_map_loft_lat', '53.05');
                $liveEvent['loft_lng'] = Setting::get('race_map_loft_lng', '-1.48');
            }
        }

        return view('site.home', [
            'loft' => $loft,
            'season' => $season,
            'homepageMode' => $homepageMode,
            'posts' => $posts,
            'upcomingFlights' => $upcomingFlights,
            'latestFlights' => $latestFlights,
            'livestream' => $livestream,
            'pigeonCount' => $pigeonCount,
            'teamCount' => $teamCount,
            'homepageContent' => $homepageContent,
            'liveEvent' => $liveEvent,
        ]);
    }
}

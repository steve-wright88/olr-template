<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Models\Team;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $season = Season::where('loft_id', config('olr.loft_id'))
            ->where('is_active', true)
            ->latest()
            ->first();

        $teams = $season
            ? Team::where('season_id', $season->id)
                ->withCount('pigeons')
                ->orderBy('country')
                ->orderBy('name')
                ->get()
                ->groupBy('country')
            : collect();

        return view('site.teams.index', [
            'season' => $season,
            'teams' => $teams,
        ]);
    }

    public function show(Team $team): View
    {
        $team->load('pigeons');

        $stats = $team->pigeons()
            ->join('results', 'pigeons.id', '=', 'results.pigeon_id')
            ->selectRaw('COUNT(DISTINCT results.flight_id) as flights_entered')
            ->selectRaw('COUNT(results.id) as total_results')
            ->selectRaw('MIN(results.arrival_order) as best_position')
            ->selectRaw('AVG(results.arrival_order) as avg_position')
            ->selectRaw('AVG(results.speed) as avg_speed')
            ->first();

        return view('site.teams.show', [
            'team' => $team,
            'stats' => $stats,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Pigeon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PigeonController extends Controller
{
    public function search(Request $request): View
    {
        $query = $request->input('q');

        $pigeons = $query
            ? Pigeon::where('ring_number', 'LIKE', '%'.strtolower($query).'%')
                ->with(['team', 'results.flight'])
                ->limit(50)
                ->get()
            : collect();

        return view('site.pigeons.search', [
            'pigeons' => $pigeons,
            'query' => $query,
        ]);
    }

    public function show(Pigeon $pigeon): View
    {
        $pigeon->load(['team', 'results.flight']);

        return view('site.pigeons.show', [
            'pigeon' => $pigeon,
        ]);
    }
}

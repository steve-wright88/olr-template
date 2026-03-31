<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\PoolEntry;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PoolController extends Controller
{
    public function create(): View
    {
        $hotspotAmounts = json_decode(Setting::get('pool_hotspot_amounts', '["50p","£1","£2","£3","£5","£10"]'), true);
        $raceAmounts = json_decode(Setting::get('pool_race_amounts', '["£2","£3","£5","£10","£50","£100"]'), true);
        $hotspotNom = Setting::get('pool_hotspot_nom', '£5 Nom');
        $raceNom = Setting::get('pool_race_nom', '£30 Nom');
        $year = Setting::get('entry_year', (string) date('Y'));

        // Get race points from race map settings
        $rawPoints = json_decode(Setting::get('race_map_points', '[]'), true) ?: [];
        $typeLabels = ['hotspot' => 'Hot Spot', 'final' => 'Grand Final', 'super' => 'Super Final', 'semi' => 'Semi Final'];
        $racePoints = collect($rawPoints)->map(function ($p) use ($typeLabels) {
            $name = $p['name'] ?? '';
            $distance = $p['distance'] ?? '';
            $type = $p['type'] ?? '';
            $typeLabel = $typeLabels[$type] ?? '';
            return [
                'name' => $name,
                'type' => $type,
                'label' => $name . ($distance ? ' — ' . $distance : '') . ($typeLabel ? ' (' . $typeLabel . ')' : ''),
            ];
        })->values()->toArray();

        return view('site.pools.create', compact(
            'hotspotAmounts', 'raceAmounts', 'hotspotNom', 'raceNom', 'year', 'racePoints'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pool_type' => 'required|in:hotspot,race',
            'race_point' => 'required|string|max:255',
            'syndicate_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'birds' => 'required|array|min:1|max:50',
            'birds.*.ring_number' => 'required|string|max:50',
            'birds.*.stakes' => 'required|array|min:1',
            'birds.*.bird_total' => 'required|numeric|min:0',
        ]);

        $year = Setting::get('entry_year', (string) date('Y'));

        $grandTotal = collect($validated['birds'])->sum('bird_total');

        $entry = DB::transaction(function () use ($validated, $year, $grandTotal) {
            $entry = PoolEntry::create([
                'reference' => PoolEntry::generateReference(),
                'pool_type' => $validated['pool_type'],
                'race_point' => $validated['race_point'],
                'syndicate_name' => $validated['syndicate_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'season_year' => $year,
            ]);

            foreach ($validated['birds'] as $bird) {
                $entry->birds()->create([
                    'ring_number' => $bird['ring_number'],
                    'stakes' => $bird['stakes'],
                    'bird_total' => $bird['bird_total'],
                ]);
            }

            return $entry;
        });

        return redirect()->route('pools.create')
            ->with('success', "Pool entry submitted! Reference: {$entry->reference}. Total: £" . number_format($grandTotal, 2));
    }
}

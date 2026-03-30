<?php

namespace App\Services;

use App\Models\Flight;
use App\Models\Result;

class AnalysisService
{
    private array $reportKeywords = [
        'loft report', 'loft-list', 'loft list', 'inventar', 'inventario',
        'loft scan', 'loft fly', 'hand scan',
    ];

    public function analyse(int $seasonId): array
    {
        $flights = Flight::where('season_id', $seasonId)
            ->where('status', 'stopped')
            ->get();

        $realFlights = $flights->filter(fn ($f) => ! $this->isReport($f));
        $raceFlights = $realFlights->filter(fn ($f) => ($f->flight_type ?? 'race') !== 'training');
        $trainingFlights = $realFlights->filter(fn ($f) => ($f->flight_type ?? 'race') === 'training');

        $flightIds = $realFlights->pluck('id')->all();

        $results = Result::whereIn('flight_id', $flightIds)
            ->with(['pigeon.team', 'flight'])
            ->get();

        // Build per-flight metadata
        $flightMeta = [];
        foreach ($realFlights as $f) {
            $flightMeta[$f->id] = [
                'name' => $f->name,
                'type' => $f->flight_type ?? 'race',
                'distance' => $f->distance,
                'date' => $f->release_time_local ?? $f->release_time?->toIso8601String(),
                'release_time' => $f->release_time?->toIso8601String(),
                'field_size' => $f->basketings_count ?: $f->arrivals_count ?: 1,
                'avg_speed' => (float) $f->average_speed,
            ];
        }

        // Precompute winner arrival time per flight
        $winnerTimes = [];
        foreach ($results->groupBy('flight_id') as $fid => $fResults) {
            $winner = $fResults->sortBy('arrival_order')->first();
            $winnerTimes[$fid] = $winner?->arrival_time;
        }

        // Group results by pigeon
        $birds = [];
        foreach ($results as $r) {
            $pid = $r->pigeon_id;
            if (! $pid || ! isset($flightMeta[$r->flight_id])) {
                continue;
            }

            $meta = $flightMeta[$r->flight_id];
            $p = $r->pigeon;
            if (! $p) {
                continue;
            }

            if (! isset($birds[$pid])) {
                $birds[$pid] = [
                    'pigeonId' => $pid,
                    'ring' => $p->ring_number ?? '-',
                    'name' => $p->name ?? '',
                    'team' => $p->team?->name ?? '',
                    'country' => $p->team?->country ?? '',
                    'sex' => $p->sex ?? '',
                    'color' => $p->color ?? '',
                    'flights' => [],
                ];
            }

            $pos = $r->arrival_order;
            $spd = (float) $r->speed;
            $coeff = ($pos && $meta['field_size'] > 0) ? ($pos / $meta['field_size']) * 100 : null;

            $duration = null;
            $behind = null;
            if ($r->arrival_time && $meta['release_time']) {
                $arrTime = strtotime($r->arrival_time);
                $libTime = strtotime($meta['release_time']);
                if ($arrTime && $libTime) {
                    $duration = $arrTime - $libTime;
                }
                if ($winnerTimes[$r->flight_id] ?? null) {
                    $winArr = strtotime($winnerTimes[$r->flight_id]);
                    if ($winArr && $arrTime) {
                        $behind = $arrTime - $winArr;
                    }
                }
            }

            $birds[$pid]['flights'][] = [
                'flightId' => $r->flight_id,
                'flightName' => $meta['name'],
                'flightType' => $meta['type'],
                'position' => $pos,
                'fieldSize' => $meta['field_size'],
                'coefficient' => $coeff !== null ? round($coeff, 2) : null,
                'speedMpm' => $spd,
                'speed' => round($spd * 0.06, 2),
                'distance' => $meta['distance'],
                'date' => $meta['date'],
                'liberation' => $meta['release_time'] ? date('H:i', strtotime($meta['release_time'])) : null,
                'duration' => $duration,
                'behind' => $behind,
            ];
        }

        $computeStats = function (string $filter) use ($birds, $raceFlights, $trainingFlights, $realFlights) {
            $filterFlights = match ($filter) {
                'race' => $raceFlights,
                'training' => $trainingFlights,
                default => $realFlights,
            };
            $validIds = $filterFlights->pluck('id')->flip();
            $totalFlights = $validIds->count();

            $stats = [];
            foreach ($birds as $b) {
                $filtered = array_filter($b['flights'], fn ($fl) => $validIds->has($fl['flightId']));
                if (empty($filtered)) {
                    continue;
                }

                $positions = array_filter(array_column($filtered, 'position'));
                $speeds = array_filter(array_column($filtered, 'speedMpm'), fn ($s) => $s > 0);
                $coefficients = array_filter(array_column($filtered, 'coefficient'), fn ($c) => $c !== null);

                $avgPos = count($positions) ? array_sum($positions) / count($positions) : 0;
                $rawCoeff = count($coefficients) ? array_sum($coefficients) / count($coefficients) : 999;
                // Weighted rating: blend real avg with 50% (mid-field) based on participation
                // A bird that did all flights keeps its real rating; fewer flights pulls towards 50%
                $participation = $totalFlights > 0 ? count($coefficients) / $totalFlights : 0;
                $avgCoeff = $rawCoeff < 999 ? ($rawCoeff * $participation) + (50 * (1 - $participation)) : 999;
                $avgSpd = count($speeds) ? array_sum($speeds) / count($speeds) : 0;
                $topSpd = count($speeds) ? max($speeds) : 0;
                $top5 = count(array_filter($coefficients, fn ($c) => $c <= 5));
                $top10 = count(array_filter($coefficients, fn ($c) => $c <= 10));
                $top20 = count(array_filter($coefficients, fn ($c) => $c <= 20));

                $raceCoeffs = array_filter($filtered, fn ($fl) => $fl['flightType'] !== 'training' && $fl['coefficient'] !== null);
                $aceScore = count($raceCoeffs) ? array_sum(array_column($raceCoeffs, 'coefficient')) : null;

                $stats[] = [
                    'pigeonId' => $b['pigeonId'],
                    'ring' => $b['ring'],
                    'name' => $b['name'],
                    'team' => $b['team'],
                    'country' => $b['country'],
                    'sex' => $b['sex'],
                    'color' => $b['color'],
                    'races' => count($positions),
                    'totalFlights' => $totalFlights,
                    'avgPosition' => round($avgPos, 1),
                    'avgCoefficient' => round($avgCoeff, 2),
                    'rawCoefficient' => round($rawCoeff, 2),
                    'top5' => $top5,
                    'top10' => $top10,
                    'top20' => $top20,
                    'aceScore' => $aceScore !== null ? round($aceScore, 1) : null,
                    'avgSpeedMpm' => round($avgSpd, 4),
                    'avgSpeed' => round($avgSpd * 0.06, 2),
                    'topSpeedMpm' => round($topSpd, 4),
                    'topSpeed' => round($topSpd * 0.06, 2),
                    'flights' => array_values($filtered),
                ];
            }

            usort($stats, fn ($a, $b) => $a['avgCoefficient'] <=> $b['avgCoefficient']);

            return $stats;
        };

        return [
            'all' => $computeStats('all'),
            'race' => $computeStats('race'),
            'training' => $computeStats('training'),
            'flightCounts' => [
                'all' => $realFlights->count(),
                'race' => $raceFlights->count(),
                'training' => $trainingFlights->count(),
            ],
        ];
    }

    private function isReport(Flight $f): bool
    {
        $name = strtolower($f->name);
        foreach ($this->reportKeywords as $kw) {
            if (str_contains($name, $kw)) {
                return true;
            }
        }

        return false;
    }
}

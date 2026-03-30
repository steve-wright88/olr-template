<?php

namespace App\Services;

use App\Models\Flight;
use App\Models\Loft;
use App\Models\Pigeon;
use App\Models\Result;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncService
{
    private ?\Closure $onProgress = null;

    public function __construct(private OlrApiClient $api) {}

    public function onProgress(\Closure $callback): self
    {
        $this->onProgress = $callback;

        return $this;
    }

    /**
     * Sync the configured loft and all its seasons/flights/results.
     */
    public function sync(): array
    {
        $loftId = config('olr.loft_id');
        $stats = ['seasons' => 0, 'flights' => 0, 'pigeons' => 0, 'teams' => 0, 'results' => 0];

        $this->progress('Fetching loft details...');
        $detail = $this->api->getLoft($loftId);
        if (! $detail) {
            $this->progress('Failed to fetch loft data.');

            return $stats;
        }

        Loft::updateOrCreate(['id' => $loftId], [
            'id' => $loftId,
            'name' => $detail['name'] ?? '',
            'avatar' => $detail['avatar'] ?? null,
            'country' => $detail['loft_address']['country'] ?? null,
            'city' => $detail['loft_address']['city'] ?? null,
            'operator' => $detail['operator'] ?? null,
            'homepage' => $detail['homepage'] ?? null,
            'synced_at' => now(),
        ]);

        foreach ($detail['seasons'] ?? [] as $season) {
            Season::updateOrCreate(['id' => $season['id']], [
                'loft_id' => $loftId,
                'name' => $season['name'] ?? '',
                'is_active' => $season['is_active'] ?? false,
                'completed' => $season['completed'] ?? false,
                'pigeon_count' => $season['trigger_pigeons_counter'] ?? 0,
                'team_count' => $season['trigger_pigeonteams_counter'] ?? 0,
                'distance' => $season['distance'] ?? null,
                'pricepool' => $season['pricepool'] ?? null,
                'currency' => $detail['currency'] ?? null,
                'synced_at' => now(),
            ]);
            $stats['seasons']++;

            $this->progress("Fetching flights for {$season['name']}...");
            $flightsData = $this->api->getSeasonFlights($season['id']);
            if (! $flightsData) {
                continue;
            }

            foreach (['stopped_flights', 'live_flights', 'upcoming_flights'] as $category) {
                $status = match ($category) {
                    'stopped_flights' => 'stopped',
                    'live_flights' => 'running',
                    'upcoming_flights' => 'upcoming',
                };

                foreach ($flightsData[$category] ?? [] as $flight) {
                    Flight::updateOrCreate(['id' => $flight['id']], [
                        'season_id' => $season['id'],
                        'name' => $flight['name'] ?? '',
                        'flight_type' => $flight['flight_type'] ?? null,
                        'distance' => $flight['distance'] ?? null,
                        'release_time' => $flight['release_time'] ?? null,
                        'release_time_local' => $flight['release_time_local'] ?? null,
                        'arrivals_count' => $flight['trigger_arrivals_counter'] ?? 0,
                        'basketings_count' => $flight['trigger_basketings_counter'] ?? 0,
                        'average_speed' => $flight['trigger_average_speed'] ?? null,
                        'status' => $status,
                        'synced_at' => now(),
                    ]);
                    $stats['flights']++;
                }
            }

            // Sync results for stopped flights
            $stoppedFlights = Flight::where('season_id', $season['id'])
                ->where('status', 'stopped')
                ->get();

            foreach ($stoppedFlights as $idx => $flight) {
                $this->progress('Results: '.($idx + 1)."/{$stoppedFlights->count()} - {$flight->name}");
                $data = $this->api->getFlightResults($flight->id);
                if (! $data) {
                    continue;
                }

                $results = $this->api->mergeResults($data);
                $flightStats = $this->syncFlightResults($flight, $results);
                $stats['pigeons'] += $flightStats['pigeons'];
                $stats['teams'] += $flightStats['teams'];
                $stats['results'] += $flightStats['results'];
            }
        }

        $this->progress('Sync complete!');

        return $stats;
    }

    private function syncFlightResults(Flight $flight, array $results): array
    {
        $stats = ['pigeons' => 0, 'teams' => 0, 'results' => 0];

        DB::beginTransaction();
        try {
            foreach ($results as $result) {
                $pigeon = $result['pigeon'] ?? [];
                $team = $pigeon['pigeon_team'] ?? [];
                $pigeonId = $pigeon['id'] ?? $result['pigeon_id'] ?? null;

                if (! $pigeonId) {
                    continue;
                }

                // Upsert team
                if (! empty($team['id'])) {
                    $wasCreated = Team::updateOrCreate(['id' => $team['id']], [
                        'season_id' => $flight->season_id,
                        'name' => $team['name'] ?? '',
                        'country' => $team['country'] ?? null,
                    ])->wasRecentlyCreated;
                    if ($wasCreated) {
                        $stats['teams']++;
                    }
                }

                // Upsert pigeon (don't overwrite team_id with null)
                $ringNumber = $pigeon['ring_number'] ?? $pigeon['ring_description'] ?? null;
                $pigeonData = [
                    'season_id' => $flight->season_id,
                    'ring_number' => $ringNumber,
                    'name' => $pigeon['name'] ?? null,
                    'color' => $pigeon['color'] ?? null,
                    'sex' => $pigeon['sex'] ?? null,
                ];
                if (! empty($team['id'])) {
                    $pigeonData['team_id'] = $team['id'];
                }
                $wasCreated = Pigeon::updateOrCreate(['id' => $pigeonId], $pigeonData)->wasRecentlyCreated;
                if ($wasCreated) {
                    $stats['pigeons']++;
                }

                // Upsert result
                Result::updateOrCreate(
                    ['flight_id' => $flight->id, 'pigeon_id' => $pigeonId],
                    [
                        'arrival_order' => $result['arrival_order'] ?? null,
                        'speed' => $result['speed'] ?? null,
                        'arrival_time' => $result['fullArrivalTime'] ?? $result['arrival_time'] ?? null,
                    ]
                );
                $stats['results']++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to sync flight results', [
                'flight_id' => $flight->id,
                'message' => $e->getMessage(),
            ]);
        }

        return $stats;
    }

    private function progress(string $message): void
    {
        if ($this->onProgress) {
            ($this->onProgress)($message);
        }
    }
}

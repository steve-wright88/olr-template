<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OlrApiClient
{
    private const BASE_URL = 'https://oneloftrace.live/api';

    public function get(string $path, array $query = []): ?array
    {
        try {
            $response = Http::timeout(15)
                ->withOptions(['verify' => true])
                ->get(self::BASE_URL.$path, $query);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::warning('OLR API fetch failed', ['path' => $path, 'error' => $e->getMessage()]);

            return null;
        }
    }

    public function getLoft(int $loftId): ?array
    {
        return $this->get('/custom/v1/public/loft/'.$loftId);
    }

    public function getSeasonFlights(int $seasonId): ?array
    {
        return $this->get('/custom/v1/public/season/'.$seasonId.'/flightsAndTrainings');
    }

    public function getFlightResults(int $flightId): ?array
    {
        return $this->get('/search/v1/public/flights/'.$flightId.'/results/live', [
            'firstAndLastCount' => [5000, 5000],
        ]);
    }

    public function mergeResults(array $data): array
    {
        $all = $data['all_flight_results'] ?? [];
        if (count($all) > 0) {
            return $all;
        }

        $first = $data['first_flight_results'] ?? [];
        $last = $data['last_flight_results'] ?? [];
        $seen = [];
        $merged = [];

        foreach (array_merge($first, $last) as $result) {
            $pid = $result['pigeon_id'] ?? null;
            if ($pid && ! isset($seen[$pid])) {
                $seen[$pid] = true;
                $merged[] = $result;
            }
        }

        return $merged;
    }
}

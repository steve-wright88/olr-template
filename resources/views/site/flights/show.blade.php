@extends('layouts.app')

@section('title', $flight->name . ' - Results')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Header Card --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 sm:p-8 mb-8">
            <div class="mb-3">
                <a href="{{ route('flights.index') }}" class="text-sm text-gray-400 hover:text-gray-700 transition-colors">&larr; {{ __('t.back_to_results') }}</a>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 uppercase">{{ $flight->name }}</h1>
                @php
                    $nameLower = strtolower($flight->name);
                    $isTraining = $flight->flight_type === 'training';
                    if (str_contains($nameLower, 'hot spot') || str_contains($nameLower, 'hotspot')) {
                        $badgeLabel = __('t.hot_spot'); $badgeBg = 'background:var(--accent);'; $badgeText = 'text-white';
                    } elseif (str_contains($nameLower, 'semi')) {
                        $badgeLabel = __('t.semi_final'); $badgeBg = ''; $badgeText = 'bg-purple-100 text-purple-700';
                    } elseif (preg_match('/\bfinal\b/i', $flight->name) && !str_contains($nameLower, 'semi')) {
                        $badgeLabel = __('t.final'); $badgeBg = ''; $badgeText = 'bg-red-100 text-red-700';
                    } elseif ($isTraining) {
                        $badgeLabel = __('t.training'); $badgeBg = ''; $badgeText = 'bg-gray-100 text-gray-600';
                    } else {
                        $badgeLabel = __('t.race'); $badgeBg = ''; $badgeText = 'bg-blue-100 text-blue-700';
                    }
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded text-xs font-bold uppercase tracking-wide {{ $badgeText }}"
                      @if($badgeBg) style="{{ $badgeBg }}" @endif>
                    {{ $badgeLabel }}
                </span>
            </div>

            {{-- Stats Row --}}
            <div class="flex flex-wrap gap-x-8 gap-y-3">
                @if($flight->release_time)
                    <div>
                        <div class="text-sm font-bold text-gray-900" style="font-family:'Space Grotesk',sans-serif;">{{ $flight->release_time->format('j M, H:i:s') }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ __('t.liberation') }}</div>
                    </div>
                @endif
                @if($flight->distance)
                    <div>
                        <div class="text-sm font-bold text-gray-900" style="font-family:'Space Grotesk',sans-serif;">{{ number_format($flight->distance, 0) }} km</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ __('t.distance') }}</div>
                    </div>
                @endif
                @if($top10Speed)
                    <div>
                        <div class="text-sm font-bold text-gray-900" style="font-family:'Space Grotesk',sans-serif;">{{ number_format($top10Speed, 1) }} m/min</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ __('t.avg_speed_top10') }}</div>
                    </div>
                @endif
                <div>
                    <div class="text-sm font-bold text-gray-900" style="font-family:'Space Grotesk',sans-serif;">{{ $flight->arrivals_count ?? 0 }} / {{ $flight->basketings_count ?? 0 }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">{{ __('t.arrived_basketed') }}</div>
                </div>
            </div>
        </div>

        {{-- Results Section --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden"
             x-data="{
                search: '',
                flagMap: { 'WA': 'gb-wls', 'XS': 'gb-sct', 'EN': 'gb-eng', 'NI': 'gb-nir' },
                flagCode(c) { return this.flagMap[c] || c.toLowerCase(); },
                results: {{ Js::from($results->map(fn ($r) => [
                    'pos' => $r->arrival_order,
                    'ring' => $r->pigeon->ring_number ?? '-',
                    'team' => $r->pigeon->team->name ?? '-',
                    'country' => $r->pigeon->team->country ?? '',
                    'speed' => round($r->speed, 2),
                    'kmh' => round($r->speed * 0.06, 1),
                    'arrival' => $r->arrival_time ? \Carbon\Carbon::parse($r->arrival_time)->format('j M, H:i:s') : '-',
                    'time' => $r->arrival_time && $flight->release_time
                        ? (function() use ($r, $flight) {
                            $diff = \Carbon\Carbon::parse($r->arrival_time)->diff($flight->release_time);
                            return $diff->h . 'h ' . str_pad($diff->i, 2, '0', STR_PAD_LEFT) . 'm ' . str_pad($diff->s, 2, '0', STR_PAD_LEFT) . 's';
                        })()
                        : '-',
                    'behind' => $r->behind_winner !== null
                        ? ($r->behind_winner === 0
                            ? '-'
                            : '+' . (function() use ($r) {
                                $s = $r->behind_winner;
                                $h = floor($s / 3600);
                                $m = floor(($s % 3600) / 60);
                                $sec = $s % 60;
                                if ($h > 0) return $h . 'h ' . str_pad($m, 2, '0', STR_PAD_LEFT) . 'm ' . str_pad($sec, 2, '0', STR_PAD_LEFT) . 's';
                                if ($m > 0) return $m . 'm ' . str_pad($sec, 2, '0', STR_PAD_LEFT) . 's';
                                return $sec . 's';
                            })())
                        : '-',
                ])) }},
                get filtered() {
                    if (!this.search.trim()) return this.results;
                    const q = this.search.toLowerCase();
                    return this.results.filter(r =>
                        r.ring.toLowerCase().includes(q) ||
                        r.team.toLowerCase().includes(q)
                    );
                }
            }">

            {{-- Results Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-200">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('t.results') }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('t.arrivals_shown', ['count' => $results->count()]) }}</p>
                </div>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="search" placeholder="{{ __('t.search_ring_team') }}"
                           class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-gray-400 focus:bg-white w-56 transition-colors">
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-left text-xs font-bold uppercase tracking-wider text-gray-400">
                            <th class="pl-6 pr-2 py-3 w-14 text-center">{{ __('t.pos') }}</th>
                            <th class="px-3 py-3">{{ __('t.ring') }}</th>
                            <th class="px-3 py-3">{{ __('t.team') }}</th>
                            <th class="px-3 py-3 text-right">{{ __('t.speed') }}</th>
                            <th class="px-3 py-3 text-right">km/h</th>
                            <th class="px-3 py-3 text-right hidden sm:table-cell">{{ __('t.arrival') }}</th>
                            <th class="px-3 py-3 text-right hidden md:table-cell">{{ __('t.time') }}</th>
                            <th class="pr-6 pl-3 py-3 text-right">{{ __('t.behind') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="r in filtered" :key="r.pos">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="pl-6 pr-2 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold tabular-nums"
                                          :class="r.pos <= 10 ? 'text-white' : 'text-gray-600 bg-gray-100'"
                                          :style="r.pos <= 10 ? 'background: var(--accent)' : ''"
                                          x-text="r.pos"></span>
                                </td>
                                <td class="px-3 py-3 font-mono text-xs font-medium text-gray-700" x-text="r.ring"></td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-2">
                                        <template x-if="r.country">
                                            <img :src="'https://flagcdn.com/20x15/' + flagCode(r.country) + '.png'"
                                                 :alt="r.country" class="inline-block rounded-sm flex-shrink-0" width="20" height="15" loading="lazy">
                                        </template>
                                        <span class="font-medium text-gray-900" x-text="r.team"></span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-right font-semibold tabular-nums text-gray-900" style="font-family:'Space Grotesk',sans-serif;" x-text="r.speed.toFixed(1)"></td>
                                <td class="px-3 py-3 text-right tabular-nums text-gray-500" style="font-family:'Space Grotesk',sans-serif;" x-text="r.kmh.toFixed(1)"></td>
                                <td class="px-3 py-3 text-right tabular-nums text-gray-500 hidden sm:table-cell" style="font-family:'Space Grotesk',sans-serif;" x-text="r.arrival"></td>
                                <td class="px-3 py-3 text-right tabular-nums text-gray-500 hidden md:table-cell" style="font-family:'Space Grotesk',sans-serif;" x-text="r.time"></td>
                                <td class="pr-6 pl-3 py-3 text-right tabular-nums text-sm"
                                    :class="r.behind === '-' ? 'text-gray-300' : 'text-orange-500 font-medium'"
                                    x-text="r.behind"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Empty state --}}
            <div x-show="filtered.length === 0" class="px-6 py-12 text-center text-gray-400">
                <p x-show="search.trim()">{{ __('t.no_results_search') }}</p>
                <p x-show="!search.trim()">{{ __('t.no_results') }}</p>
            </div>
        </div>

    </div>
@endsection

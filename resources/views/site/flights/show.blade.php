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

            {{-- Weather Cards --}}
            @if($flight->status === 'stopped' && $flight->release_time)
                <p class="text-xs text-gray-400 mt-5 mb-1.5 font-medium">
                    @if($flight->flight_type === 'training')
                        <span class="italic">Weather at the loft</span> {{ $flight->release_time->format('j M Y') }}
                    @else
                        <span class="italic">Race day weather</span> {{ $flight->release_time->format('j M Y') }}
                    @endif
                </p>
            @endif

            @php $hasLiberation = $matchedPoint && !empty($matchedPoint['lat']) && !empty($matchedPoint['lng']); @endphp
            <div class="grid {{ $hasLiberation ? 'grid-cols-3' : 'grid-cols-1' }} gap-2 max-w-md" x-data="flightWeather()" x-init="fetchWeather()">
                {{-- Loft --}}
                <div class="bg-gray-50 rounded-lg px-3 py-2.5">
                    <div class="text-[9px] uppercase tracking-wider text-gray-400 font-bold mb-1.5">Loft</div>
                    <template x-if="loft.loaded">
                        <div>
                            <div class="flex items-center gap-1">
                                <span class="text-lg font-black text-gray-900" x-text="loft.temp + '°'"></span>
                                <img :src="'https://openweathermap.org/img/wn/' + loft.icon + '.png'" class="w-6 h-6" alt="">
                            </div>
                            <div class="flex items-center gap-1 mt-1">
                                <svg class="w-2.5 h-2.5 text-gray-400 transition-transform" :style="'transform: rotate(' + loft.windDeg + 'deg)'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                <span class="text-[9px] text-gray-500" x-text="loft.windSpeed + ' mph ' + loft.windDir"></span>
                            </div>
                            <div class="text-[9px] text-gray-400 mt-0.5" x-text="loft.description"></div>
                        </div>
                    </template>
                    <template x-if="!loft.loaded"><div class="animate-pulse"><div class="h-6 w-12 bg-gray-200 rounded"></div></div></template>
                </div>

                @if($hasLiberation)
                {{-- Liberation --}}
                <div class="bg-gray-50 rounded-lg px-3 py-2.5">
                    <div class="text-[9px] uppercase tracking-wider text-gray-400 font-bold mb-1.5">{{ $matchedPoint['name'] ?? 'Liberation' }}</div>
                    <template x-if="liberation.loaded">
                        <div>
                            <div class="flex items-center gap-1">
                                <span class="text-lg font-black text-gray-900" x-text="liberation.temp + '°'"></span>
                                <img :src="'https://openweathermap.org/img/wn/' + liberation.icon + '.png'" class="w-6 h-6" alt="">
                            </div>
                            <div class="flex items-center gap-1 mt-1">
                                <svg class="w-2.5 h-2.5 text-gray-400 transition-transform" :style="'transform: rotate(' + liberation.windDeg + 'deg)'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                <span class="text-[9px] text-gray-500" x-text="liberation.windSpeed + ' mph ' + liberation.windDir"></span>
                            </div>
                            <div class="text-[9px] text-gray-400 mt-0.5" x-text="liberation.description"></div>
                        </div>
                    </template>
                    <template x-if="!liberation.loaded"><div class="animate-pulse"><div class="h-6 w-12 bg-gray-200 rounded"></div></div></template>
                </div>

                {{-- Wind --}}
                <div class="bg-gray-50 rounded-lg px-3 py-2.5">
                    <div class="text-[9px] uppercase tracking-wider text-gray-400 font-bold mb-1.5">Wind</div>
                    <template x-if="windAnalysis.loaded">
                        <div>
                            <div class="text-sm font-black" :class="windAnalysis.colorClass" x-text="windAnalysis.label"></div>
                            <div class="text-[9px] text-gray-500 mt-0.5" x-text="windAnalysis.detail"></div>
                            <div class="flex items-center gap-1.5 mt-1.5">
                                <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-700" :class="windAnalysis.barClass" :style="'width:' + windAnalysis.strength + '%'"></div>
                                </div>
                                <span class="text-[9px] text-gray-400" x-text="liberation.windSpeed + 'mph'"></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="!windAnalysis.loaded"><div class="animate-pulse"><div class="h-6 w-12 bg-gray-200 rounded"></div></div></template>
                </div>
                @endif
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
                    'speed' => round($r->speed, 4),
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
                                <td class="px-3 py-3 font-mono text-xs font-medium text-gray-700 whitespace-nowrap" x-text="r.ring"></td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-2">
                                        <template x-if="r.country">
                                            <img :src="'https://flagcdn.com/20x15/' + flagCode(r.country) + '.png'"
                                                 :alt="r.country" class="inline-block rounded-sm flex-shrink-0" width="20" height="15" loading="lazy">
                                        </template>
                                        <span class="font-medium text-gray-900" x-text="r.team"></span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-right font-semibold tabular-nums text-gray-900" style="font-family:'Space Grotesk',sans-serif;" x-text="r.speed.toFixed(4)"></td>
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

            {{-- Data disclaimer --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                <p class="text-[11px] text-gray-400 leading-relaxed">Race data including results, speeds, arrival times, and pigeon information is sourced directly from <a href="https://oneloftrace.live" target="_blank" rel="noopener" class="underline hover:text-gray-600">oneloftrace.live</a>. {{ config('olr.site_name') }} does not independently verify this data. Any discrepancies, missing entries, or inaccuracies should be reported to the race timing provider.</p>
            </div>
        </div>

    </div>

    <script>
    function flightWeather() {
        const hasLiberation = {{ ($matchedPoint && !empty($matchedPoint['lat']) && !empty($matchedPoint['lng'])) ? 'true' : 'false' }};
        const libLat = {{ $matchedPoint['lat'] ?? $loftLat }};
        const libLng = {{ $matchedPoint['lng'] ?? $loftLng }};
        const loftLat = {{ $loftLat }};
        const loftLng = {{ $loftLng }};
        const flightDate = '{{ $flight->release_time ? $flight->release_time->format("Y-m-d") : "" }}';
        const isCompleted = {{ $flight->status === 'stopped' ? 'true' : 'false' }};

        const wmoMap = {
            0:['Clear sky','01d'],1:['Mainly clear','02d'],2:['Partly cloudy','03d'],3:['Overcast','04d'],
            45:['Foggy','50d'],48:['Rime fog','50d'],51:['Light drizzle','09d'],53:['Drizzle','09d'],55:['Heavy drizzle','09d'],
            61:['Light rain','10d'],63:['Rain','10d'],65:['Heavy rain','10d'],71:['Light snow','13d'],73:['Snow','13d'],75:['Heavy snow','13d'],
            80:['Light showers','09d'],81:['Showers','09d'],82:['Heavy showers','09d'],95:['Thunderstorm','11d'],96:['Thunderstorm+hail','11d'],99:['Thunderstorm+hail','11d'],
        };

        function degToCompass(deg) {
            const dirs = ['N','NNE','NE','ENE','E','ESE','SE','SSE','S','SSW','SW','WSW','W','WNW','NW','NNW'];
            return dirs[Math.round(deg / 22.5) % 16];
        }

        function bearingFromTo(lat1,lng1,lat2,lng2) {
            const toRad = d => d*Math.PI/180;
            const dLng = toRad(lng2-lng1);
            const y = Math.sin(dLng)*Math.cos(toRad(lat2));
            const x = Math.cos(toRad(lat1))*Math.sin(toRad(lat2))-Math.sin(toRad(lat1))*Math.cos(toRad(lat2))*Math.cos(dLng);
            return (Math.atan2(y,x)*180/Math.PI+360)%360;
        }

        function analyseWind(windDeg, windSpeed) {
            const routeBearing = bearingFromTo(libLat,libLng,loftLat,loftLng);
            let angleDiff = Math.abs(windDeg - routeBearing);
            if (angleDiff > 180) angleDiff = 360 - angleDiff;
            let label,detail,colorClass,barClass;
            const strength = Math.min(100, (windSpeed / 40) * 100);
            if (angleDiff <= 30) { label='Tailwind'; detail='Wind pushing birds home'; colorClass='text-green-600'; barClass='bg-green-500'; }
            else if (angleDiff <= 60) { label='Tail/Cross'; detail='Helpful crosswind'; colorClass='text-green-500'; barClass='bg-green-400'; }
            else if (angleDiff <= 90) { label='Crosswind'; detail='Wind across the route'; colorClass='text-yellow-500'; barClass='bg-yellow-400'; }
            else if (angleDiff <= 150) { label='Head/Cross'; detail='Difficult crosswind'; colorClass='text-orange-500'; barClass='bg-orange-400'; }
            else { label='Headwind'; detail='Wind against the birds'; colorClass='text-red-500'; barClass='bg-red-400'; }
            return { label, detail, colorClass, barClass, strength, loaded: true };
        }

        async function fetchCurrent(lat, lng) {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,weather_code,wind_speed_10m,wind_direction_10m&wind_speed_unit=mph&timezone=auto`;
            const res = await fetch(url); const data = await res.json(); const c = data.current;
            const [desc,icon] = wmoMap[c.weather_code]||['Unknown','03d'];
            return { temp:Math.round(c.temperature_2m), windSpeed:Math.round(c.wind_speed_10m), windDeg:c.wind_direction_10m, windDir:degToCompass(c.wind_direction_10m), description:desc, icon, loaded:true };
        }

        async function fetchHistorical(lat, lng, date) {
            const url = `https://archive-api.open-meteo.com/v1/archive?latitude=${lat}&longitude=${lng}&start_date=${date}&end_date=${date}&hourly=temperature_2m,weather_code,wind_speed_10m,wind_direction_10m&wind_speed_unit=mph&timezone=auto`;
            const res = await fetch(url); const data = await res.json(); const h = data.hourly;
            const [desc,icon] = wmoMap[h.weather_code[12]]||['Unknown','03d'];
            return { temp:Math.round(h.temperature_2m[12]), windSpeed:Math.round(h.wind_speed_10m[12]), windDeg:h.wind_direction_10m[12], windDir:degToCompass(h.wind_direction_10m[12]), description:desc, icon, loaded:true };
        }

        return {
            liberation: { loaded: false },
            loft: { loaded: false },
            windAnalysis: { loaded: false },
            async fetchWeather() {
                try {
                    const fetcher = isCompleted && flightDate ? (lat,lng) => fetchHistorical(lat,lng,flightDate) : fetchCurrent;
                    const loftData = await fetcher(loftLat, loftLng);
                    this.loft = loftData;

                    if (hasLiberation) {
                        const lib = await fetcher(libLat, libLng);
                        this.liberation = lib;
                        this.windAnalysis = analyseWind(lib.windDeg, lib.windSpeed);
                    }
                } catch(e) { console.error('Weather fetch failed', e); }
            }
        };
    }
    </script>
@endsection

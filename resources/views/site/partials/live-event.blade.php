@if(isset($liveEvent) && $liveEvent)
@php
    $flight = $liveEvent['flight'];
    $mode = $liveEvent['mode']; // 'live', 'next', or 'result'
    $point = $liveEvent['point'] ?? null;
    $loftLat = $liveEvent['loft_lat'] ?? '53.05';
    $loftLng = $liveEvent['loft_lng'] ?? '-1.48';
    $hasCoords = $point && !empty($point['lat']) && !empty($point['lng']);

    $isTraining = $flight->flight_type === 'training';
    $flightNameLower = strtolower($flight->name);
    if (str_contains($flightNameLower, 'hot spot') || str_contains($flightNameLower, 'hotspot')) {
        $eventType = 'Hot Spot Race';
    } elseif (str_contains($flightNameLower, 'final') && !str_contains($flightNameLower, 'semi')) {
        $eventType = 'Grand Final';
    } elseif (str_contains($flightNameLower, 'semi')) {
        $eventType = 'Semi Final';
    } elseif ($isTraining) {
        $eventType = 'Training Toss';
    } else {
        $eventType = 'Race';
    }
@endphp

<section class="relative overflow-hidden" style="background: var(--primary);"
         @if($hasCoords) x-data="liveEventWeather()" x-init="fetchWeather()" @endif>

    {{-- Animated background pattern for live events --}}
    @if($mode === 'live')
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.03) 10px, rgba(255,255,255,0.03) 20px);"></div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative">

        {{-- Top status bar --}}
        <div class="flex items-center gap-3 mb-4">
            @if($mode === 'live')
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-widest bg-red-600 text-white shadow-lg shadow-red-600/30">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                    </span>
                    {{ __('t.live_now') }}
                </span>
            @elseif($mode === 'result')
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-white/15 text-white/80">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('t.latest_result') }}
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest text-white" style="background: var(--accent);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('t.next_event') }}
                </span>
            @endif
            <span class="text-xs font-semibold text-white/40 uppercase tracking-wider">{{ $eventType }}</span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-end gap-6 lg:gap-10">

            {{-- Event Info --}}
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">{{ $flight->name }}</h2>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-3">
                    @if($point && !empty($point['distance']))
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-sm font-semibold text-white/70">{{ $point['distance'] }}</span>
                        </div>
                    @elseif($flight->distance)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-sm font-semibold text-white/70">{{ number_format($flight->distance, 0) }} km</span>
                        </div>
                    @endif
                    @if($flight->release_time)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-sm font-semibold text-white/70">{{ $flight->release_time->format('l j M Y') }}</span>
                        </div>
                    @endif
                    @if(($mode === 'live' || $mode === 'result') && $flight->arrivals_count)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span class="text-sm font-bold text-white">{{ $flight->arrivals_count }} / {{ $flight->basketings_count ?? '?' }} {{ $mode === 'result' ? 'birds clocked' : 'arrived' }}</span>
                        </div>
                    @endif
                    @if($mode === 'result' && $flight->average_speed)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span class="text-sm font-bold text-white">{{ number_format($flight->average_speed, 1) }} m/min avg</span>
                        </div>
                    @endif
                </div>

                @if($mode === 'live' || $mode === 'result')
                    <div class="mt-4">
                        <a href="{{ route('flights.show', $flight) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold text-white transition-all hover:scale-105 hover:shadow-lg" style="background: var(--accent); box-shadow: 0 4px 14px rgba(0,0,0,0.2);">
                            {{ $mode === 'live' ? __('t.view_live_results') : __('t.view_full_results') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Weather Cards --}}
            @if($hasCoords)
            <div class="flex flex-col items-end flex-shrink-0">
                <p class="text-[10px] text-white/75 mb-2 italic text-center lg:text-right">{{ __('t.weather_caveat', ['date' => now()->format('j M Y')]) }}</p>
                <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:flex lg:flex-nowrap">
                    {{-- Liberation Point Weather --}}
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg sm:rounded-xl px-3 py-2.5 sm:px-5 sm:py-4 lg:min-w-[170px] border border-white/5">
                        <div class="text-[9px] sm:text-[10px] uppercase tracking-wider text-white/40 font-bold mb-1.5 sm:mb-2">{{ $point['name'] ?? 'Liberation' }}</div>
                        <template x-if="liberation.loaded">
                            <div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <span class="text-xl sm:text-3xl font-black text-white" x-text="liberation.temp + '°'"></span>
                                    <img :src="'https://openweathermap.org/img/wn/' + liberation.icon + '.png'" class="w-7 h-7 sm:w-10 sm:h-10" alt="" x-show="liberation.icon">
                                </div>
                                <div class="flex items-center gap-1 sm:gap-1.5 mt-1">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-white/50 transition-transform" :style="'transform: rotate(' + liberation.windDeg + 'deg)'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                    <span class="text-[10px] sm:text-xs font-semibold text-white/50" x-text="liberation.windSpeed + ' mph ' + liberation.windDir"></span>
                                </div>
                                <div class="hidden sm:block text-[11px] text-white/35 mt-0.5" x-text="liberation.description"></div>
                            </div>
                        </template>
                        <template x-if="!liberation.loaded">
                            <div class="animate-pulse space-y-2">
                                <div class="h-6 sm:h-8 w-12 sm:w-16 bg-white/10 rounded"></div>
                                <div class="h-3 w-16 sm:w-24 bg-white/10 rounded"></div>
                            </div>
                        </template>
                    </div>

                    {{-- Loft Weather --}}
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg sm:rounded-xl px-3 py-2.5 sm:px-5 sm:py-4 lg:min-w-[170px] border border-white/5">
                        <div class="text-[9px] sm:text-[10px] uppercase tracking-wider text-white/40 font-bold mb-1.5 sm:mb-2">{{ __('t.loft') }}</div>
                        <template x-if="loft.loaded">
                            <div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <span class="text-xl sm:text-3xl font-black text-white" x-text="loft.temp + '°'"></span>
                                    <img :src="'https://openweathermap.org/img/wn/' + loft.icon + '.png'" class="w-7 h-7 sm:w-10 sm:h-10" alt="" x-show="loft.icon">
                                </div>
                                <div class="flex items-center gap-1 sm:gap-1.5 mt-1">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-white/50 transition-transform" :style="'transform: rotate(' + loft.windDeg + 'deg)'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                    <span class="text-[10px] sm:text-xs font-semibold text-white/50" x-text="loft.windSpeed + ' mph ' + loft.windDir"></span>
                                </div>
                                <div class="hidden sm:block text-[11px] text-white/35 mt-0.5" x-text="loft.description"></div>
                            </div>
                        </template>
                        <template x-if="!loft.loaded">
                            <div class="animate-pulse space-y-2">
                                <div class="h-6 sm:h-8 w-12 sm:w-16 bg-white/10 rounded"></div>
                                <div class="h-3 w-16 sm:w-24 bg-white/10 rounded"></div>
                            </div>
                        </template>
                    </div>

                    {{-- Wind Analysis --}}
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg sm:rounded-xl px-3 py-2.5 sm:px-5 sm:py-4 lg:min-w-[155px] border border-white/5">
                        <div class="text-[9px] sm:text-[10px] uppercase tracking-wider text-white/40 font-bold mb-1.5 sm:mb-2">{{ __('t.wind') }}</div>
                        <template x-if="windAnalysis.loaded">
                            <div>
                                <div class="text-base sm:text-xl font-black" :class="windAnalysis.colorClass" x-text="windAnalysis.label"></div>
                                <div class="hidden sm:block text-[11px] text-white/50 font-medium mt-1" x-text="windAnalysis.detail"></div>
                                <div class="mt-1.5 sm:mt-2.5 flex items-center gap-1 sm:gap-1.5">
                                    <div class="w-full bg-white/10 rounded-full h-1.5 sm:h-2">
                                        <div class="h-1.5 sm:h-2 rounded-full transition-all duration-700" :class="windAnalysis.barClass" :style="'width:' + windAnalysis.strength + '%'"></div>
                                    </div>
                                    <span class="text-[9px] sm:text-[10px] font-bold text-white/30" x-text="liberation.windSpeed + 'mph'"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="!windAnalysis.loaded">
                            <div class="animate-pulse space-y-2">
                                <div class="h-5 sm:h-7 w-16 sm:w-24 bg-white/10 rounded"></div>
                                <div class="h-3 w-16 sm:w-28 bg-white/10 rounded"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Bottom accent line --}}
    <div class="h-1" style="background: var(--accent);"></div>

    @if($hasCoords)
    <script>
    function liveEventWeather() {
        const libLat = {{ $point['lat'] }};
        const libLng = {{ $point['lng'] }};
        const loftLat = {{ $loftLat }};
        const loftLng = {{ $loftLng }};

        function degToCompass(deg) {
            const dirs = ['N','NNE','NE','ENE','E','ESE','SE','SSE','S','SSW','SW','WSW','W','WNW','NW','NNW'];
            return dirs[Math.round(deg / 22.5) % 16];
        }

        function bearingFromTo(lat1, lng1, lat2, lng2) {
            const toRad = d => d * Math.PI / 180;
            const dLng = toRad(lng2 - lng1);
            const y = Math.sin(dLng) * Math.cos(toRad(lat2));
            const x = Math.cos(toRad(lat1)) * Math.sin(toRad(lat2)) - Math.sin(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.cos(dLng);
            return (Math.atan2(y, x) * 180 / Math.PI + 360) % 360;
        }

        function analyseWind(windDeg, windSpeed) {
            const flightBearing = bearingFromTo(libLat, libLng, loftLat, loftLng);
            let diff = ((windDeg - flightBearing) + 360) % 360;
            let angleDiff = diff > 180 ? 360 - diff : diff;

            let label, detail, colorClass, barClass, strength;

            if (angleDiff <= 30) {
                label = 'Tailwind'; detail = 'Wind pushing birds home';
                colorClass = 'text-green-400'; barClass = 'bg-green-400';
                strength = Math.min(100, 50 + windSpeed * 3);
            } else if (angleDiff <= 60) {
                label = 'Tail/Cross'; detail = 'Helpful crosswind';
                colorClass = 'text-green-300'; barClass = 'bg-green-300';
                strength = Math.min(90, 40 + windSpeed * 2);
            } else if (angleDiff <= 120) {
                label = 'Crosswind'; detail = 'Wind across the route';
                colorClass = 'text-yellow-400'; barClass = 'bg-yellow-400';
                strength = Math.min(80, 30 + windSpeed * 2);
            } else if (angleDiff <= 150) {
                label = 'Head/Cross'; detail = 'Difficult crosswind';
                colorClass = 'text-orange-400'; barClass = 'bg-orange-400';
                strength = Math.min(90, 40 + windSpeed * 2);
            } else {
                label = 'Headwind'; detail = 'Wind against the birds';
                colorClass = 'text-red-400'; barClass = 'bg-red-400';
                strength = Math.min(100, 50 + windSpeed * 3);
            }

            return { label, detail, colorClass, barClass, strength, loaded: true };
        }

        async function fetchOpenMeteo(lat, lng) {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,weather_code,wind_speed_10m,wind_direction_10m&wind_speed_unit=mph&timezone=auto`;
            const res = await fetch(url);
            const data = await res.json();
            const c = data.current;

            const wmoMap = {
                0: ['Clear sky', '01d'], 1: ['Mainly clear', '02d'], 2: ['Partly cloudy', '03d'], 3: ['Overcast', '04d'],
                45: ['Foggy', '50d'], 48: ['Rime fog', '50d'],
                51: ['Light drizzle', '09d'], 53: ['Drizzle', '09d'], 55: ['Heavy drizzle', '09d'],
                61: ['Light rain', '10d'], 63: ['Rain', '10d'], 65: ['Heavy rain', '10d'],
                71: ['Light snow', '13d'], 73: ['Snow', '13d'], 75: ['Heavy snow', '13d'],
                80: ['Light showers', '09d'], 81: ['Showers', '09d'], 82: ['Heavy showers', '09d'],
                95: ['Thunderstorm', '11d'], 96: ['Thunderstorm + hail', '11d'], 99: ['Thunderstorm + hail', '11d'],
            };

            const [desc, icon] = wmoMap[c.weather_code] || ['Unknown', '03d'];

            return {
                temp: Math.round(c.temperature_2m),
                windSpeed: Math.round(c.wind_speed_10m),
                windDeg: c.wind_direction_10m,
                windDir: degToCompass(c.wind_direction_10m),
                description: desc,
                icon: icon,
                loaded: true,
            };
        }

        return {
            liberation: { loaded: false },
            loft: { loaded: false },
            windAnalysis: { loaded: false },

            async fetchWeather() {
                try {
                    const [lib, loftData] = await Promise.all([
                        fetchOpenMeteo(libLat, libLng),
                        fetchOpenMeteo(loftLat, loftLng),
                    ]);
                    this.liberation = lib;
                    this.loft = loftData;
                    this.windAnalysis = analyseWind(lib.windDeg, lib.windSpeed);
                } catch (e) {
                    console.error('Weather fetch failed:', e);
                }
            }
        };
    }
    </script>
    @endif
</section>
@endif

@extends('layouts.app')

@section('title', __('t.results') . ' | ' . ($season->name ?? config('olr.site_name')))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Page Header with Season Picker --}}
    <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('t.results') }}</h1>
            @if($season)
                <p class="mt-1 text-gray-500">{{ $season->name }}</p>
            @endif
        </div>
        @if($seasons->count() > 1)
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-400 font-medium">{{ __('t.season') }}</span>
                <select onchange="window.location.href=this.value"
                        class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] cursor-pointer appearance-none pr-8"
                        style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E&quot;); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.25em;">
                    @foreach($seasons as $s)
                        <option value="{{ route('flights.index', ['season' => $s->id]) }}" {{ $season && $season->id === $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    {{-- Live Flights --}}
    @if(($flights['live'] ?? collect())->isNotEmpty())
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: var(--accent);"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3" style="background: var(--accent);"></span>
                </span>
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400">{{ __('t.live_now') }}</h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($flights['live'] as $flight)
                    <a href="{{ route('flights.show', $flight) }}" class="block rounded-xl border-2 p-5 transition-all hover:shadow-lg hover:scale-[1.01]" style="border-color: var(--accent);">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-900">{{ $flight->name }}</h3>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold text-white" style="background: var(--accent);">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                </span>
                                LIVE
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                            @if($flight->distance)
                                <span class="font-semibold">{{ number_format($flight->distance, 0) }} km</span>
                            @endif
                            <span>{{ $flight->arrivals_count ?? 0 }} / {{ $flight->basketings_count ?? 0 }} {{ __('t.arrived') }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Completed Flights --}}
    @if(($flights['completed'] ?? collect())->isNotEmpty())
        <div class="mb-12">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4">{{ __('t.completed') }}</h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($flights['completed'] as $flight)
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
                    <a href="{{ route('flights.show', $flight) }}"
                       class="block rounded-lg border border-gray-200 bg-white px-4 py-3 transition-all hover:shadow-md hover:border-gray-300">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-sm font-bold text-gray-900 uppercase truncate">{{ $flight->name }}</h3>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide flex-shrink-0 {{ $badgeText }}"
                                  @if($badgeBg) style="{{ $badgeBg }}" @endif>
                                {{ $badgeLabel }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 mt-1.5 text-xs text-gray-500">
                            @if($flight->distance)
                                <span class="font-semibold text-gray-700">{{ number_format($flight->distance, 0) }} km</span>
                            @endif
                            @if($flight->top10_speed)
                                <span style="font-family:'Space Grotesk',sans-serif;">{{ number_format($flight->top10_speed, 1) }} m/min</span>
                            @endif
                            @if($flight->arrivals_count)
                                <span>{{ $flight->arrivals_count }}/{{ $flight->basketings_count ?? '?' }} {{ __('t.arrived') }}</span>
                            @endif
                            @if($flight->release_time)
                                <span class="text-gray-400">{{ $flight->release_time->format('j M Y') }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Upcoming Flights --}}
    @if(($flights['upcoming'] ?? collect())->isNotEmpty())
        <div class="mb-12">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4">{{ __('t.upcoming') }}</h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($flights['upcoming'] as $flight)
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-3">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-sm font-bold text-gray-900 uppercase truncate">{{ $flight->name }}</h3>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-amber-100 text-amber-700 flex-shrink-0">{{ __('t.upcoming') }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 mt-1.5 text-xs text-gray-500">
                            @if($flight->distance)
                                <span class="font-semibold text-gray-700">{{ number_format($flight->distance, 0) }} km</span>
                            @endif
                            @if($flight->release_time)
                                <span class="text-gray-400">{{ $flight->release_time->format('j M Y') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Empty State --}}
    @if(!$season || $flights->isEmpty())
        <div class="text-center py-20">
            <p class="text-lg text-gray-400">{{ __('t.no_flights') }}</p>
        </div>
    @endif

    {{-- Bird Performance Analysis --}}
    @if($season && ($flights['completed'] ?? collect())->isNotEmpty())
        @php
            $analysisFlightCount = ($flights['completed'] ?? collect())->count();
        @endphp
        <a href="#analysis" class="block w-full rounded-xl py-4 text-center font-bold text-white transition-all hover:shadow-lg hover:scale-[1.005]" style="background: var(--accent);">
            <svg class="inline-block w-5 h-5 mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            {{ __('t.analyse_performance') }}
            <span class="ml-1 text-white/70 font-normal text-sm">{{ __('t.across_flights', ['count' => $analysisFlightCount]) }}</span>
        </a>

        <section id="analysis" class="mt-10 pt-10 border-t border-gray-200" x-data="analysisApp()" x-init="init()">
            <div class="mb-6">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('t.bird_performance') }}</h2>
                <p class="text-gray-500 mt-1 text-sm">{{ __('t.click_bird_detail') }}</p>
            </div>

            {{-- Loading State --}}
            <div x-show="loading" class="text-center py-16">
                <svg class="animate-spin h-8 w-8 mx-auto mb-3" style="color: var(--accent);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-gray-400">{{ __('t.loading') }}</p>
            </div>

            {{-- Top Teams --}}
            <div x-show="!loading && topTeams().length > 0" x-cloak class="mb-8">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">{{ __('t.top_lofts') }} | <span class="font-normal normal-case">{{ __('t.based_on_best_3') }}</span></h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <template x-for="(team, idx) in topTeams()" :key="team.name">
                        <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                 :class="idx === 0 ? 'bg-yellow-100 text-yellow-700' : idx === 1 ? 'bg-gray-100 text-gray-500' : 'bg-amber-50 text-amber-700'"
                                 x-text="idx + 1"></div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <template x-if="team.country">
                                        <img :src="'https://flagcdn.com/20x15/' + flagCode(team.country) + '.png'" class="rounded-sm flex-shrink-0" width="16" height="12" loading="lazy">
                                    </template>
                                    <span class="font-semibold text-gray-900 text-sm truncate" x-text="team.name"></span>
                                </div>
                                <div class="flex gap-3 mt-0.5 text-xs text-gray-500">
                                    <span><span class="font-semibold text-gray-700" x-text="team.birds"></span> {{ __('t.birds_entered') }}</span>
                                    <span>{{ __('t.avg_pos') }} <span class="font-semibold text-gray-700" x-text="team.avgPos.toFixed(0)"></span></span>
                                    <span class="hidden sm:inline">{{ __('t.avg_speed') }} <span class="font-semibold text-gray-700" x-text="team.avgSpeed.toFixed(0)"></span> m/min</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Controls Row --}}
            <div x-show="!loading" x-cloak class="flex flex-wrap items-center gap-3 mb-4">
                {{-- Tabs --}}
                <div class="flex gap-1 bg-gray-100 rounded-lg p-1">
                    <button @click="setTab('race')"
                            :class="tab === 'race' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                            class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors">
                        {{ __('t.races') }} <span class="text-gray-400 ml-0.5" x-text="'(' + counts.race + ')'"></span>
                    </button>
                    <button @click="setTab('training')"
                            :class="tab === 'training' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                            class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors">
                        {{ __('t.training') }} <span class="text-gray-400 ml-0.5" x-text="'(' + counts.training + ')'"></span>
                    </button>
                    <button @click="setTab('all')"
                            :class="tab === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                            class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors">
                        {{ __('t.all') }} <span class="text-gray-400 ml-0.5" x-text="'(' + counts.all + ')'"></span>
                    </button>
                </div>
                {{-- Filters --}}
                <select x-model="minFlights" @change="resetPage()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20">
                    <option value="0">{{ __('t.all_flights') }}</option>
                    <option value="1">{{ __('t.min_flights', ['count' => 1]) }}</option>
                    <option value="2">{{ __('t.min_flights', ['count' => 2]) }}</option>
                    <option value="3">{{ __('t.min_flights', ['count' => 3]) }}</option>
                    <option value="5">{{ __('t.min_flights', ['count' => 5]) }}</option>
                </select>
                <select x-model="countryFilter" @change="resetPage()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20">
                    <option value="">{{ __('t.all_countries') }}</option>
                    <template x-for="c in countries" :key="c">
                        <option :value="c" x-text="c"></option>
                    </template>
                </select>
                <input type="text" x-model="search" @input="resetPage()"
                       placeholder="{{ __('t.search_ring_or_team') }}"
                       class="flex-1 min-w-[180px] bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20">
                <div class="text-gray-400 text-xs" x-show="filtered().length !== currentData().length">
                    <span x-text="filtered().length"></span> of <span x-text="currentData().length"></span>
                </div>
            </div>

            {{-- Data Table --}}
            <div x-show="!loading" x-cloak class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-left text-xs font-bold uppercase tracking-wider text-gray-400">
                            <th class="px-4 py-3 cursor-pointer select-none hover:text-gray-700" @click="sortBy('ring')">
                                {{ __('t.ring') }} <span x-show="sortCol==='ring'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                            <th class="px-3 py-3 cursor-pointer select-none hover:text-gray-700" @click="sortBy('team')">
                                {{ __('t.team') }} <span x-show="sortCol==='team'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                            <th class="px-3 py-3 text-center cursor-pointer select-none hover:text-gray-700" @click="sortBy('races')">
                                {{ __('t.flts') }} <span x-show="sortCol==='races'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                            <th class="px-3 py-3 text-center cursor-pointer select-none hover:text-gray-700" @click="sortBy('avgCoefficient')">
                                {{ __('t.rating') }} <span x-show="sortCol==='avgCoefficient'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                            <th class="px-3 py-3 text-center cursor-pointer select-none hover:text-gray-700" @click="sortBy('top5')">
                                {{ __('t.top_5') }} <span x-show="sortCol==='top5'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                            <th class="px-3 py-3 text-center cursor-pointer select-none hover:text-gray-700 hidden md:table-cell" @click="sortBy('top10')">
                                {{ __('t.top_10') }} <span x-show="sortCol==='top10'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                            <th class="px-3 py-3 text-center cursor-pointer select-none hover:text-gray-700 hidden lg:table-cell" @click="sortBy('top20')">
                                {{ __('t.top_20') }} <span x-show="sortCol==='top20'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                            <th class="px-3 py-3 text-right cursor-pointer select-none hover:text-gray-700" @click="sortBy('avgSpeedMpm')">
                                {{ __('t.avg_speed') }} <span x-show="sortCol==='avgSpeedMpm'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                            <th class="pr-4 pl-3 py-3 text-right cursor-pointer select-none hover:text-gray-700 hidden sm:table-cell" @click="sortBy('topSpeedMpm')">
                                {{ __('t.top_speed') }} <span x-show="sortCol==='topSpeedMpm'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-[10px]" style="color:var(--accent);"></span>
                            </th>
                        </tr>
                    </thead>
                    <template x-for="bird in paginated()" :key="bird.pigeonId">
                        <tbody>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors"
                                @click="toggle(bird.pigeonId)">
                                <td class="px-4 py-2.5 font-mono text-xs font-medium text-gray-700" x-text="bird.ring"></td>
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <template x-if="bird.country">
                                            <img :src="'https://flagcdn.com/20x15/' + flagCode(bird.country) + '.png'"
                                                 :alt="bird.country" class="inline-block rounded-sm flex-shrink-0" width="20" height="15" loading="lazy">
                                        </template>
                                        <span class="font-medium text-gray-900 truncate" x-text="bird.team"></span>
                                    </div>
                                </td>
                                <td class="px-3 py-2.5 text-center tabular-nums">
                                    <span class="font-semibold text-gray-900" x-text="bird.races"></span><span class="text-gray-400 text-xs" x-text="'/' + bird.totalFlights"></span>
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="font-bold px-1.5 py-0.5 rounded text-xs"
                                          :class="bird.avgCoefficient <= 20 ? 'bg-green-100 text-green-700' : bird.avgCoefficient <= 50 ? 'bg-blue-100 text-blue-700' : bird.avgCoefficient <= 80 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600'"
                                          x-text="bird.avgCoefficient < 999 ? bird.avgCoefficient.toFixed(1) + '%' : '-'"></span>
                                </td>
                                <td class="px-3 py-2.5 text-center tabular-nums font-semibold" :class="bird.top5 ? 'text-green-600' : 'text-gray-300'" x-text="bird.top5 || '-'"></td>
                                <td class="px-3 py-2.5 text-center tabular-nums font-semibold hidden md:table-cell" :class="bird.top10 ? 'text-blue-600' : 'text-gray-300'" x-text="bird.top10 || '-'"></td>
                                <td class="px-3 py-2.5 text-center tabular-nums text-gray-600 hidden lg:table-cell" :class="bird.top20 ? 'text-gray-700' : 'text-gray-300'" x-text="bird.top20 || '-'"></td>
                                <td class="px-3 py-2.5 text-right tabular-nums" style="font-family:'Space Grotesk',sans-serif;">
                                    <span class="font-semibold text-gray-900" x-text="bird.avgSpeedMpm ? bird.avgSpeedMpm.toFixed(1) : '-'"></span>
                                </td>
                                <td class="pr-4 pl-3 py-2.5 text-right tabular-nums font-semibold text-gray-900 hidden sm:table-cell" style="font-family:'Space Grotesk',sans-serif;" x-text="bird.topSpeedMpm ? bird.topSpeedMpm.toFixed(1) : '-'"></td>
                            </tr>
                            {{-- Expanded: flight history --}}
                            <tr x-show="expanded === bird.pigeonId" x-cloak class="bg-gray-50/80">
                                <td colspan="9" class="px-4 py-3">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs">
                                            <thead>
                                                <tr class="text-gray-400 text-left border-b border-gray-200 text-[10px] uppercase tracking-wider font-bold">
                                                    <th class="px-3 py-2">{{ __('t.flight') }}</th>
                                                    <th class="px-3 py-2 text-center">{{ __('t.type') }}</th>
                                                    <th class="px-3 py-2 text-right">{{ __('t.distance') }}</th>
                                                    <th class="px-3 py-2 text-center">{{ __('t.pos') }}</th>
                                                    <th class="px-3 py-2 text-center">Field</th>
                                                    <th class="px-3 py-2 text-right">M/Min</th>
                                                    <th class="px-3 py-2 text-right">km/h</th>
                                                    <th class="px-3 py-2 text-right">{{ __('t.rating') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                <template x-for="fl in bird.flights || []" :key="fl.flightId">
                                                    <tr class="hover:bg-gray-100">
                                                        <td class="px-3 py-1.5 font-medium text-gray-900" x-text="fl.flightName"></td>
                                                        <td class="px-3 py-1.5 text-center">
                                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold"
                                                                  :class="fl.flightType !== 'training' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'"
                                                                  x-text="fl.flightType === 'training' ? 'TRN' : 'RACE'"></span>
                                                        </td>
                                                        <td class="px-3 py-1.5 text-right text-gray-500 tabular-nums" x-text="fl.distance ? fl.distance + ' km' : '-'"></td>
                                                        <td class="px-3 py-1.5 text-center">
                                                            <span class="font-bold tabular-nums"
                                                                  :class="fl.position <= 3 ? 'text-yellow-600' : fl.position <= 10 ? 'text-blue-700' : 'text-gray-900'"
                                                                  x-text="fl.position || '-'"></span>
                                                        </td>
                                                        <td class="px-3 py-1.5 text-center text-gray-400 tabular-nums" x-text="fl.fieldSize || '-'"></td>
                                                        <td class="px-3 py-1.5 text-right font-semibold text-gray-900 tabular-nums" style="font-family:'Space Grotesk',sans-serif;" x-text="fl.speedMpm ? parseFloat(fl.speedMpm).toFixed(1) : '-'"></td>
                                                        <td class="px-3 py-1.5 text-right text-gray-500 tabular-nums" style="font-family:'Space Grotesk',sans-serif;" x-text="fl.speed ? parseFloat(fl.speed).toFixed(1) : '-'"></td>
                                                        <td class="px-3 py-1.5 text-right text-gray-500 tabular-nums" x-text="fl.coefficient ? parseFloat(fl.coefficient).toFixed(1) + '%' : '-'"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </table>

                {{-- Empty filter state --}}
                <div x-show="!loading && filtered().length === 0" class="p-10 text-center">
                    <p class="text-gray-400">{{ __('t.no_birds_match') }}</p>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-between mt-4" x-show="!loading && totalPages() > 1">
                <button @click="prevPage()" :disabled="page === 1"
                        :class="page === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 transition-colors">
                    &larr; {{ __('t.previous') }}
                </button>
                <span class="text-gray-500 text-sm">
                    Page <span class="text-gray-900 font-bold" x-text="page"></span> of <span class="text-gray-900 font-bold" x-text="totalPages()"></span>
                </span>
                <button @click="nextPage()" :disabled="page >= totalPages()"
                        :class="page >= totalPages() ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 transition-colors">
                    {{ __('t.next') }} &rarr;
                </button>
            </div>
        </section>
    @endif

</div>

@if($season)
<script>
function analysisApp() {
    return {
        raw: { all: [], race: [], training: [] },
        counts: { all: 0, race: 0, training: 0 },
        tab: 'race',
        page: 1,
        perPage: 50,
        sortCol: 'avgCoefficient',
        sortDir: 'asc',
        minFlights: 0,
        countryFilter: '',
        search: '',
        expanded: null,
        countries: [],
        loading: true,
        _flagMap: { 'WA': 'gb-wls', 'XS': 'gb-sct', 'EN': 'gb-eng', 'NI': 'gb-nir' },
        flagCode(c) { return this._flagMap[c] || c.toLowerCase(); },

        init() {
            fetch('{{ route("analysis.data") }}?season={{ $season->id }}')
                .then(r => { if (!r.ok) throw new Error('No data'); return r.json(); })
                .then(data => {
                    this.raw.all = data.all || [];
                    this.raw.race = data.race || [];
                    this.raw.training = data.training || [];
                    this.counts = data.flightCounts || {};
                    this.buildCountries();
                    this.loading = false;
                })
                .catch(() => { this.loading = false; });
        },

        buildCountries() {
            const set = new Set();
            this.raw.all.forEach(b => { if (b.country) set.add(b.country); });
            this.countries = [...set].sort();
        },

        topTeams() {
            const data = this.currentData();
            const teams = {};
            data.forEach(b => {
                if (!b.team || !b.avgPosition) return;
                if (!teams[b.team]) teams[b.team] = { name: b.team, country: b.country, positions: [], weights: [], speeds: [], birds: 0 };
                const weight = b.totalFlights > 0 ? b.races / b.totalFlights : 0;
                teams[b.team].positions.push(b.avgPosition);
                teams[b.team].weights.push(weight);
                if (b.avgSpeedMpm > 0) teams[b.team].speeds.push(b.avgSpeedMpm);
                teams[b.team].birds++;
            });
            return Object.values(teams)
                .filter(t => t.birds >= 3)
                .map(t => {
                    // Sort birds by weighted position (best first), take top 3
                    const sorted = t.positions.map((pos, i) => ({ pos, weight: t.weights[i], speed: t.speeds[i] || 0 }))
                        .sort((a, b) => a.pos - b.pos)
                        .slice(0, 3);
                    const totalWeight = sorted.reduce((s, b) => s + b.weight, 0);
                    const weightedPos = totalWeight > 0
                        ? sorted.reduce((s, b) => s + b.pos * b.weight, 0) / totalWeight
                        : sorted.reduce((s, b) => s + b.pos, 0) / sorted.length;
                    const speeds = sorted.filter(b => b.speed > 0);
                    return {
                        ...t,
                        avgPos: weightedPos,
                        avgSpeed: speeds.length ? speeds.reduce((s, b) => s + b.speed, 0) / speeds.length : 0,
                    };
                })
                .sort((a, b) => a.avgPos - b.avgPos)
                .slice(0, 3);
        },

        setTab(t) { this.tab = t; this.resetPage(); },
        currentData() { return this.raw[this.tab] || []; },

        filtered() {
            let data = this.currentData();
            const min = parseInt(this.minFlights) || 0;
            if (min > 0) data = data.filter(b => (b.races || 0) >= min);
            if (this.countryFilter) data = data.filter(b => b.country === this.countryFilter);
            if (this.search) {
                const q = this.search.toLowerCase();
                data = data.filter(b => (b.ring && b.ring.toLowerCase().includes(q)) || (b.team && b.team.toLowerCase().includes(q)));
            }
            return this.sorted(data);
        },

        sorted(data) {
            const col = this.sortCol, dir = this.sortDir === 'asc' ? 1 : -1;
            return [...data].sort((a, b) => {
                let av = a[col], bv = b[col];
                if (typeof av === 'string') av = av.toLowerCase();
                if (typeof bv === 'string') bv = bv.toLowerCase();
                if (av == null) return 1; if (bv == null) return -1;
                return av < bv ? -1 * dir : av > bv ? 1 * dir : 0;
            });
        },

        sortBy(col) {
            if (this.sortCol === col) { this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc'; }
            else { this.sortCol = col; this.sortDir = col === 'ring' || col === 'team' || col === 'country' ? 'asc' : 'desc'; }
            this.resetPage();
        },

        paginated() { const f = this.filtered(), s = (this.page - 1) * this.perPage; return f.slice(s, s + this.perPage); },
        totalPages() { return Math.max(1, Math.ceil(this.filtered().length / this.perPage)); },
        resetPage() { this.page = 1; this.expanded = null; },
        prevPage() { if (this.page > 1) this.page--; },
        nextPage() { if (this.page < this.totalPages()) this.page++; },
        toggle(id) { this.expanded = this.expanded === id ? null : id; }
    };
}
</script>
@endif
@endsection

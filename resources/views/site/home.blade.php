@extends('layouts.app')

@section('title', config('olr.site_name') . ' | Home')

@section('content')
    {{-- Banner --}}
    @if(config('olr.banner') && file_exists(public_path(config('olr.banner'))))
        <section class="border-b border-gray-200">
            <img src="{{ asset(config('olr.banner')) }}" alt="{{ config('olr.site_name') }}" class="w-full h-auto">
        </section>
    @endif

    {{-- Live Event Card --}}
    @include('site.partials.live-event')

    {{-- CTA Strip --}}
    @if(\App\Models\Setting::get('entries_enabled', '1') === '1')
        <section class="border-b border-gray-200 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('enter') }}" class="inline-flex items-center px-8 py-3 rounded-lg font-semibold text-white text-sm transition-colors hover:opacity-90" style="background: #c8102e;">
                        {{ __('t.enter_your_birds') }}
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Sponsor --}}
    @if(config('olr.sponsor_image') && file_exists(public_path(config('olr.sponsor_image'))))
        <div class="border-b border-gray-200 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-center gap-3">
                <span class="text-sm uppercase tracking-wider text-gray-500 font-medium">{{ __('t.sponsored_by') }}</span>
                <a href="{{ config('olr.sponsor_url', '#') }}" target="_blank" rel="noopener">
                    <img src="{{ asset(config('olr.sponsor_image')) }}" alt="{{ config('olr.sponsor_name', 'Sponsor') }}" class="h-16 object-contain">
                </a>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Quick Stats (race-season only) --}}
        @if($homepageMode === 'race-season' && ($pigeonCount || $teamCount))
            <section class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-px bg-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-white py-4 text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $pigeonCount ? number_format($pigeonCount) : '-' }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">{{ __('t.pigeons') }}</div>
                </div>
                <div class="bg-white py-4 text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $teamCount ? number_format($teamCount) : '-' }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">{{ __('t.teams') }}</div>
                </div>
                <div class="bg-white py-4 text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $latestFlights->count() }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">{{ __('t.races_completed') }}</div>
                </div>
                <div class="bg-white py-4 text-center">
                    <div class="text-2xl font-bold" style="color: var(--accent);">{{ $upcomingFlights->count() }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">{{ __('t.upcoming') }}</div>
                </div>
            </section>
        @endif

        {{-- Homepage Content (admin write-up) --}}
        @if($homepageContent)
            <section class="mt-10">
                <div class="prose prose-lg max-w-none
                            prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-gray-900
                            prose-p:text-gray-700 prose-li:text-gray-700
                            prose-a:text-blue-700 prose-a:no-underline hover:prose-a:underline
                            prose-img:rounded-lg">
                    {!! t(\App\Models\Setting::replaceShortcodes($homepageContent)) !!}
                </div>
            </section>
        @endif

        {{-- Upcoming Flights (race-season only) --}}
        @if($homepageMode === 'race-season' && $upcomingFlights->isNotEmpty())
            <section class="mt-14">
                <h2 class="text-xl font-bold text-gray-900 border-b border-gray-200 pb-3">{{ __('t.upcoming_flights') }}</h2>
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
                                <th class="pb-3 pr-4 font-semibold">{{ __('t.flight') }}</th>
                                <th class="pb-3 pr-4 font-semibold">{{ __('t.distance') }}</th>
                                <th class="pb-3 font-semibold">{{ __('t.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($upcomingFlights as $flight)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 pr-4 font-medium text-gray-900">{{ $flight->name }}</td>
                                    <td class="py-3 pr-4 text-gray-600">{{ $flight->distance ? number_format($flight->distance, 1) . ' km' : '-' }}</td>
                                    <td class="py-3 text-gray-600">{{ $flight->release_time ? $flight->release_time->format('j M Y') : __('t.tbc') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        {{-- Latest Results (race-season only) --}}
        @if($homepageMode === 'race-season' && $latestFlights->isNotEmpty())
            <section class="mt-14">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                    <h2 class="text-xl font-bold text-gray-900">{{ __('t.latest_results') }}</h2>
                    <a href="{{ route('flights.index') }}" class="text-sm font-medium hover:underline" style="color: var(--accent);">{{ __('t.view_all') }} &rarr;</a>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
                                <th class="pb-3 pr-4 font-semibold">{{ __('t.race') }}</th>
                                <th class="pb-3 pr-4 font-semibold">{{ __('t.type') }}</th>
                                <th class="pb-3 pr-4 font-semibold">{{ __('t.distance') }}</th>
                                <th class="pb-3 pr-4 font-semibold">{{ __('t.avg_speed') }}</th>
                                <th class="pb-3 pr-4 font-semibold">{{ __('t.arrival') }}</th>
                                <th class="pb-3 font-semibold">{{ __('t.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($latestFlights as $flight)
                                @php
                                    $nameLower = strtolower($flight->name);
                                    $isTraining = $flight->flight_type === 'training';
                                    if (str_contains($nameLower, 'hot spot') || str_contains($nameLower, 'hotspot')) {
                                        $badgeLabel = __('t.hot_spot');
                                    } elseif (str_contains($nameLower, 'semi')) {
                                        $badgeLabel = __('t.semi_final');
                                    } elseif (preg_match('/\bfinal\b/i', $flight->name) && !str_contains($nameLower, 'semi')) {
                                        $badgeLabel = __('t.final');
                                    } elseif ($isTraining) {
                                        $badgeLabel = __('t.training');
                                    } else {
                                        $badgeLabel = __('t.race');
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 pr-4">
                                        <a href="{{ route('flights.show', $flight) }}" class="font-medium text-gray-900 hover:underline" style="text-decoration-color: var(--accent);">{{ $flight->name }}</a>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $isTraining ? 'bg-gray-100 text-gray-600' : 'text-white' }}" @if(!$isTraining) style="background: var(--accent);" @endif>
                                            {{ $badgeLabel }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4 text-gray-600">{{ $flight->distance ? number_format($flight->distance, 1) . ' km' : '-' }}</td>
                                    <td class="py-3 pr-4 text-gray-600">{{ $flight->average_speed ? number_format($flight->average_speed, 1) . ' m/min' : '-' }}</td>
                                    <td class="py-3 pr-4 text-gray-600">{{ $flight->arrivals_count ?? 0 }} / {{ $flight->basketings_count ?? 0 }}</td>
                                    <td class="py-3 text-gray-600">{{ $flight->release_time ? $flight->release_time->format('j M Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        {{-- Livestream (only shown when active) --}}
        @if($livestream && $livestream->is_published)
            <section class="mt-14">
                <div class="border border-gray-200 rounded-lg p-5 bg-gray-50">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wider bg-red-600 text-white">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                            </span>
                            {{ __('t.live') }}
                        </span>
                        <h3 class="font-bold text-lg text-gray-900">{{ $livestream->title }}</h3>
                    </div>
                    @if($livestream->livestream_url)
                        <div class="aspect-video rounded-lg overflow-hidden bg-black">
                            <iframe src="{{ $livestream->livestream_url }}" class="w-full h-full" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                        </div>
                    @endif
                </div>
            </section>
        @endif

    </div>

    {{-- Promo Video --}}
    @if(config('olr.promo_video'))
        @php
            $videoUrl = config('olr.promo_video');
            $embedUrl = null;
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/live\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
                $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
            }
        @endphp
        @if($embedUrl)
            <section class="mt-14" style="background: var(--primary);">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16 sm:pt-16 sm:pb-20">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ __('t.welcome_to') }} {{ config('olr.site_name') }}</h2>
                        <p class="mt-2 text-white/60">Take a look at our facilities and what makes us the UK's premier one loft race</p>
                    </div>
                    <div class="aspect-video rounded-xl overflow-hidden shadow-2xl ring-1 ring-white/10">
                        <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    </div>
                </div>
            </section>
        @endif
    @endif
@endsection

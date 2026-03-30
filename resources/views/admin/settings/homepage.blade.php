@extends('layouts.admin')

@section('title', 'Homepage')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Homepage</h1>
        <p class="text-gray-500 text-sm mt-1">Control what visitors see when they first land on your site.</p>
    </div>

    {{-- Mini preview --}}
    <div class="max-w-2xl rounded-lg overflow-hidden mb-8 border border-gray-200 shadow-sm">
        <div class="px-4 py-2.5" style="background: var(--primary);">
            <span class="text-white font-extrabold text-xs">{{ $settings['site_name'] ?? config('olr.site_name') }}</span>
        </div>
        <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
            <div class="flex justify-center gap-6 text-center">
                <div>
                    <div class="text-lg font-bold text-gray-900">{{ $settings['homepage_pigeon_count'] ?? '-' }}</div>
                    <div class="text-[10px] text-gray-400 uppercase tracking-wider">Pigeons</div>
                </div>
                <div>
                    <div class="text-lg font-bold text-gray-900">{{ $settings['homepage_team_count'] ?? '-' }}</div>
                    <div class="text-[10px] text-gray-400 uppercase tracking-wider">Teams</div>
                </div>
            </div>
        </div>
        <div class="bg-white px-4 py-3">
            <div class="text-xs text-gray-400 italic">Your homepage write-up appears here...</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-2xl space-y-5">
        @csrf
        <input type="hidden" name="_redirect" value="admin.settings.homepage">

        {{-- Live Event Card info --}}
        @php
            $runningFlight = null;
            $nextFlight = null;
            $activeSeason = \App\Models\Season::where('loft_id', config('olr.loft_id'))->where('is_active', true)->latest()->first();
            if ($activeSeason) {
                $runningFlight = \App\Models\Flight::where('season_id', $activeSeason->id)->where('status', 'running')->first();
                if (!$runningFlight) {
                    $nextFlight = \App\Models\Flight::where('season_id', $activeSeason->id)->where('status', 'upcoming')->orderBy('release_time')->first();
                }
            }
        @endphp
        <div class="border border-gray-200 rounded-lg p-5 bg-white">
            <h3 class="text-sm font-bold text-gray-900 mb-1">Live Event Card</h3>
            <p class="text-xs text-gray-400 mb-3">This appears automatically on your homepage based on race data from oneloftrace.live — no setup needed.</p>
            @if($runningFlight)
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-red-50 border border-red-200">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                    <span class="text-sm font-bold text-red-700">LIVE NOW: {{ $runningFlight->name }}</span>
                </div>
            @elseif($nextFlight)
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 border border-blue-200">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-semibold text-blue-700">Next event: {{ $nextFlight->name }}</span>
                    @if($nextFlight->release_time)
                        <span class="text-xs text-blue-500">— {{ $nextFlight->release_time->format('l j M') }}</span>
                    @endif
                </div>
            @else
                <div class="px-3 py-2 rounded-lg bg-gray-50 border border-gray-200">
                    <span class="text-sm text-gray-500">No upcoming or live flights. The card will appear when the next event is synced.</span>
                </div>
            @endif
            <p class="text-xs text-gray-400 mt-2">Weather data is shown automatically when the flight name matches one of your <a href="{{ route('admin.settings.race-map') }}" class="underline" style="color:var(--accent);">Race Program</a> map points.</p>
        </div>

        <div>
            <label for="homepage_mode" class="block text-sm font-semibold text-gray-700 mb-2">Homepage Mode</label>
            <select id="homepage_mode" name="homepage_mode" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors">
                <option value="pre-season" {{ ($settings['homepage_mode'] ?? 'pre-season') === 'pre-season' ? 'selected' : '' }}>Pre-Season (promoting upcoming race)</option>
                <option value="race-season" {{ ($settings['homepage_mode'] ?? '') === 'race-season' ? 'selected' : '' }}>Race Season (showing results + flights)</option>
            </select>
            <p class="text-xs text-gray-400 mt-1">Pre-Season shows your write-up only. Race Season adds live results, stats bar and upcoming flights.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="homepage_pigeon_count" class="block text-sm font-semibold text-gray-700 mb-2">Pigeon Count</label>
                <input type="number" id="homepage_pigeon_count" name="homepage_pigeon_count" value="{{ $settings['homepage_pigeon_count'] ?? '' }}"
                       class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                       placeholder="e.g. 1650">
            </div>
            <div>
                <label for="homepage_team_count" class="block text-sm font-semibold text-gray-700 mb-2">Team Count</label>
                <input type="number" id="homepage_team_count" name="homepage_team_count" value="{{ $settings['homepage_team_count'] ?? '' }}"
                       class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                       placeholder="e.g. 284">
            </div>
        </div>
        <p class="text-xs text-gray-400 -mt-3">Shown in the stats bar on the homepage when in Race Season mode.</p>

        <div>
            <label for="homepage_content" class="block text-sm font-semibold text-gray-700 mb-2">Homepage Write-up</label>
            <textarea id="homepage_content" name="homepage_content" rows="12"
                      class="richtext w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                      placeholder="Write about this year's race...">{{ $settings['homepage_content'] ?? '' }}</textarea>
            <p class="text-xs text-gray-400 mt-1">The main content on your homepage. Tell visitors about this season.</p>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                    style="background:var(--accent);">
                Save Homepage
            </button>
        </div>
    </form>
@endsection

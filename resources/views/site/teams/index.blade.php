@extends('layouts.app')

@section('title', 'Teams')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Teams</h1>
            <p class="text-gray-500 mt-1">
                {{ $season->name }} &mdash;
                <span class="font-semibold text-gray-700">{{ $teams->flatten()->count() }}</span> teams entered
            </p>
        </div>

        {{-- Country Sections --}}
        @foreach($teams as $countryCode => $countryTeams)
            <section class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <img src="https://flagcdn.com/24x18/{{ strtolower($countryCode) }}.png"
                         alt="{{ $countryCode }}"
                         class="w-6 h-[18px] object-cover rounded-sm">
                    <h2 class="text-lg font-bold text-gray-900">{{ $countryCode }}</h2>
                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">
                        {{ $countryTeams->count() }} {{ Str::plural('team', $countryTeams->count()) }}
                    </span>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="border-b border-gray-200 text-left">
                                <th class="px-4 py-3 font-semibold text-gray-700">Team Name</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 text-right">Pigeons</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($countryTeams as $team)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer even:bg-gray-50/50" onclick="window.location='{{ route('teams.show', $team) }}'">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('teams.show', $team) }}" class="font-semibold text-gray-900 hover:text-blue-700 transition-colors">
                                            {{ $team->name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-600">{{ $team->pigeons_count }} {{ Str::plural('pigeon', $team->pigeons_count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach

    </div>
</div>
@endsection

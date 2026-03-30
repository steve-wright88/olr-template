@extends('layouts.app')

@section('title', $team->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://flagcdn.com/24x18/{{ strtolower($team->country) }}.png"
                     alt="{{ $team->country }}"
                     class="w-6 h-[18px] object-cover rounded-sm">
                <span class="text-gray-500 text-sm font-semibold uppercase tracking-wider">{{ $team->country }}</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $team->name }}</h1>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-gray-200 border border-gray-200 rounded-lg overflow-hidden mb-10">
            <div class="bg-white p-5 text-center">
                <div class="text-2xl font-bold text-blue-700">{{ $team->pigeons->count() }}</div>
                <div class="text-gray-500 text-sm font-medium mt-1">Pigeons</div>
            </div>
            <div class="bg-white p-5 text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_flights'] }}</div>
                <div class="text-gray-500 text-sm font-medium mt-1">Flights Entered</div>
            </div>
            <div class="bg-white p-5 text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['avg_position'] ? number_format($stats['avg_position'], 1) : '-' }}</div>
                <div class="text-gray-500 text-sm font-medium mt-1">Avg Position</div>
            </div>
            <div class="bg-white p-5 text-center">
                <div class="text-2xl font-bold text-blue-700">{{ $stats['best_position'] ?? '-' }}</div>
                <div class="text-gray-500 text-sm font-medium mt-1">Best Position</div>
            </div>
        </div>

        {{-- Pigeons Table --}}
        <div>
            <h2 class="font-bold text-lg text-gray-900 mb-3">Pigeons</h2>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-200 text-left">
                            <th class="px-4 py-3 font-semibold text-gray-700">Ring</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-center">Sex</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Colour</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-center">Flights</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($team->pigeons as $pigeon)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors even:bg-gray-50/50">
                                <td class="px-4 py-3">
                                    <a href="{{ route('pigeons.show', $pigeon) }}"
                                       class="font-semibold text-gray-900 hover:text-blue-700 transition-colors">
                                        {{ $pigeon->ring_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $pigeon->name ?: '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($pigeon->sex)
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $pigeon->sex === 'm' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                            {{ $pigeon->sex === 'm' ? 'M' : 'F' }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $pigeon->color ?: '-' }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-900">{{ $pigeon->results->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

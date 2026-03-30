@extends('layouts.app')

@section('title', $pigeon->ring_number)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $pigeon->ring_number }}</h1>
            @if($pigeon->name)
                <p class="text-xl text-gray-500 mt-1">{{ $pigeon->name }}</p>
            @endif
            <div class="flex items-center gap-3 mt-3 flex-wrap">
                @if($pigeon->team)
                    <a href="{{ route('teams.show', $pigeon->team) }}"
                       class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-700 transition-colors">
                        <img src="https://flagcdn.com/24x18/{{ strtolower($pigeon->team->country) }}.png"
                             alt="{{ $pigeon->team->country }}"
                             class="w-5 h-[14px] object-cover rounded-sm">
                        {{ $pigeon->team->name }}
                    </a>
                @endif
                @if($pigeon->sex)
                    <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $pigeon->sex === 'm' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                        {{ $pigeon->sex === 'm' ? __('t.male') : __('t.female') }}
                    </span>
                @endif
                @if($pigeon->color)
                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">
                        {{ $pigeon->color }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        @php
            $results = $pigeon->results->sortByDesc(fn($r) => $r->flight->date ?? '');
            $totalFlights = $results->count();
            $bestPosition = $results->where('arrival_order', '>', 0)->min('arrival_order');
            $avgSpeed = $results->where('speed', '>', 0)->avg('speed');
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-px bg-gray-200 border border-gray-200 rounded-lg overflow-hidden mb-10">
            <div class="bg-white p-5 text-center">
                <div class="text-2xl font-bold text-blue-700">{{ $totalFlights }}</div>
                <div class="text-gray-500 text-sm font-medium mt-1">{{ __('t.total_flights') }}</div>
            </div>
            <div class="bg-white p-5 text-center">
                <div class="text-2xl font-bold text-blue-700">{{ $bestPosition ?? '-' }}</div>
                <div class="text-gray-500 text-sm font-medium mt-1">{{ __('t.best_position') }}</div>
            </div>
            <div class="bg-white p-5 text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $avgSpeed ? number_format($avgSpeed, 1) : '-' }}</div>
                <div class="text-gray-500 text-sm font-medium mt-1">{{ __('t.avg_speed') }} (m/min)</div>
            </div>
        </div>

        {{-- Flight History --}}
        <div>
            <h2 class="font-bold text-lg text-gray-900 mb-3">{{ __('t.flight_history') }}</h2>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-200 text-left">
                            <th class="px-4 py-3 font-semibold text-gray-700">{{ __('t.race') }}</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-center">{{ __('t.type') }}</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">{{ __('t.date') }}</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-right">{{ __('t.distance') }}</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-center">{{ __('t.position') }}</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-right">m/min</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-right">km/h</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $result)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors even:bg-gray-50/50">
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $result->flight->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @php $type = $result->flight->type ?? 'race'; @endphp
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $type === 'training' ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $type === 'training' ? 'TRN' : 'RACE' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $result->flight->date ? \Carbon\Carbon::parse($result->flight->date)->format('j M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600">
                                    {{ $result->flight->distance ? $result->flight->distance . ' km' : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($result->arrival_order)
                                        <span class="font-bold {{ $result->arrival_order <= 3 ? 'text-yellow-600' : ($result->arrival_order <= 20 ? 'text-blue-700' : 'text-gray-900') }}">
                                            {{ $result->arrival_order }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    {{ $result->speed ? number_format($result->speed, 2) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-500">
                                    {{ $result->speed ? number_format($result->speed * 0.06, 1) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

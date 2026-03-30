@extends('layouts.app')

@section('title', __('t.find_your_bird'))

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('t.find_your_bird') }}</h1>
            <p class="text-gray-500 mt-1">{{ __('t.search_description') }}</p>
        </div>

        {{-- Search Form --}}
        <form action="{{ route('pigeons.search') }}" method="GET" class="mb-10">
            <div class="flex gap-3">
                <input type="text"
                       name="q"
                       value="{{ $query }}"
                       placeholder="{{ __('t.enter_ring_number') }}"
                       class="flex-1 bg-white border border-gray-300 rounded-lg px-5 py-3 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-colors">
                <button type="submit"
                        class="px-6 py-3 rounded-lg font-semibold text-sm text-white transition-colors hover:opacity-90"
                        style="background:#0077CC;">
                    {{ __('t.search') }}
                </button>
            </div>
        </form>

        {{-- Results --}}
        @if($query)
            @if($pigeons->count())
                <p class="text-gray-500 text-sm font-medium mb-3">
                    {{ __('t.results_found', ['count' => $pigeons->count()]) }}
                </p>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="border-b border-gray-200 text-left">
                                <th class="px-4 py-3 font-semibold text-gray-700">{{ __('t.ring_number') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-700">{{ __('t.name') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-700">{{ __('t.team') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 w-12"></th>
                                <th class="px-4 py-3 font-semibold text-gray-700 text-center">{{ __('t.flights') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pigeons as $pigeon)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer even:bg-gray-50/50" onclick="window.location='{{ route('pigeons.show', $pigeon) }}'">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('pigeons.show', $pigeon) }}" class="font-semibold text-gray-900 hover:text-blue-700 transition-colors">
                                            {{ $pigeon->ring_number }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $pigeon->name ?: '-' }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        @if($pigeon->team)
                                            {{ $pigeon->team->name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($pigeon->team && $pigeon->team->country)
                                            <img src="https://flagcdn.com/24x18/{{ strtolower($pigeon->team->country) }}.png"
                                                 alt="{{ $pigeon->team->country }}"
                                                 class="w-5 h-[14px] object-cover rounded-sm inline-block">
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center font-semibold text-gray-900">{{ $pigeon->results_count ?? $pigeon->results->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 font-semibold text-lg">{{ __('t.no_birds_found') }}</p>
                    <p class="text-gray-400 text-sm mt-1">{{ __('t.no_pigeon_matched', ['query' => $query]) }}</p>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <p class="text-gray-400">{{ __('t.enter_ring_to_find') }}</p>
            </div>
        @endif

    </div>
</div>
@endsection

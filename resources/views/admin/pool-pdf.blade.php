@extends('layouts.admin')

@section('title', 'Pool PDFs')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Pool PDFs</h1>
        <p class="text-gray-500 mt-1 text-sm">Configure and generate downloadable pool entry forms.</p>
    </div>

    {{-- Current PDFs --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-8">
        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Current PDFs</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center gap-3">
                @if($hotspotPdfExists)
                    <span class="text-green-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <span class="text-sm text-gray-700">Hot Spot Pool Sheet</span>
                    <a href="{{ asset('downloads/pool-hotspots.pdf') }}" target="_blank" class="text-sm font-medium" style="color: var(--accent);">Preview</a>
                @else
                    <span class="text-gray-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <span class="text-sm text-gray-400">Hot Spot Pool Sheet — not generated yet</span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @if($racePdfExists)
                    <span class="text-green-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <span class="text-sm text-gray-700">Race Pool Sheet</span>
                    <a href="{{ asset('downloads/pool-races.pdf') }}" target="_blank" class="text-sm font-medium" style="color: var(--accent);">Preview</a>
                @else
                    <span class="text-gray-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <span class="text-sm text-gray-400">Race Pool Sheet — not generated yet</span>
                @endif
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.pool-pdf.update') }}">
        @csrf

        {{-- Hot Spot Settings --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Hot Spot Pools</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pool Amounts (comma separated)</label>
                    <input type="text" name="pool_hotspot_amounts_raw" value="{{ implode(', ', json_decode($settings['pool_hotspot_amounts'], true) ?? []) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                           placeholder="50p, £1, £2, £3, £5, £10">
                    <p class="text-xs text-gray-400 mt-1">e.g. 50p, £1, £2, £3, £5, £10</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomination Label</label>
                    <input type="text" name="pool_hotspot_nom" value="{{ $settings['pool_hotspot_nom'] }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Footer Disclaimer</label>
                <textarea name="pool_hotspot_footer" rows="3"
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ $settings['pool_hotspot_footer'] }}</textarea>
            </div>
        </div>

        {{-- Race Settings --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Race / Final Pools</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pool Amounts (comma separated)</label>
                    <input type="text" name="pool_race_amounts_raw" value="{{ implode(', ', json_decode($settings['pool_race_amounts'], true) ?? []) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                           placeholder="£2, £3, £5, £10, £50, £100">
                    <p class="text-xs text-gray-400 mt-1">e.g. £2, £3, £5, £10, £50, £100</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomination Label</label>
                    <input type="text" name="pool_race_nom" value="{{ $settings['pool_race_nom'] }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Footer Disclaimer</label>
                <textarea name="pool_race_footer" rows="3"
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ $settings['pool_race_footer'] }}</textarea>
            </div>
        </div>

        {{-- General --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">General</h2>
            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">Blank Rows on PDF</label>
                <input type="number" name="pool_pdf_rows" value="{{ $settings['pool_pdf_rows'] }}" min="5" max="30"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" name="action" value="save"
                    class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white shadow-sm hover:opacity-90"
                    style="background: var(--accent);">
                Save Settings
            </button>
            <button type="submit" name="action" value="generate"
                    class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-green-600 shadow-sm hover:bg-green-700">
                Save & Generate Both PDFs
            </button>
        </div>
    </form>
@endsection

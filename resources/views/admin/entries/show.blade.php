@extends('layouts.admin')

@section('title', 'Entry ' . $entry->reference)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.entries.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back to Entries</a>
    </div>

    <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $entry->reference }}</h1>
            <p class="text-gray-500 mt-1">Submitted {{ $entry->created_at->format('j F Y \a\t H:i') }}</p>
        </div>

        {{-- Status actions --}}
        <div class="flex items-center gap-2">
            @if($entry->status !== 'confirmed')
                <form method="POST" action="{{ route('admin.entries.updateStatus', $entry) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors">Confirm</button>
                </form>
            @endif
            @if($entry->status !== 'rejected')
                <form method="POST" action="{{ route('admin.entries.updateStatus', $entry) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">Reject</button>
                </form>
            @endif
            @if($entry->status !== 'pending')
                <form method="POST" action="{{ route('admin.entries.updateStatus', $entry) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="pending">
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors">Reset to Pending</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Status badge --}}
    <div class="mb-6">
        @if($entry->status === 'confirmed')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">Confirmed</span>
        @elseif($entry->status === 'rejected')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">Rejected</span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-700">Pending</span>
        @endif
    </div>

    {{-- Owner details --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Owner Details</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
            <div>
                <dt class="text-xs text-gray-400">Flyer Name</dt>
                <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $entry->flyer_name }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400">Syndicate</dt>
                <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $entry->syndicate_name ?: '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400">Email</dt>
                <dd class="text-sm mt-0.5"><a href="mailto:{{ $entry->email }}" class="font-medium hover:underline" style="color: var(--accent);">{{ $entry->email }}</a></dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400">Phone</dt>
                <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $entry->phone ?: '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400">Team Name</dt>
                <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $entry->team_name ?: '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400">Season</dt>
                <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $entry->season_year }}</dd>
            </div>
        </dl>
        @if($entry->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <dt class="text-xs text-gray-400">Notes</dt>
                <dd class="text-sm text-gray-700 mt-0.5">{{ $entry->notes }}</dd>
            </div>
        @endif
    </div>

    {{-- Birds --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Birds ({{ $entry->birds->count() }})</h2>
            <span class="text-sm font-bold" style="color: var(--accent);">{{ $entry->number_of_birds }} x {{ \App\Models\Setting::get('entry_currency', '£') }}{{ \App\Models\Setting::get('entry_fee', '150') }} = {{ \App\Models\Setting::get('entry_currency', '£') }}{{ number_format($entry->number_of_birds * (int) \App\Models\Setting::get('entry_fee', '150')) }}</span>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-3 font-semibold w-12">#</th>
                    <th class="px-6 py-3 font-semibold">Ring Number</th>
                    <th class="px-6 py-3 font-semibold">Pigeon Name</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($entry->birds as $i => $bird)
                    <tr>
                        <td class="px-6 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 font-mono font-medium text-gray-900">{{ $bird->ring_number }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $bird->pigeon_name ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Delete --}}
    <div class="border-t border-gray-200 pt-6">
        <form method="POST" action="{{ route('admin.entries.destroy', $entry) }}" onsubmit="return confirm('Are you sure you want to delete this entry?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Delete this entry</button>
        </form>
    </div>
@endsection

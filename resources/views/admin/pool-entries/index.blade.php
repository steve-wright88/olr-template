@extends('layouts.admin')

@section('title', 'Pool Entries')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Pool Entries</h1>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <a href="{{ route('admin.pool-entries.index', ['year' => $year]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !$type && !$status ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All</a>
        <a href="{{ route('admin.pool-entries.index', ['year' => $year, 'type' => 'hotspot']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $type === 'hotspot' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Hot Spot</a>
        <a href="{{ route('admin.pool-entries.index', ['year' => $year, 'type' => 'race']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $type === 'race' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Race</a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('admin.pool-entries.index', ['year' => $year, 'status' => 'pending']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Pending</a>
        <a href="{{ route('admin.pool-entries.index', ['year' => $year, 'status' => 'confirmed']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $status === 'confirmed' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Confirmed</a>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-left text-xs font-bold uppercase tracking-wider text-gray-400">
                    <th class="px-5 py-3">Reference</th>
                    <th class="px-3 py-3">Type</th>
                    <th class="px-3 py-3">Syndicate</th>
                    <th class="px-3 py-3">Email</th>
                    <th class="px-3 py-3 text-center">Birds</th>
                    <th class="px-3 py-3 text-right">Total</th>
                    <th class="px-3 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($entries as $entry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.pool-entries.show', $entry) }}" class="font-semibold hover:underline" style="color: var(--accent);">{{ $entry->reference }}</a>
                        </td>
                        <td class="px-3 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $entry->pool_type === 'hotspot' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ $entry->pool_type === 'hotspot' ? 'Hot Spot' : 'Race' }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-gray-900">{{ $entry->syndicate_name }}</td>
                        <td class="px-3 py-3 text-gray-500 text-xs">{{ $entry->email }}</td>
                        <td class="px-3 py-3 text-center">{{ $entry->birds->count() }}</td>
                        <td class="px-3 py-3 text-right font-semibold">£{{ number_format($entry->grand_total, 2) }}</td>
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $entry->status === 'confirmed' ? 'bg-green-100 text-green-700' : ($entry->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ ucfirst($entry->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right text-xs text-gray-400">{{ $entry->created_at->format('j M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">No pool entries yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($entries->hasPages())
        <div class="mt-6">{{ $entries->links() }}</div>
    @endif
@endsection

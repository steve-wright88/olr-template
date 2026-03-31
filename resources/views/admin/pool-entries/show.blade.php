@extends('layouts.admin')

@section('title', 'Pool Entry ' . $poolEntry->reference)

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.pool-entries.index') }}" class="text-sm text-gray-400 hover:text-gray-700">&larr; Back to Pool Entries</a>
    </div>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $poolEntry->reference }}</h1>
            <p class="text-gray-500 mt-1 text-sm">Submitted {{ $poolEntry->created_at->format('j M Y, H:i') }}</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase
            {{ $poolEntry->status === 'confirmed' ? 'bg-green-100 text-green-700' : ($poolEntry->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
            {{ ucfirst($poolEntry->status) }}
        </span>
    </div>

    {{-- Details --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Pool Type</div>
                <div class="font-semibold text-gray-900">{{ $poolEntry->pool_type === 'hotspot' ? 'Hot Spot' : 'Race / Final' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Syndicate</div>
                <div class="font-semibold text-gray-900">{{ $poolEntry->syndicate_name }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Email</div>
                <div class="text-gray-700">{{ $poolEntry->email }}</div>
            </div>
            @if($poolEntry->phone)
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Phone</div>
                <div class="text-gray-700">{{ $poolEntry->phone }}</div>
            </div>
            @endif
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Grand Total</div>
                <div class="text-lg font-bold" style="color: var(--accent);">£{{ number_format($poolEntry->grand_total, 2) }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Season</div>
                <div class="text-gray-700">{{ $poolEntry->season_year }}</div>
            </div>
        </div>
    </div>

    {{-- Birds --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-6">
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-bold text-gray-900">Birds ({{ $poolEntry->birds->count() }})</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-left text-xs font-bold uppercase tracking-wider text-gray-400">
                    <th class="px-5 py-2">#</th>
                    <th class="px-3 py-2">Ring Number</th>
                    <th class="px-3 py-2">Stakes</th>
                    <th class="px-5 py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($poolEntry->birds as $i => $bird)
                    <tr>
                        <td class="px-5 py-2.5 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-3 py-2.5 font-mono text-xs font-medium text-gray-900">{{ $bird->ring_number }}</td>
                        <td class="px-3 py-2.5">
                            @foreach($bird->stakes ?? [] as $label => $entered)
                                @if($entered)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 mr-1">{{ $label }}</span>
                                @endif
                            @endforeach
                        </td>
                        <td class="px-5 py-2.5 text-right font-semibold">£{{ number_format($bird->bird_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap items-center gap-3">
        @if($poolEntry->status !== 'confirmed')
            <form method="POST" action="{{ route('admin.pool-entries.updateStatus', $poolEntry) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-green-600 hover:bg-green-700">Confirm</button>
            </form>
        @endif
        @if($poolEntry->status !== 'rejected')
            <form method="POST" action="{{ route('admin.pool-entries.updateStatus', $poolEntry) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700">Reject</button>
            </form>
        @endif
        @if($poolEntry->status !== 'pending')
            <form method="POST" action="{{ route('admin.pool-entries.updateStatus', $poolEntry) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="pending">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300">Reset to Pending</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.pool-entries.destroy', $poolEntry) }}" onsubmit="return confirm('Delete this pool entry?');">
            @csrf @method('DELETE')
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-red-600 border border-red-200 hover:bg-red-50">Delete</button>
        </form>
    </div>
@endsection

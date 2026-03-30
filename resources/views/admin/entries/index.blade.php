@extends('layouts.admin')

@section('title', 'Entries')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Entries</h1>
        <a href="{{ route('admin.entry-settings') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg text-white transition-colors hover:opacity-90" style="background: var(--accent);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Entry Settings
        </a>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            @if($years->isNotEmpty())
                <select name="year" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            @endif
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </form>
        <span class="text-sm text-gray-500">{{ $entries->total() }} entries</span>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3 font-semibold">Reference</th>
                        <th class="px-4 py-3 font-semibold">Flyer</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Team</th>
                        <th class="px-4 py-3 font-semibold">Birds</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($entries as $entry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs font-bold">{{ $entry->reference }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $entry->flyer_name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $entry->email }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $entry->team_name ?: '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $entry->number_of_birds }}</td>
                            <td class="px-4 py-3">
                                @if($entry->status === 'confirmed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-700">Confirmed</span>
                                @elseif($entry->status === 'rejected')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">Rejected</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $entry->created_at->format('j M Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.entries.show', $entry) }}" class="text-sm font-medium hover:underline" style="color: var(--accent);">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">No entries yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($entries->hasPages())
        <div class="mt-6">
            {{ $entries->withQueryString()->links() }}
        </div>
    @endif
@endsection

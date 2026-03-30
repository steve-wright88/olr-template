@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div>

    {{-- Sync stats flash banner --}}
    @if(session('sync_stats'))
        <div class="mb-8 rounded-lg border border-blue-200 bg-blue-50 p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </div>
                <span class="font-bold text-blue-700 text-lg uppercase tracking-wide">Sync Complete</span>
            </div>
            <div class="ml-11 text-sm text-blue-700 space-y-1">
                @php $stats = session('sync_stats'); @endphp
                @if(is_array($stats))
                    @foreach($stats as $key => $value)
                        <div><span class="font-semibold text-blue-900">{{ ucwords(str_replace('_', ' ', $key)) }}:</span> {{ $value }}</div>
                    @endforeach
                @else
                    <div>{{ $stats }}</div>
                @endif
            </div>
        </div>
    @endif

    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="mt-1 text-gray-500">Welcome back. Here is your loft at a glance.</p>
    </div>

    {{-- Quick stats row --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        {{-- Season --}}
        <div class="col-span-2 lg:col-span-1 border border-gray-200 bg-white rounded-lg p-4">
            <div class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Season</div>
            <select onchange="window.location.href='{{ route('admin.dashboard') }}?season=' + this.value"
                    class="w-full text-sm font-bold text-gray-900 bg-transparent border-0 p-0 focus:outline-none focus:ring-0 cursor-pointer">
                @foreach($seasons as $s)
                    <option value="{{ $s->id }}" {{ $season?->id === $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Total pigeons --}}
        <div class="border border-gray-200 bg-white rounded-lg p-4">
            <div class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Pigeons</div>
            <div class="text-2xl font-bold" style="color:var(--accent);">{{ number_format($season?->pigeon_count ?? 0) }}</div>
        </div>

        {{-- Total teams --}}
        <div class="border border-gray-200 bg-white rounded-lg p-4">
            <div class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Teams</div>
            <div class="text-2xl font-bold" style="color:var(--accent);">{{ number_format($season?->team_count ?? 0) }}</div>
        </div>

        {{-- Total flights --}}
        <div class="border border-gray-200 bg-white rounded-lg p-4">
            <div class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Flights</div>
            <div class="text-2xl font-bold" style="color:var(--accent);">{{ $season?->flights()->count() ?? 0 }}</div>
        </div>

        {{-- Last sync --}}
        <div class="border border-gray-200 bg-white rounded-lg p-4">
            <div class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Last Sync</div>
            <div class="text-sm font-semibold text-gray-900">
                @if($loft?->synced_at)
                    <span title="{{ $loft->synced_at->format('d M Y H:i') }}">{{ $loft->synced_at->diffForHumans() }}</span>
                @else
                    <span class="text-gray-400">Never</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="mb-10">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('admin.sync') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90 active:scale-95" style="background:var(--accent);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.992 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    Refresh Race Data
                </button>
            </form>

            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Post Update
            </a>
        </div>
    </div>

    {{-- Recent activity --}}
    <div>
        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Recent Posts</h2>
        @if($recentPosts->count())
            <div class="border border-gray-200 bg-white rounded-lg divide-y divide-gray-100">
                @foreach($recentPosts as $post)
                    <a href="{{ route('news.show', $post->slug) }}" class="flex items-center justify-between gap-4 px-5 py-3.5 hover:bg-gray-50 transition-colors group">
                        <div class="min-w-0">
                            <div class="font-medium text-gray-900 truncate">{{ $post->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ $post->published_at?->format('d M Y') }}
                                @if($post->is_pinned)
                                    <span class="ml-2 inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-white" style="background:var(--accent);">Pinned</span>
                                @endif
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                @endforeach
            </div>
        @else
            <div class="border border-gray-200 bg-white rounded-lg px-5 py-8 text-center text-gray-400">
                No posts yet. Use the "Post Update" button above to get started.
            </div>
        @endif
    </div>

</div>
@endsection

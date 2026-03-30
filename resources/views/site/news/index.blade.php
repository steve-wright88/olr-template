@extends('layouts.app')

@section('title', __('t.news_updates'))

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('t.news_updates') }}</h1>
        </div>

        {{-- Posts List --}}
        @if($posts->count())
            <div class="divide-y divide-gray-200 border-t border-gray-200">
                @foreach($posts as $post)
                    <a href="{{ route('news.show', $post->slug) }}" class="block py-5 group hover:bg-gray-50 -mx-4 px-4 transition-colors">
                        <div class="flex items-center gap-3 mb-1">
                            @if($post->post_type === 'livestream')
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.12V15.88a1.5 1.5 0 002.3 1.279l9.344-5.88a1.5 1.5 0 000-2.558L6.3 2.84z"/></svg>
                                    Livestream
                                </span>
                            @elseif($post->post_type === 'update')
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700">Update</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">News</span>
                            @endif
                            <span class="text-gray-400 text-sm">{{ $post->published_at?->format('j M Y') }}</span>
                        </div>
                        <h2 class="font-bold text-lg text-gray-900 group-hover:text-blue-700 transition-colors leading-tight">
                            {{ t($post->title) }}
                        </h2>
                        <p class="text-gray-500 text-sm mt-1 line-clamp-2">
                            {{ t($post->excerpt ?: Str::limit(strip_tags($post->body), 120)) }}
                        </p>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-400">No posts yet. Check back soon.</p>
            </div>
        @endif

    </div>
</div>
@endsection

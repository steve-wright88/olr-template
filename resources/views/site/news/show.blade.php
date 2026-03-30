@extends('layouts.app')

@section('title', t($post->title))

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                @if($post->post_type === 'livestream')
                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.12V15.88a1.5 1.5 0 002.3 1.279l9.344-5.88a1.5 1.5 0 000-2.558L6.3 2.84z"/></svg>
                        Livestream
                    </span>
                @elseif($post->post_type === 'update')
                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700">Update</span>
                @else
                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">{{ __('t.news') }}</span>
                @endif
                <span class="text-gray-400 text-sm">{{ $post->published_at?->format('j M Y') }}</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 leading-tight">{{ t($post->title) }}</h1>
        </div>

        {{-- Embed --}}
        @if($embedHtml)
            <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden">
                <div class="relative w-full" style="padding-bottom:56.25%;">
                    <div class="absolute inset-0 [&>iframe]:w-full [&>iframe]:h-full">
                        {!! $embedHtml !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Body --}}
        <div class="prose prose-lg max-w-none
                    prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-gray-900
                    prose-p:text-gray-700 prose-li:text-gray-700
                    prose-a:text-blue-700 prose-a:no-underline hover:prose-a:underline
                    prose-img:rounded-lg">
            {!! t(\App\Models\Setting::replaceShortcodes($post->body)) !!}
        </div>

        {{-- Back Link --}}
        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="{{ route('news.index') }}"
               class="text-sm font-semibold text-gray-500 hover:text-blue-700 transition-colors">
                &larr; Back to News
            </a>
        </div>

    </div>
</div>
@endsection

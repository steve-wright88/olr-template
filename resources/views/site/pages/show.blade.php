@extends('layouts.app')

@section('title', t($page->title))

@section('content')
<div class="py-12 mb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-8">{{ t($page->title) }}</h1>

        {{-- Race Program Map --}}
        @if($page->slug === 'race-program' || $page->slug === 'race-programme')
            @include('site.pages.partials.race-map')
        @endif

        <div class="prose prose-lg max-w-none
                    prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-gray-900
                    prose-p:text-gray-700 prose-li:text-gray-700
                    prose-a:text-blue-700 prose-a:no-underline hover:prose-a:underline
                    prose-img:rounded-lg
                    prose-ol:list-decimal prose-ol:pl-6 prose-li:pl-1">
            {!! t(\App\Models\Setting::replaceShortcodes($page->body)) !!}
        </div>

        {{-- Gallery --}}
        @if($page->slug === 'gallery')
            @include('site.pages.partials.gallery')
        @endif

        {{-- Agents --}}
        @if($page->slug === 'agents')
            @include('site.pages.partials.agents')
        @endif

        {{-- Prize Money --}}
        @if($page->slug === 'prize-money')
            @include('site.pages.partials.prize-money')
        @endif

        {{-- Contact Form --}}
        @if($page->slug === 'contact')
            @include('site.pages.partials.contact-form')
        @endif

        {{-- Sponsor --}}
        @if(config('olr.sponsor_image') && file_exists(public_path(config('olr.sponsor_image'))))
            <div class="mt-12 pt-8 border-t border-gray-200 flex items-center justify-center gap-4">
                <span class="text-sm uppercase tracking-wider text-gray-500 font-medium">{{ __('t.sponsored_by') }}</span>
                <a href="{{ config('olr.sponsor_url', '#') }}" target="_blank" rel="noopener">
                    <img src="{{ asset(config('olr.sponsor_image')) }}" alt="{{ config('olr.sponsor_name', 'Sponsor') }}" class="h-20 object-contain">
                </a>
            </div>
        @endif

    </div>
</div>
@endsection

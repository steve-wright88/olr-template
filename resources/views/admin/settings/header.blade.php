@extends('layouts.admin')

@section('title', 'Header & Navigation')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Header & Navigation</h1>
        <p class="text-gray-500 text-sm mt-1">The top bar on every page - your race name, brand colour and navigation options.</p>
    </div>

    {{-- Mini preview --}}
    <div class="max-w-2xl rounded-lg overflow-hidden mb-8 border border-gray-200 shadow-sm">
        <div class="px-4 py-3 flex items-center justify-between" style="background: var(--primary);">
            <span class="text-white font-extrabold text-sm tracking-tight">{{ $settings['site_name'] ?? config('olr.site_name') }}</span>
            <div class="flex gap-2">
                <span class="px-2 py-1 rounded text-xs text-white/60 bg-white/10">Home</span>
                <span class="px-2 py-1 rounded text-xs text-white/60">Enter Your Birds</span>
                <span class="px-2 py-1 rounded text-xs text-white/60">News</span>
                <span class="px-2 py-1 rounded text-xs text-white/60">Results</span>
                <span class="px-2 py-1 rounded text-xs text-white/60">More</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-2xl space-y-5">
        @csrf
        <input type="hidden" name="_redirect" value="admin.settings.header">

        <div>
            <label for="site_name" class="block text-sm font-semibold text-gray-700 mb-2">Site Name</label>
            <input type="text" id="site_name" name="site_name" value="{{ $settings['site_name'] ?? config('olr.site_name') }}"
                   class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors">
            <p class="text-xs text-gray-400 mt-1">Shown in the header, footer, and browser tab on every page.</p>
        </div>

        <div>
            <label for="tagline" class="block text-sm font-semibold text-gray-700 mb-2">Tagline</label>
            <input type="text" id="tagline" name="tagline" value="{{ $settings['tagline'] ?? config('olr.tagline') }}"
                   class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors">
            <p class="text-xs text-gray-400 mt-1">Shown below your site name in the footer and in browser tab titles.</p>
        </div>

        <div>
            <label for="season_year" class="block text-sm font-semibold text-gray-700 mb-2">Season Year</label>
            <input type="text" id="season_year" name="season_year" value="{{ $settings['season_year'] ?? date('Y') }}"
                   class="w-32 bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                   placeholder="e.g. 2026">
            <p class="text-xs text-gray-400 mt-1">The current season year. Use <code class="bg-gray-100 px-1 rounded text-gray-600">@{{year}}</code> anywhere in your page or post content and it will automatically show this year.</p>
        </div>

        <div>
            <label for="accent_color" class="block text-sm font-semibold text-gray-700 mb-2">Accent Colour</label>
            <div class="flex items-center gap-3">
                <input type="color" id="accent_color" name="accent_color" value="{{ $settings['accent_color'] ?? config('olr.accent_color') }}"
                       class="w-12 h-12 rounded-lg border border-gray-300 bg-white cursor-pointer">
                <input type="text" value="{{ $settings['accent_color'] ?? config('olr.accent_color') }}"
                       class="w-32 bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC]"
                       oninput="document.getElementById('accent_color').value = this.value"
                       id="accent_color_text">
            </div>
            <p class="text-xs text-gray-400 mt-1">Used for buttons, links, badges and highlights across the whole site.</p>
        </div>

        <div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="entries_enabled" value="0">
                <input type="checkbox" name="entries_enabled" value="1" {{ ($settings['entries_enabled'] ?? '1') === '1' ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-gray-300 text-[#0077CC] focus:ring-[#0077CC]/20">
                <span class="text-sm font-semibold text-gray-700">Show "Enter Your Birds" in the navigation</span>
            </label>
            <p class="text-xs text-gray-400 mt-1 ml-8">When off, the Enter Your Birds page, nav link, and homepage button are all hidden.</p>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                    style="background:var(--accent);">
                Save Header
            </button>
        </div>
    </form>
@endsection

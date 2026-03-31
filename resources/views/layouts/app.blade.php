<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('olr.site_name')) | {{ config('olr.tagline') }}</title>
    <meta name="description" content="@yield('description', config('olr.site_name') . ' | ' . config('olr.tagline'))">
    <link rel="icon" type="image/jpeg" href="{{ asset(config('olr.logo', '/images/logo.jpg')) }}">
    <link rel="apple-touch-icon" href="{{ asset(config('olr.logo', '/images/logo.jpg')) }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=roboto:400,500,600,700,800,900" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: {{ config('olr.primary_color', '#1a2332') }};
            --accent: {{ config('olr.accent_color', '#0077CC') }};
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased" style="font-family:'Roboto',sans-serif;" x-data="{ mobileNav: false }">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 shadow-sm" style="background: var(--primary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Site name --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    @if(file_exists(public_path(config('olr.logo'))))
                        <img src="{{ asset(config('olr.logo')) }}" alt="{{ config('olr.site_name') }}" class="h-10 w-10 rounded-full object-cover">
                    @endif
                    <span class="text-xl font-extrabold text-white tracking-tight">{{ config('olr.site_name') }}</span>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden lg:flex items-center gap-0.5">
                    @php
                        $navPages = \App\Models\Page::published()->get();
                        $keyPages = ['enter', 'enter-your-birds', 'prize-money', 'race-program', 'race-programme', 'developer'];
                        $featuredPages = $navPages->filter(fn($p) => in_array($p->slug, $keyPages) && !in_array($p->slug, ['enter', 'enter-your-birds']));
                        $otherPages = $navPages->reject(fn($p) => in_array($p->slug, $keyPages));
                        $entriesEnabled = \App\Models\Setting::get('entries_enabled', '1') === '1';
                    @endphp

                    <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-medium rounded {{ request()->routeIs('home') ? 'text-white bg-white/20' : 'text-white/80 hover:text-white hover:bg-white/10' }} transition-colors">{{ __('t.home') }}</a>

                    <a href="{{ route('flights.index') }}" class="px-3 py-2 text-sm font-medium rounded {{ request()->routeIs('flights.*') ? 'text-white bg-white/20' : 'text-white/80 hover:text-white hover:bg-white/10' }} transition-colors">{{ __('t.results') }}</a>

                    @if($entriesEnabled)
                        <a href="{{ route('enter') }}" class="px-3 py-2 text-sm font-medium rounded {{ request()->routeIs('enter*') ? 'text-white bg-white/20' : 'text-white/80 hover:text-white hover:bg-white/10' }} transition-colors">{{ __('t.enter_your_birds') }}</a>
                    @endif

                    @foreach($featuredPages as $fp)
                        <a href="{{ route('pages.show', $fp->slug) }}" class="px-3 py-2 text-sm font-medium rounded {{ request()->is('page/'.$fp->slug) ? 'text-white bg-white/20' : 'text-white/80 hover:text-white hover:bg-white/10' }} transition-colors">{{ t($fp->title) }}</a>
                    @endforeach

                    <a href="{{ route('news.index') }}" class="px-3 py-2 text-sm font-medium rounded {{ request()->routeIs('news.*') ? 'text-white bg-white/20' : 'text-white/80 hover:text-white hover:bg-white/10' }} transition-colors">{{ __('t.news') }}</a>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                            {{ __('t.more') }}
                            <svg class="w-4 h-4 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-50">
                            @foreach($otherPages as $p)
                                <a href="{{ route('pages.show', $p->slug) }}" class="block px-4 py-2 text-sm {{ request()->is('page/'.$p->slug) ? 'text-gray-900 bg-gray-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">{{ t($p->title) }}</a>
                            @endforeach
                            <div class="border-t border-gray-100 my-1"></div>
                            @auth
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50">{{ __('t.admin') }}</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50">{{ __('t.logout') }}</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50">{{ __('t.login') }}</a>
                            @endauth
                        </div>
                    </div>

                    {{-- Facebook --}}
                    @if(config('olr.social.facebook'))
                        <a href="{{ config('olr.social.facebook') }}" target="_blank" rel="noopener" class="flex items-center px-2 py-2 text-white/60 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif

                    {{-- Language switcher --}}
                    @if(count(config('olr.locales', [])) > 1)
                        <div class="relative ml-1" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-1 px-2 py-2 text-sm font-medium rounded text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                <span class="uppercase">{{ app()->getLocale() }}</span>
                            </button>
                            <div x-show="open" x-transition x-cloak class="absolute right-0 mt-1 w-40 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-50">
                                @foreach(config('olr.locales', []) as $code => $label)
                                    <a href="{{ route('lang.switch', $code) }}" class="block px-4 py-2 text-sm {{ app()->getLocale() === $code ? 'text-gray-900 bg-gray-100 font-medium' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">{{ $label }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Mobile menu button --}}
                <button @click="mobileNav = !mobileNav" class="lg:hidden p-2 text-white/80 hover:text-white">
                    <svg x-show="!mobileNav" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileNav" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile nav --}}
        <div x-show="mobileNav" x-transition x-cloak class="lg:hidden border-t border-white/10" style="background: var(--primary);">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('home') }}" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ __('t.home') }}</a>
                <a href="{{ route('flights.index') }}" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ __('t.results') }}</a>
                @if($entriesEnabled)
                    <a href="{{ route('enter') }}" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ __('t.enter_your_birds') }}</a>
                @endif
                @foreach($featuredPages as $fp)
                    <a href="{{ route('pages.show', $fp->slug) }}" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ t($fp->title) }}</a>
                @endforeach
                <a href="{{ route('news.index') }}" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ __('t.news') }}</a>
                @foreach($otherPages as $p)
                    <a href="{{ route('pages.show', $p->slug) }}" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ t($p->title) }}</a>
                @endforeach
                <div class="border-t border-white/10 mt-2 pt-2">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ __('t.admin') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ __('t.logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block py-2 text-sm font-medium text-white/80 hover:text-white">{{ __('t.login') }}</a>
                    @endauth
                </div>

                {{-- Mobile language switcher --}}
                @if(count(config('olr.locales', [])) > 1)
                    <div class="border-t border-white/10 mt-2 pt-2 flex flex-wrap gap-2">
                        @foreach(config('olr.locales', []) as $code => $label)
                            <a href="{{ route('lang.switch', $code) }}" class="px-2 py-1 text-xs rounded {{ app()->getLocale() === $code ? 'bg-white/20 text-white font-medium' : 'text-white/50 hover:text-white' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- Live Banner --}}
    @php $activeLive = \App\Models\Post::where('post_type', 'livestream')->where('is_pinned', true)->where('is_published', true)->latest()->first(); @endphp
    @if($activeLive)
        <div class="relative overflow-hidden text-white" style="background: var(--accent);">
            <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-center gap-3">
                <span class="flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                    </span>
                    <span class="font-bold text-sm uppercase tracking-wider">{{ __('t.live_now') }}</span>
                </span>
                <a href="{{ route('news.show', $activeLive->slug) }}" class="text-sm font-medium underline underline-offset-2 hover:no-underline">{{ $activeLive->title }} &rarr;</a>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-white" style="background: var(--primary);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="font-bold text-lg tracking-wide">{{ config('olr.site_name') }}</div>
                    <div class="text-sm text-white/60 mt-1">{{ config('olr.tagline') }}</div>
                    @if(config('olr.address'))
                        <p class="text-sm text-white/50 mt-4">{{ config('olr.address') }}</p>
                    @endif
                </div>
                <div>
                    <div class="font-semibold text-sm uppercase tracking-wider text-white/60 mb-3">{{ __('t.contact') }}</div>
                    @if(config('olr.contact_email'))
                        <a href="mailto:{{ config('olr.contact_email') }}" class="block text-sm text-white/50 hover:text-white transition-colors">{{ config('olr.contact_email') }}</a>
                    @endif
                    @if(config('olr.contact_phone'))
                        <a href="tel:{{ config('olr.contact_phone') }}" class="block text-sm text-white/50 hover:text-white transition-colors mt-1">{{ config('olr.contact_phone') }}</a>
                    @endif
                    @if(config('olr.social.facebook'))
                        <a href="{{ config('olr.social.facebook') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm text-white/50 hover:text-white transition-colors mt-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                    @endif
                </div>
            </div>
            @if(config('olr.sponsor_image') && file_exists(public_path(config('olr.sponsor_image'))))
                <div class="border-t border-white/10 mt-8 pt-6 flex items-center justify-center gap-4">
                    <span class="text-sm uppercase tracking-wider text-white/50 font-medium">{{ __('t.sponsored_by') }}</span>
                    <a href="{{ config('olr.sponsor_url', '#') }}" target="_blank" rel="noopener">
                        <img src="{{ asset(config('olr.sponsor_image')) }}" alt="{{ config('olr.sponsor_name', 'Sponsor') }}" class="h-12 object-contain opacity-80 hover:opacity-100 transition-opacity">
                    </a>
                </div>
            @endif
            <div class="border-t border-white/10 mt-6 pt-6 text-center text-xs text-white/40">
                &copy; {{ date('Y') }} {{ config('olr.site_name') }}. {{ __('t.data_powered_by') }} <a href="https://oneloftrace.live" target="_blank" class="underline hover:text-white/60">oneloftrace.live</a>
                <span class="mx-1">&middot;</span>
                Built by <a href="{{ route('pages.show', 'developer') }}" class="underline hover:text-white/60">Ste Wright</a>
            </div>
        </div>
    </footer>
</body>
</html>

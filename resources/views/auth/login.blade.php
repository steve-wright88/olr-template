<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | {{ config('olr.site_name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: {{ config('olr.primary_color', '#1a2332') }};
            --accent: {{ config('olr.accent_color', '#0077CC') }};
        }
    </style>
</head>
<body class="bg-gray-50 antialiased" style="font-family:'Inter',sans-serif;">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-sm">
            {{-- Branding --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    @if(file_exists(public_path(config('olr.logo'))))
                        <img src="{{ asset(config('olr.logo')) }}" alt="{{ config('olr.site_name') }}" class="w-16 h-16 rounded-full object-cover mx-auto mb-3">
                    @endif
                    <span class="text-2xl font-extrabold tracking-tight text-gray-900">{{ config('olr.site_name') }}</span>
                </a>
                <p class="text-sm text-gray-500 mt-1">Admin Login</p>
            </div>

            {{-- Session Status --}}
            @if(session('status'))
                <div class="mb-4 p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-colors"
                           style="focus:ring-color: var(--accent);">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-colors">
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                        style="background: var(--accent);">
                    Log In
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-6">
                <a href="{{ route('home') }}" class="hover:text-gray-600 transition-colors">&larr; Back to site</a>
            </p>
        </div>
    </div>
</body>
</html>

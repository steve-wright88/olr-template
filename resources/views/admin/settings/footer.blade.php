@extends('layouts.admin')

@section('title', 'Footer')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Footer</h1>
        <p class="text-gray-500 text-sm mt-1">Contact details and social links shown at the bottom of every page.</p>
    </div>

    {{-- Mini preview --}}
    <div class="max-w-2xl rounded-lg overflow-hidden mb-8 border border-gray-200 shadow-sm">
        <div class="px-4 py-3" style="background: var(--primary);">
            <div class="flex justify-between text-white/60 text-[10px]">
                <div>
                    <div class="font-bold text-white text-xs">{{ $settings['site_name'] ?? config('olr.site_name') }}</div>
                    <div>{{ $settings['tagline'] ?? config('olr.tagline') }}</div>
                    @if($settings['address'] ?? config('olr.address'))
                        <div class="mt-1 text-white/40">{{ Str::limit($settings['address'] ?? config('olr.address'), 50) }}</div>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-white/50 uppercase tracking-wider font-semibold mb-1">Contact</div>
                    <div>{{ $settings['contact_email'] ?? config('olr.contact_email') }}</div>
                    <div>{{ $settings['contact_phone'] ?? config('olr.contact_phone') }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-2xl space-y-5">
        @csrf
        <input type="hidden" name="_redirect" value="admin.settings.footer">

        <div>
            <label for="contact_email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" id="contact_email" name="contact_email" value="{{ $settings['contact_email'] ?? config('olr.contact_email') }}"
                   class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors">
        </div>

        <div>
            <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
            <input type="text" id="contact_phone" name="contact_phone" value="{{ $settings['contact_phone'] ?? config('olr.contact_phone') }}"
                   class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors">
        </div>

        <div>
            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
            <textarea id="address" name="address" rows="2"
                      class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors resize-none">{{ $settings['address'] ?? config('olr.address') }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Shown in the footer and on your Contact page.</p>
        </div>

        <div class="border-t border-gray-200 pt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-4">Social Media Links</label>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <input type="url" id="facebook" name="facebook" value="{{ $settings['facebook'] ?? config('olr.social.facebook') }}"
                           class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors text-sm"
                           placeholder="https://facebook.com/...">
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    <input type="url" id="youtube" name="youtube" value="{{ $settings['youtube'] ?? config('olr.social.youtube') }}"
                           class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors text-sm"
                           placeholder="https://youtube.com/...">
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
                    <input type="url" id="instagram" name="instagram" value="{{ $settings['instagram'] ?? config('olr.social.instagram') }}"
                           class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors text-sm"
                           placeholder="https://instagram.com/...">
                </div>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                    style="background:var(--accent);">
                Save Footer
            </button>
        </div>
    </form>
@endsection

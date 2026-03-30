<div class="mt-10 pt-8 border-t border-gray-200">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- Contact Form --}}
        <div class="lg:col-span-2">
            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('t.send_message') }}</h3>

            @if(session('contact_sent'))
                <div class="bg-green-50 border border-green-200 rounded-lg px-5 py-4 mb-6">
                    <p class="text-green-700 font-semibold">{{ __('t.message_sent') }}</p>
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.name') }}</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]"
                               placeholder="{{ __('t.your_name') }}">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.email') }}</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]"
                               placeholder="{{ __('t.your_email') }}">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.subject') }}</label>
                    <input type="text" name="subject" id="subject" required value="{{ old('subject') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]"
                           placeholder="{{ __('t.whats_this_about') }}">
                    @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.message') }}</label>
                    <textarea name="message" id="message" rows="5" required
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] resize-y"
                              placeholder="{{ __('t.your_message') }}">{{ old('message') }}</textarea>
                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-bold text-white transition-all hover:shadow-lg hover:scale-[1.02]"
                        style="background: var(--accent);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('t.send') }}
                </button>
            </form>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Facebook --}}
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3">{{ __('t.find_us_facebook') }}</h4>
                <a href="https://www.facebook.com/garytomlins666" target="_blank" rel="noopener"
                   class="flex items-center gap-3 px-4 py-3 bg-[#1877F2] rounded-lg text-white font-semibold text-sm hover:bg-[#166FE5] transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Follow Who Dares Wins
                </a>
                <p class="text-xs text-gray-500 mt-2">Live race day updates, training reports & results</p>
            </div>

            {{-- Quick Contact --}}
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3">{{ __('t.quick_contact') }}</h4>
                <div class="space-y-3">
                    @if($contactPhone = \App\Models\Setting::get('contact_phone'))
                        <a href="tel:{{ $contactPhone }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $contactPhone }}
                        </a>
                    @endif
                    @if($contactEmail = \App\Models\Setting::get('contact_email'))
                        <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $contactEmail }}
                        </a>
                    @endif
                    @if($address = \App\Models\Setting::get('address'))
                        <div class="flex items-start gap-3 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{!! nl2br(e($address)) !!}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $agents = \App\Models\Agent::active()->ordered()->get();
@endphp

@if($agents->isNotEmpty())
    <div class="mt-10 space-y-4">
        @foreach($agents as $agent)
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 flex items-center gap-5">
                @if($agent->photo)
                    <img src="{{ asset('storage/' . $agent->photo) }}" alt="{{ $agent->name }}"
                         class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover flex-shrink-0">
                @else
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                @endif
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider mb-0.5" style="color: var(--accent);">
                        {{ $agent->country }}{{ $agent->region ? ' / ' . $agent->region : '' }}
                    </p>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $agent->name }}</h3>
                    <div class="space-y-0.5 text-sm text-gray-600">
                        @if($agent->email)
                            <div><a href="mailto:{{ $agent->email }}" class="hover:text-gray-900 transition-colors">{{ $agent->email }}</a></div>
                        @endif
                        @if($agent->phone)
                            <div><a href="tel:{{ preg_replace('/\s+/', '', $agent->phone) }}" class="hover:text-gray-900 transition-colors">{{ $agent->phone }}</a></div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<div class="mt-8 rounded-xl px-6 py-8 sm:px-8 text-center text-white" style="background: var(--accent);">
    <h3 class="text-xl sm:text-2xl font-black mb-2">{{ __('t.want_to_be_agent') }}</h3>
    <p class="opacity-90 mb-5 max-w-lg mx-auto text-sm sm:text-base">{{ __('t.agent_cta') }}</p>
    <a href="{{ route('pages.show', 'contact') }}"
       class="inline-block bg-white font-bold px-6 py-3 rounded-lg text-sm hover:shadow-lg transition-all"
       style="color: var(--accent);">
        {{ __('t.contact_us') }}
    </a>
</div>

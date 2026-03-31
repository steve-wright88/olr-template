@extends('layouts.app')

@section('title', 'Enter Your Birds | ' . config('olr.site_name'))

@section('content')
    {{-- Hero with banner --}}
    <section class="border-b border-gray-200">
        @if(config('olr.banner') && file_exists(public_path(config('olr.banner'))))
            <img src="{{ asset(config('olr.banner')) }}" alt="{{ config('olr.site_name') }}" class="w-full h-auto">
        @endif
    </section>

    {{-- CTAs --}}
    <section class="border-b border-gray-200 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @if(file_exists(public_path('downloads/entry-form.pdf')))
                    <a href="{{ asset('downloads/entry-form.pdf') }}" target="_blank" class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-sm font-semibold text-white bg-green-600 hover:bg-green-700 text-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download Entry Form (PDF)
                    </a>
                @endif
                <a href="{{ route('pools.create') }}" class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-200 text-center hover:border-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Enter Pools
                </a>
            </div>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-8 p-5 rounded-lg bg-green-50 border border-green-200">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h3 class="font-bold text-green-800">{{ __('t.entry_submitted') }}</h3>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- Entry info --}}
        @if($settings['notes'])
            <div class="prose prose-sm max-w-none mb-10">
                {!! nl2br(e(t($settings['notes']))) !!}
            </div>
        @endif

        <div class="mb-8 flex flex-wrap gap-2">
            @if($settings['deadline'])
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background: var(--accent);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('t.entry_deadline') }}: {{ \Carbon\Carbon::parse($settings['deadline'])->format('j F Y') }}
                </div>
            @endif

            @if(($acceptanceDates['show_on_site'] ?? '1') === '1' && $acceptanceDates['start'] && $acceptanceDates['end'])
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white/90" style="background: var(--accent); opacity: 0.85;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Birds accepted: {{ \Carbon\Carbon::parse($acceptanceDates['start'])->format('j F Y') }} - {{ \Carbon\Carbon::parse($acceptanceDates['end'])->format('j F Y') }}
                </div>
            @endif
        </div>

        {{-- Entry Form --}}
        @if($entriesOpen)
            <form id="entry-form" method="POST" action="{{ route('enter.store') }}" x-data="{
                defaultMaxBirds: {{ $settings['max_birds'] }},
                maxBirds: {{ $settings['max_birds'] }},
                perBirdFee: {{ $settings['fee'] }},
                birds: [{ ring_number: '', pigeon_name: '' }],
                offers: @json($offers),
                selectedOffer: null,
                get activeOffer() {
                    if (!this.selectedOffer) return null;
                    return this.offers.find(o => o.id == this.selectedOffer);
                },
                get totalFee() {
                    if (this.activeOffer) {
                        return parseFloat(this.activeOffer.price);
                    }
                    return this.birds.length * this.perBirdFee;
                },
                selectOffer(id) {
                    if (this.selectedOffer == id) {
                        this.selectedOffer = null;
                        this.maxBirds = this.defaultMaxBirds;
                    } else {
                        this.selectedOffer = id;
                        let offer = this.offers.find(o => o.id == id);
                        if (offer) {
                            this.maxBirds = offer.number_of_birds + offer.bonus_birds;
                        }
                    }
                    // Trim birds if over new max
                    while (this.birds.length > this.maxBirds) {
                        this.birds.pop();
                    }
                },
                addBird() {
                    if (this.birds.length < this.maxBirds) {
                        this.birds.push({ ring_number: '', pigeon_name: '' });
                    }
                },
                removeBird(index) {
                    if (this.birds.length > 1) {
                        this.birds.splice(index, 1);
                    }
                }
            }">
                @csrf

                <h2 class="text-xl font-bold text-gray-900 border-b border-gray-200 pb-3 mb-6">{{ __('t.online_entry_form') }}</h2>

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                        <h3 class="font-semibold text-red-800 text-sm">{{ __('t.fix_errors') }}</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Offers --}}
                @if($offers->isNotEmpty())
                    <input type="hidden" name="offer_id" :value="selectedOffer">
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Choose a Package</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="offer in offers" :key="offer.id">
                                <button type="button"
                                        @click="selectOffer(offer.id)"
                                        :class="selectedOffer == offer.id ? 'border-2 ring-2 ring-offset-1' : 'border border-gray-200 hover:border-gray-300'"
                                        :style="selectedOffer == offer.id ? 'border-color: var(--accent); --tw-ring-color: var(--accent);' : ''"
                                        class="relative p-4 rounded-lg bg-white text-left transition-all">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="font-semibold text-gray-900" x-text="offer.name"></span>
                                            <div class="text-sm text-gray-500 mt-1">
                                                <span x-text="offer.number_of_birds"></span> bird(s)
                                                <template x-if="offer.bonus_birds > 0">
                                                    <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700" x-text="'+ ' + offer.bonus_birds + ' FREE'"></span>
                                                </template>
                                            </div>
                                            <template x-if="offer.description">
                                                <p class="text-xs text-gray-400 mt-1" x-text="offer.description"></p>
                                            </template>
                                        </div>
                                        <span class="text-lg font-bold whitespace-nowrap" style="color: var(--accent);" x-text="'{{ $settings['currency'] }}' + parseFloat(offer.price).toLocaleString()"></span>
                                    </div>
                                    <div x-show="selectedOffer == offer.id" class="absolute top-2 right-2">
                                        <svg class="w-5 h-5" style="color: var(--accent);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                </button>
                            </template>
                        </div>
                        <button type="button" x-show="selectedOffer" @click="selectOffer(selectedOffer)" class="mt-2 text-xs text-gray-400 hover:text-gray-600 transition-colors">
                            Clear selection (use per-bird pricing)
                        </button>
                    </div>
                @endif

                {{-- Owner Details --}}
                <div class="space-y-4 mb-8">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ __('t.your_details') }}</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="flyer_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.flyer_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="flyer_name" name="flyer_name" value="{{ old('flyer_name') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--accent);">
                        </div>
                        <div>
                            <label for="syndicate_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.syndicate_name') }}</label>
                            <input type="text" id="syndicate_name" name="syndicate_name" value="{{ old('syndicate_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--accent);">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.email_address') }} <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--accent);">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.phone') }}</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--accent);">
                        </div>
                    </div>

                    <div>
                        <label for="team_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.team_name') }}</label>
                        <input type="text" id="team_name" name="team_name" value="{{ old('team_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--accent);"
                               placeholder="{{ __('t.team_name_help') }}">
                    </div>
                </div>

                {{-- Birds --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">{{ __('t.your_birds') }}</h3>
                        <span class="text-xs text-gray-400" x-text="birds.length + ' / ' + maxBirds + ' birds'"></span>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(bird, index) in birds" :key="index">
                            <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500" x-text="index + 1"></div>
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('t.ring_number') }} <span class="text-red-500">*</span></label>
                                        <input type="text" :name="'birds['+index+'][ring_number]'" x-model="bird.ring_number" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--accent);"
                                               placeholder="e.g. GB-25-N12345">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('t.pigeon_name') }}</label>
                                        <input type="text" :name="'birds['+index+'][pigeon_name]'" x-model="bird.pigeon_name"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--accent);"
                                               placeholder="{{ __('t.optional') }}">
                                    </div>
                                </div>
                                <button type="button" @click="removeBird(index)" x-show="birds.length > 1"
                                        class="flex-shrink-0 p-1.5 text-gray-400 hover:text-red-500 transition-colors mt-5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addBird()" x-show="birds.length < maxBirds"
                            class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border-2 border-dashed border-gray-300 text-gray-500 hover:border-gray-400 hover:text-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        {{ __('t.add_another_bird') }}
                    </button>
                </div>

                {{-- Notes --}}
                <div class="mb-8">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('t.additional_notes') }}</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent" style="--tw-ring-color: var(--accent);"
                              placeholder="Any additional information (shipping arrangements, agent details, etc.)">{{ old('notes') }}</textarea>
                </div>

                {{-- Fee summary --}}
                <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <template x-if="activeOffer">
                                <span>Package: <strong x-text="activeOffer.name"></strong></span>
                            </template>
                            <template x-if="!activeOffer">
                                <span>{{ __('t.entry_fee') }}: <strong>{{ $settings['currency'] }}{{ $settings['fee'] }}</strong> {{ __('t.per_bird') }}</span>
                            </template>
                        </div>
                        <span class="text-lg font-bold" style="color: var(--accent);" x-text="'{{ $settings['currency'] }}' + totalFee.toLocaleString()"></span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        <template x-if="activeOffer">
                            <span>Package price for <span x-text="activeOffer.number_of_birds + (activeOffer.bonus_birds > 0 ? ' + ' + activeOffer.bonus_birds + ' free' : '')"></span> bird(s). Payment details will be sent in your confirmation email.</span>
                        </template>
                        <template x-if="!activeOffer">
                            <span>Total based on <span x-text="birds.length"></span> bird(s). Payment details will be sent in your confirmation email.</span>
                        </template>
                    </p>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full sm:w-auto px-8 py-3 rounded-lg font-semibold text-white text-sm transition-colors hover:opacity-90" style="background: var(--accent);">
                    {{ __('t.submit_entry') }}
                </button>
            </form>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <h2 class="text-xl font-bold text-gray-900">Entries are currently closed</h2>
                <p class="text-gray-500 mt-2">{{ __('t.entries_closed_message') }}</p>
                @if(config('olr.contact_email'))
                    <a href="mailto:{{ config('olr.contact_email') }}" class="inline-flex items-center gap-2 mt-4 text-sm font-medium hover:underline" style="color: var(--accent);">
                        {{ __('t.contact_us') }}
                    </a>
                @endif
            </div>
        @endif
    </div>

    {{-- Sponsor --}}
    @if(config('olr.sponsor_image') && file_exists(public_path(config('olr.sponsor_image'))))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="pt-8 border-t border-gray-200 flex items-center justify-center gap-4">
                <span class="text-sm uppercase tracking-wider text-gray-500 font-medium">{{ __('t.sponsored_by') }}</span>
                <a href="{{ config('olr.sponsor_url', '#') }}" target="_blank" rel="noopener">
                    <img src="{{ asset(config('olr.sponsor_image')) }}" alt="{{ config('olr.sponsor_name', 'Sponsor') }}" class="h-20 object-contain">
                </a>
            </div>
        </div>
    @endif
@endsection

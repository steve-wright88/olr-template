@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Site Settings</h1>
        <p class="text-gray-500 text-sm mt-1">Changes here update your live website. Each section shows where it appears on the site.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-2xl space-y-10">
        @csrf

        {{-- ============================================== --}}
        {{-- HEADER & NAVIGATION                            --}}
        {{-- ============================================== --}}
        <fieldset>
            <div class="flex items-center gap-3 mb-1">
                <legend class="text-lg font-bold text-gray-900">Header & Navigation</legend>
            </div>
            <p class="text-sm text-gray-500 mb-5">The top bar on every page - your race name, tagline and brand colour.</p>

            {{-- Mini preview --}}
            <div class="rounded-lg overflow-hidden mb-5 border border-gray-200 shadow-sm">
                <div class="px-4 py-3 flex items-center justify-between" style="background: var(--primary);">
                    <span class="text-white font-extrabold text-sm tracking-tight">{{ $settings['site_name'] ?? config('olr.site_name') }}</span>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 rounded text-xs text-white/60">Home</span>
                        <span class="px-2 py-1 rounded text-xs text-white/60">Enter Your Birds</span>
                        <span class="px-2 py-1 rounded text-xs text-white/60">News</span>
                        <span class="px-2 py-1 rounded text-xs text-white/60">Results</span>
                        <span class="px-2 py-1 rounded text-xs text-white/60">More</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
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
            </div>
        </fieldset>

        <hr class="border-gray-200">

        {{-- ============================================== --}}
        {{-- HOMEPAGE                                        --}}
        {{-- ============================================== --}}
        <fieldset>
            <div class="flex items-center gap-3 mb-1">
                <legend class="text-lg font-bold text-gray-900">Homepage</legend>
            </div>
            <p class="text-sm text-gray-500 mb-5">What visitors see when they first land on your site.</p>

            {{-- Mini preview --}}
            <div class="rounded-lg overflow-hidden mb-5 border border-gray-200 shadow-sm">
                <div class="bg-gray-100 px-4 py-3">
                    <div class="flex justify-center gap-4 text-center">
                        <div>
                            <div class="text-sm font-bold text-gray-900">{{ $settings['homepage_pigeon_count'] ?? '-' }}</div>
                            <div class="text-[10px] text-gray-400 uppercase">Pigeons</div>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-900">{{ $settings['homepage_team_count'] ?? '-' }}</div>
                            <div class="text-[10px] text-gray-400 uppercase">Teams</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white px-4 py-3">
                    <div class="text-xs text-gray-400 italic">Your homepage write-up appears here...</div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="homepage_mode" class="block text-sm font-semibold text-gray-700 mb-2">Homepage Mode</label>
                    <select id="homepage_mode" name="homepage_mode" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors">
                        <option value="pre-season" {{ ($settings['homepage_mode'] ?? 'pre-season') === 'pre-season' ? 'selected' : '' }}>Pre-Season (promoting upcoming race)</option>
                        <option value="race-season" {{ ($settings['homepage_mode'] ?? '') === 'race-season' ? 'selected' : '' }}>Race Season (showing results + flights)</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Pre-Season shows your write-up only. Race Season adds live results, stats and upcoming flights.</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="homepage_pigeon_count" class="block text-sm font-semibold text-gray-700 mb-2">Pigeon Count</label>
                        <input type="number" id="homepage_pigeon_count" name="homepage_pigeon_count" value="{{ $settings['homepage_pigeon_count'] ?? '' }}"
                               class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                               placeholder="e.g. 1650">
                    </div>
                    <div>
                        <label for="homepage_team_count" class="block text-sm font-semibold text-gray-700 mb-2">Team Count</label>
                        <input type="number" id="homepage_team_count" name="homepage_team_count" value="{{ $settings['homepage_team_count'] ?? '' }}"
                               class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                               placeholder="e.g. 284">
                    </div>
                </div>
                <p class="text-xs text-gray-400 -mt-2">Shown in the stats bar on the homepage when in Race Season mode.</p>
                <div>
                    <label for="homepage_content" class="block text-sm font-semibold text-gray-700 mb-2">Homepage Write-up</label>
                    <textarea id="homepage_content" name="homepage_content" rows="10"
                              class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                              placeholder="Write about this year's race... HTML is supported.">{{ $settings['homepage_content'] ?? '' }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">The main content on your homepage. Tell visitors about this season. HTML is supported.</p>
                </div>
            </div>
        </fieldset>

        <hr class="border-gray-200">

        {{-- ============================================== --}}
        {{-- RACE PROGRAM PAGE - MAP                        --}}
        {{-- ============================================== --}}
        <fieldset x-data="{
            points: {{ json_encode(json_decode($settings['race_map_points'] ?? '[]', true) ?: []) }},
            colors: ['#2563eb','#0891b2','#059669','#d97706','#ea580c','#dc2626','#7c3aed','#db2777'],
            typeColors: { final: '#dc2626', super: '#7c3aed' },
            getColor(point, index) {
                return this.typeColors[point.type] || this.colors[index % this.colors.length];
            },
            addPoint() {
                this.points.push({ name: '', lat: '', lng: '', distance: '', date: '', type: 'hotspot' });
            },
            removePoint(i) {
                this.points.splice(i, 1);
            }
        }">
            <div class="flex items-center gap-3 mb-1">
                <legend class="text-lg font-bold text-gray-900">Race Program Map</legend>
            </div>
            <p class="text-sm text-gray-500 mb-5">The interactive map shown at the top of your <a href="{{ url('/page/race-program') }}" target="_blank" class="underline" style="color:var(--accent);">Race Program</a> page. Each point draws a coloured line from your loft.</p>

            {{-- Mini preview --}}
            <div class="rounded-lg overflow-hidden mb-5 border border-gray-200 shadow-sm bg-gray-100 p-4">
                <div class="flex items-center justify-center gap-1 flex-wrap">
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500">
                        <span class="w-2.5 h-2.5 rounded-full" style="background: var(--primary);"></span> Loft
                    </span>
                    <template x-for="(point, index) in points" :key="index">
                        <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500">
                            <span class="w-2.5 h-2.5 rounded-full" :style="'background:' + getColor(point, index)"></span>
                            <span x-text="point.name || ('Point ' + (index + 1))"></span>
                        </span>
                    </template>
                </div>
            </div>

            <div class="space-y-4 mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Loft Latitude</label>
                        <input type="text" name="race_map_loft_lat" value="{{ $settings['race_map_loft_lat'] ?? '53.05' }}"
                               class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                               placeholder="e.g. 53.05">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Loft Longitude</label>
                        <input type="text" name="race_map_loft_lng" value="{{ $settings['race_map_loft_lng'] ?? '-1.48' }}"
                               class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                               placeholder="e.g. -1.48">
                    </div>
                </div>
                <p class="text-xs text-gray-400 -mt-2">The starting point for all race lines on the map. Find your coordinates on <a href="https://www.latlong.net/" target="_blank" class="underline">latlong.net</a>.</p>
            </div>

            <template x-for="(point, index) in points" :key="index">
                <div class="border border-gray-200 rounded-lg p-4 mb-3 bg-gray-50">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full border-2 border-white shadow" :style="'background:' + getColor(point, index)"></span>
                            <span class="text-sm font-semibold text-gray-700" x-text="point.name || ('Race Point ' + (index + 1))"></span>
                            <span class="text-xs text-gray-400" x-text="point.distance ? '(' + point.distance + ')' : ''"></span>
                        </div>
                        <button type="button" @click="removePoint(index)" class="text-red-500 hover:text-red-700 text-xs font-medium">Remove</button>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                            <input type="text" x-model="point.name" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC]" placeholder="e.g. Warwick">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                            <select x-model="point.type" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC]">
                                <option value="hotspot">Hot Spot</option>
                                <option value="final">Grand Final</option>
                                <option value="super">Super Final</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Latitude</label>
                            <input type="text" x-model="point.lat" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC]" placeholder="e.g. 52.282">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Longitude</label>
                            <input type="text" x-model="point.lng" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC]" placeholder="e.g. -1.585">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Distance</label>
                            <input type="text" x-model="point.distance" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC]" placeholder="e.g. 51 Miles / 82 km">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                            <input type="text" x-model="point.date" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC]" placeholder="e.g. Sunday 10th August">
                        </div>
                    </div>
                </div>
            </template>

            <input type="hidden" name="race_map_points" :value="JSON.stringify(points)">

            <button type="button" @click="addPoint()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Race Point
            </button>
        </fieldset>

        <hr class="border-gray-200">

        {{-- ============================================== --}}
        {{-- FOOTER                                          --}}
        {{-- ============================================== --}}
        <fieldset>
            <div class="flex items-center gap-3 mb-1">
                <legend class="text-lg font-bold text-gray-900">Footer</legend>
            </div>
            <p class="text-sm text-gray-500 mb-5">Contact details and social links shown at the bottom of every page.</p>

            {{-- Mini preview --}}
            <div class="rounded-lg overflow-hidden mb-5 border border-gray-200 shadow-sm">
                <div class="px-4 py-3" style="background: var(--primary);">
                    <div class="flex justify-between text-white/60 text-[10px]">
                        <div>
                            <div class="font-bold text-white text-xs">{{ $settings['site_name'] ?? config('olr.site_name') }}</div>
                            <div>{{ $settings['tagline'] ?? config('olr.tagline') }}</div>
                        </div>
                        <div class="text-right">
                            <div>{{ $settings['contact_email'] ?? config('olr.contact_email') }}</div>
                            <div>{{ $settings['contact_phone'] ?? config('olr.contact_phone') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
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

                <div class="border-t border-gray-200 pt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Social Media Links</label>
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
            </div>
        </fieldset>

        {{-- Save --}}
        <div class="pt-4 pb-8">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                    style="background:var(--accent);">
                Save Settings
            </button>
        </div>
    </form>
@endsection

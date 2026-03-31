@extends('layouts.admin')

@section('title', 'Race Map')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Race Program</h1>
        <p class="text-gray-500 text-sm mt-1">The Race Program page on your site - the map, schedule and race details.</p>
    </div>

    {{-- Edit page content link --}}
    @php $raceProgramPage = \App\Models\Page::where('slug', 'race-program')->orWhere('slug', 'race-programme')->first(); @endphp
    @if($raceProgramPage)
        <div class="max-w-2xl mb-8 p-4 rounded-lg border border-gray-200 bg-white flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-gray-900">Page Content</div>
                <p class="text-xs text-gray-400 mt-0.5">Edit the race schedule tables, training info and other text on this page.</p>
            </div>
            <a href="{{ route('admin.pages.edit', $raceProgramPage) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all hover:opacity-90" style="background:var(--accent);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Page Content
            </a>
        </div>
    @endif

    <h2 class="text-lg font-bold text-gray-900 mb-1">Race Map</h2>
    <p class="text-gray-500 text-sm mb-6">The interactive map shown at the top of your <a href="{{ url('/page/race-program') }}" target="_blank" class="underline" style="color:var(--accent);">Race Program page</a>. Each point draws a coloured line from your loft.</p>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-2xl space-y-5"
          x-data="{
            points: {{ json_encode(json_decode($settings['race_map_points'] ?? '[]', true) ?: []) }},
            defaultColors: ['#2563eb','#0891b2','#059669','#d97706','#ea580c','#dc2626','#7c3aed','#db2777'],
            typeColors: { final: '#dc2626', super: '#7c3aed' },
            getColor(point, index) {
                if (point.color) return point.color;
                return this.typeColors[point.type] || this.defaultColors[index % this.defaultColors.length];
            },
            addPoint() {
                this.points.push({ name: '', lat: '', lng: '', distance: '', date: '', type: 'hotspot', color: '' });
            },
            removePoint(i) {
                this.points.splice(i, 1);
            },
            moveUp(i) {
                if (i <= 0) return;
                [this.points[i - 1], this.points[i]] = [this.points[i], this.points[i - 1]];
            },
            moveDown(i) {
                if (i >= this.points.length - 1) return;
                [this.points[i], this.points[i + 1]] = [this.points[i + 1], this.points[i]];
            }
        }">
        @csrf
        <input type="hidden" name="_redirect" value="admin.settings.race-map">

        {{-- Live key preview --}}
        <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100 p-4 mb-2">
            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mb-2 text-center">Map Key Preview</p>
            <div class="flex items-center justify-center gap-3 flex-wrap">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600">
                    <span class="w-3 h-3 rounded-full border border-white shadow-sm" style="background: var(--primary);"></span> Loft
                </span>
                <template x-for="(point, index) in points" :key="index">
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600">
                        <span class="w-3 h-3 rounded-full border border-white shadow-sm" :style="'background:' + getColor(point, index)"></span>
                        <span x-text="point.name || ('Point ' + (index + 1))"></span>
                    </span>
                </template>
            </div>
        </div>

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
        <p class="text-xs text-gray-400 -mt-3">The starting point for all race lines. Find coordinates at <a href="https://www.latlong.net/" target="_blank" class="underline">latlong.net</a>.</p>

        <div class="space-y-3">
            <template x-for="(point, index) in points" :key="index">
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full border-2 border-white shadow flex items-center justify-center text-[10px] font-bold text-white" :style="'background:' + getColor(point, index)" x-text="point.type === 'final' ? 'F' : point.type === 'super' ? 'SF' : (index + 1)"></span>
                            <span class="text-sm font-semibold text-gray-700" x-text="point.name || ('Race Point ' + (index + 1))"></span>
                            <span class="text-xs text-gray-400" x-text="point.distance ? '(' + point.distance + ')' : ''"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="moveUp(index)" :class="index === 0 ? 'opacity-20 cursor-not-allowed' : 'hover:text-gray-700'" class="text-gray-400 p-1" title="Move up">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" @click="moveDown(index)" :class="index === points.length - 1 ? 'opacity-20 cursor-not-allowed' : 'hover:text-gray-700'" class="text-gray-400 p-1" title="Move down">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <button type="button" @click="removePoint(index)" class="text-red-500 hover:text-red-700 text-xs font-medium">Remove</button>
                        </div>
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
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Line Colour</label>
                            <div class="flex items-center gap-2">
                                <input type="color" :value="getColor(point, index)" @input="point.color = $event.target.value"
                                       class="w-9 h-9 rounded border border-gray-300 bg-white cursor-pointer">
                                <input type="text" x-model="point.color" class="w-28 bg-white border border-gray-300 rounded px-3 py-2 text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC]"
                                       :placeholder="getColor(point, index)">
                                <span class="text-xs text-gray-400">Leave blank for auto colour</span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <input type="hidden" name="race_map_points" :value="JSON.stringify(points)">

        <button type="button" @click="addPoint()"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Race Point
        </button>

        <div class="pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                    style="background:var(--accent);">
                Save Race Map
            </button>
        </div>
    </form>
@endsection

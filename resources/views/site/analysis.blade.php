@extends('layouts.app')

@section('title', 'Bird Performance Analysis')

@section('content')
<div class="py-12" x-data="analysisApp()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Bird Performance Analysis</h1>
            <p class="text-gray-500 mt-1">{{ $season->name }}</p>
        </div>

        {{-- Loading State --}}
        <div x-show="loading" class="text-center py-20">
            <svg class="animate-spin h-10 w-10 mx-auto mb-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <p class="text-gray-400 text-lg">Loading analysis data&hellip;</p>
        </div>

        {{-- Tab Bar --}}
        <div x-show="!loading" x-cloak class="flex gap-1 bg-gray-100 rounded-lg p-1 mb-6 w-fit">
            <button @click="setTab('all')"
                    :class="tab === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                    class="px-5 py-2 rounded-md text-sm font-semibold transition-colors">
                All <span class="text-gray-400 ml-1" x-text="'(' + counts.all + ')'"></span>
            </button>
            <button @click="setTab('race')"
                    :class="tab === 'race' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                    class="px-5 py-2 rounded-md text-sm font-semibold transition-colors">
                Races <span class="text-gray-400 ml-1" x-text="'(' + counts.race + ')'"></span>
            </button>
            <button @click="setTab('training')"
                    :class="tab === 'training' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                    class="px-5 py-2 rounded-md text-sm font-semibold transition-colors">
                Training <span class="text-gray-400 ml-1" x-text="'(' + counts.training + ')'"></span>
            </button>
        </div>

        {{-- Filter Row --}}
        <div x-show="!loading" x-cloak class="flex flex-wrap items-center gap-3 mb-6">
            <select x-model="minFlights" @change="resetPage()"
                    class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                <option value="0">All flights</option>
                <option value="1">Min 1 flight</option>
                <option value="2">Min 2 flights</option>
                <option value="3">Min 3 flights</option>
                <option value="5">Min 5 flights</option>
                <option value="10">Min 10 flights</option>
            </select>
            <select x-model="countryFilter" @change="resetPage()"
                    class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                <option value="">All Countries</option>
                <template x-for="c in countries" :key="c">
                    <option :value="c" x-text="c"></option>
                </template>
            </select>
            <input type="text" x-model="search" @input="resetPage()"
                   placeholder="Search ring or team..."
                   class="flex-1 min-w-[200px] bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
            <div class="text-gray-400 text-sm" x-show="filtered().length !== currentData().length">
                <span x-text="filtered().length"></span> of <span x-text="currentData().length"></span> birds
            </div>
        </div>

        {{-- Data Table --}}
        <div x-show="!loading" x-cloak class="overflow-x-auto border border-gray-200 rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-200 text-left">
                            <th class="px-4 py-3 font-semibold text-gray-700 cursor-pointer select-none hover:text-gray-900" @click="sortBy('ring')">
                                Ring <span x-show="sortCol==='ring'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-4 py-3 font-semibold text-gray-700 cursor-pointer select-none hover:text-gray-900" @click="sortBy('team')">
                                Team <span x-show="sortCol==='team'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-3 py-3 font-semibold text-gray-700 text-center cursor-pointer select-none hover:text-gray-900" @click="sortBy('country')">
                                Country <span x-show="sortCol==='country'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-3 py-3 font-semibold text-gray-700 text-center cursor-pointer select-none hover:text-gray-900" @click="sortBy('raceFlights')">
                                Flts <span x-show="sortCol==='raceFlights'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-3 py-3 font-semibold text-gray-700 text-center cursor-pointer select-none hover:text-gray-900" @click="sortBy('avgCoefficient')">
                                Rating <span x-show="sortCol==='avgCoefficient'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-3 py-3 font-semibold text-gray-700 text-center cursor-pointer select-none hover:text-gray-900" @click="sortBy('avgPosition')">
                                Avg Pos <span x-show="sortCol==='avgPosition'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-3 py-3 font-semibold text-gray-700 text-center cursor-pointer select-none hover:text-gray-900" @click="sortBy('top5Pct')">
                                Top 5% <span x-show="sortCol==='top5Pct'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-3 py-3 font-semibold text-gray-700 text-center cursor-pointer select-none hover:text-gray-900" @click="sortBy('top10Pct')">
                                Top 10% <span x-show="sortCol==='top10Pct'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-3 py-3 font-semibold text-gray-700 text-center cursor-pointer select-none hover:text-gray-900" @click="sortBy('top20Pct')">
                                Top 20% <span x-show="sortCol==='top20Pct'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-3 py-3 font-semibold text-gray-700 text-right cursor-pointer select-none hover:text-gray-900" @click="sortBy('avgSpeedMpm')">
                                Avg Speed <span x-show="sortCol==='avgSpeedMpm'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                            <th class="px-4 py-3 font-semibold text-gray-700 text-right cursor-pointer select-none hover:text-gray-900" @click="sortBy('topSpeed')">
                                Top Speed <span x-show="sortCol==='topSpeed'" x-text="sortDir==='asc'?'\u25B2':'\u25BC'" class="text-blue-700"></span>
                            </th>
                        </tr>
                    </thead>
                    <template x-for="bird in paginated()" :key="bird.ring">
                        <tbody>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors even:bg-gray-50/50"
                                @click="toggle(bird.ring)">
                                <td class="px-4 py-3 font-semibold text-gray-900" x-text="bird.ring"></td>
                                <td class="px-4 py-3 text-gray-600" x-text="bird.team"></td>
                                <td class="px-3 py-3 text-center">
                                    <img :src="'https://flagcdn.com/24x18/' + bird.country.toLowerCase() + '.png'"
                                         :alt="bird.country"
                                         class="w-5 h-[14px] object-cover rounded-sm inline-block">
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="font-semibold text-gray-900" x-text="bird.raceFlights"></span><span class="text-gray-400" x-text="'/' + bird.totalFlights"></span>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="font-bold" :class="bird.avgCoefficient >= 80 ? 'text-blue-700' : bird.avgCoefficient >= 50 ? 'text-yellow-600' : 'text-gray-500'"
                                          x-text="bird.avgCoefficient ? bird.avgCoefficient.toFixed(1) + '%' : '-'"></span>
                                </td>
                                <td class="px-3 py-3 text-center font-semibold text-gray-900" x-text="bird.avgPosition ? bird.avgPosition.toFixed(1) : '-'"></td>
                                <td class="px-3 py-3 text-center text-gray-600" x-text="bird.top5Pct ? bird.top5Pct.toFixed(0) + '%' : '-'"></td>
                                <td class="px-3 py-3 text-center text-gray-600" x-text="bird.top10Pct ? bird.top10Pct.toFixed(0) + '%' : '-'"></td>
                                <td class="px-3 py-3 text-center text-gray-600" x-text="bird.top20Pct ? bird.top20Pct.toFixed(0) + '%' : '-'"></td>
                                <td class="px-3 py-3 text-right">
                                    <span class="font-semibold text-gray-900" x-text="bird.avgSpeedMpm ? bird.avgSpeedMpm.toFixed(2) : '-'"></span>
                                    <span class="text-gray-400 text-xs ml-1" x-text="bird.avgSpeed ? bird.avgSpeed.toFixed(1) + ' km/h' : ''"></span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900" x-text="bird.topSpeed ? bird.topSpeed.toFixed(1) : '-'"></td>
                            </tr>
                            {{-- Expanded Row --}}
                            <tr x-show="expanded === bird.ring" x-cloak class="bg-gray-50">
                                <td colspan="11" class="px-4 py-4">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs">
                                            <thead>
                                                <tr class="text-gray-500 text-left border-b border-gray-200">
                                                    <th class="px-3 py-2 font-semibold">Flight</th>
                                                    <th class="px-3 py-2 font-semibold text-center">Type</th>
                                                    <th class="px-3 py-2 font-semibold">Date</th>
                                                    <th class="px-3 py-2 font-semibold text-right">Distance</th>
                                                    <th class="px-3 py-2 font-semibold text-center">Position</th>
                                                    <th class="px-3 py-2 font-semibold text-center">Basketed</th>
                                                    <th class="px-3 py-2 font-semibold text-right">m/min</th>
                                                    <th class="px-3 py-2 font-semibold text-right">Coeff %</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                <template x-for="fl in bird.flights || []" :key="fl.flightName">
                                                    <tr class="hover:bg-gray-100">
                                                        <td class="px-3 py-2 font-medium text-gray-900" x-text="fl.flightName"></td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="px-2 py-0.5 rounded text-xs font-semibold"
                                                                  :class="fl.flightType !== 'training' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'"
                                                                  x-text="fl.flightType === 'training' ? 'TRN' : 'RACE'"></span>
                                                        </td>
                                                        <td class="px-3 py-2 text-gray-500" x-text="fl.date || '-'"></td>
                                                        <td class="px-3 py-2 text-right text-gray-500" x-text="fl.distance ? fl.distance + ' km' : '-'"></td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-bold"
                                                                  :class="fl.position <= 3 ? 'text-yellow-600' : fl.position <= 20 ? 'text-blue-700' : 'text-gray-900'"
                                                                  x-text="fl.position || '-'"></span>
                                                        </td>
                                                        <td class="px-3 py-2 text-center text-gray-500" x-text="fl.basketed || '-'"></td>
                                                        <td class="px-3 py-2 text-right font-semibold text-gray-900" x-text="fl.speedMpm ? parseFloat(fl.speedMpm).toFixed(2) : '-'"></td>
                                                        <td class="px-3 py-2 text-right text-gray-600" x-text="fl.coefficient ? parseFloat(fl.coefficient).toFixed(1) + '%' : '-'"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </table>
            </div>

            {{-- Empty State --}}
            <div x-show="filtered().length === 0" class="p-10 text-center">
                <p class="text-gray-400">No birds match your filters.</p>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between mt-6" x-show="totalPages() > 1">
            <button @click="prevPage()"
                    :disabled="page === 1"
                    :class="page === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 transition-colors">
                &larr; Previous
            </button>
            <span class="text-gray-500 text-sm">
                Page <span class="text-gray-900 font-bold" x-text="page"></span> of <span class="text-gray-900 font-bold" x-text="totalPages()"></span>
            </span>
            <button @click="nextPage()"
                    :disabled="page >= totalPages()"
                    :class="page >= totalPages() ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 transition-colors">
                Next &rarr;
            </button>
        </div>

    </div>
</div>

<script>
function analysisApp() {
    return {
        raw: { all: [], race: [], training: [] },
        counts: { all: 0, race: 0, training: 0 },
        tab: 'all',
        page: 1,
        perPage: 50,
        sortCol: 'avgCoefficient',
        sortDir: 'desc',
        minFlights: 0,
        countryFilter: '',
        search: '',
        expanded: null,
        countries: [],

        loading: true,

        init() {
            fetch('{{ route("analysis.data") }}')
                .then(r => {
                    if (!r.ok) throw new Error('No data');
                    return r.json();
                })
                .then(data => {
                    this.raw.all = data.all || [];
                    this.raw.race = data.race || [];
                    this.raw.training = data.training || [];
                    this.counts = data.flightCounts || { all: this.raw.all.length, race: this.raw.race.length, training: this.raw.training.length };
                    this.buildCountries();
                    this.loading = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        },

        buildCountries() {
            const set = new Set();
            this.raw.all.forEach(b => { if (b.country) set.add(b.country); });
            this.countries = [...set].sort();
        },

        setTab(t) {
            this.tab = t;
            this.resetPage();
        },

        currentData() {
            return this.raw[this.tab] || [];
        },

        filtered() {
            let data = this.currentData();
            const min = parseInt(this.minFlights) || 0;
            if (min > 0) {
                data = data.filter(b => (b.totalFlights || 0) >= min);
            }
            if (this.countryFilter) {
                data = data.filter(b => b.country === this.countryFilter);
            }
            if (this.search) {
                const q = this.search.toLowerCase();
                data = data.filter(b =>
                    (b.ring && b.ring.toLowerCase().includes(q)) ||
                    (b.team && b.team.toLowerCase().includes(q))
                );
            }
            return this.sorted(data);
        },

        sorted(data) {
            const col = this.sortCol;
            const dir = this.sortDir === 'asc' ? 1 : -1;
            return [...data].sort((a, b) => {
                let av = a[col], bv = b[col];
                if (typeof av === 'string') av = av.toLowerCase();
                if (typeof bv === 'string') bv = bv.toLowerCase();
                if (av == null) av = dir === 1 ? Infinity : -Infinity;
                if (bv == null) bv = dir === 1 ? Infinity : -Infinity;
                if (av < bv) return -1 * dir;
                if (av > bv) return 1 * dir;
                return 0;
            });
        },

        sortBy(col) {
            if (this.sortCol === col) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortCol = col;
                this.sortDir = col === 'ring' || col === 'team' || col === 'country' ? 'asc' : 'desc';
            }
            this.resetPage();
        },

        paginated() {
            const f = this.filtered();
            const start = (this.page - 1) * this.perPage;
            return f.slice(start, start + this.perPage);
        },

        totalPages() {
            return Math.max(1, Math.ceil(this.filtered().length / this.perPage));
        },

        resetPage() {
            this.page = 1;
            this.expanded = null;
        },

        prevPage() {
            if (this.page > 1) this.page--;
        },

        nextPage() {
            if (this.page < this.totalPages()) this.page++;
        },

        toggle(ring) {
            this.expanded = this.expanded === ring ? null : ring;
        }
    };
}
</script>
@endsection

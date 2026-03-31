@extends('layouts.app')

@section('title', 'Pool Entry | ' . config('olr.site_name'))

@section('content')
    {{-- Banner --}}
    <section class="border-b border-gray-200">
        @if(config('olr.banner') && file_exists(public_path(config('olr.banner'))))
            <img src="{{ asset(config('olr.banner')) }}" alt="{{ config('olr.site_name') }}" class="w-full h-auto">
        @endif
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="text-2xl font-bold tracking-tight text-gray-900 mb-2">Enter Pools</h1>
        <p class="text-gray-500 mb-5 text-sm">Submit your pool entries online or download the forms to fill in manually.</p>

        {{-- PDF Downloads --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
            @if(file_exists(public_path('downloads/pool-hotspots.pdf')))
                <a href="{{ asset('downloads/pool-hotspots.pdf') }}" target="_blank" class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-200 text-center hover:border-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Hot Spot Pool Sheet
                </a>
            @endif
            @if(file_exists(public_path('downloads/pool-races.pdf')))
                <a href="{{ asset('downloads/pool-races.pdf') }}" target="_blank" class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-200 text-center hover:border-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Race Pool Sheet
                </a>
            @endif
        </div>

        <h2 class="text-lg font-bold tracking-tight text-gray-900 mb-1">Submit Online</h2>
        <p class="text-gray-500 mb-6 text-sm">Select your pool type, add your birds, and tick which pools you want to enter.</p>

        <form method="POST" action="{{ route('pools.store') }}"
              x-data="poolForm()" x-cloak>
            @csrf

            {{-- Pool Type --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pool Type</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="setType('hotspot')"
                            :class="poolType === 'hotspot' ? 'border-2 ring-2 ring-blue-200' : 'border border-gray-200'"
                            class="rounded-lg p-4 text-left transition-all hover:border-gray-300"
                            :style="poolType === 'hotspot' ? 'border-color: var(--accent)' : ''">
                        <div class="font-bold text-gray-900">Hot Spot Races</div>
                        <div class="text-xs text-gray-500 mt-1">Qualifying hot spot races</div>
                    </button>
                    <button type="button" @click="setType('race')"
                            :class="poolType === 'race' ? 'border-2 ring-2 ring-blue-200' : 'border border-gray-200'"
                            class="rounded-lg p-4 text-left transition-all hover:border-gray-300"
                            :style="poolType === 'race' ? 'border-color: var(--accent)' : ''">
                        <div class="font-bold text-gray-900">Race / Final</div>
                        <div class="text-xs text-gray-500 mt-1">Semi final and grand final</div>
                    </button>
                </div>
                <input type="hidden" name="pool_type" :value="poolType">
            </div>

            {{-- Details --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Race / Event <span class="text-red-500">*</span></label>
                    <select name="race_point" required x-model="racePoint"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Select a race...</option>
                        <template x-for="point in filteredRaces" :key="point.name">
                            <option :value="point.name" x-text="point.label"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Syndicate Name <span class="text-red-500">*</span></label>
                    <input type="text" name="syndicate_name" required value="{{ old('syndicate_name') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
            </div>

            {{-- Birds + Stakes --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-6">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900">Your Birds</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-bold uppercase text-gray-500 w-40">Ring Number</th>
                                <template x-for="(amt, i) in amounts" :key="i">
                                    <th class="px-2 py-2 text-center text-xs font-bold uppercase text-gray-500" x-text="amt"></th>
                                </template>
                                <th class="px-2 py-2 text-center text-xs font-bold uppercase text-gray-500" x-text="nomLabel"></th>
                                <th class="px-3 py-2 text-right text-xs font-bold uppercase text-gray-500 w-20">Total</th>
                                <th class="px-2 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(bird, idx) in birds" :key="idx">
                                <tr class="border-b border-gray-100">
                                    <td class="px-3 py-2">
                                        <input type="text" :name="'birds[' + idx + '][ring_number]'" x-model="bird.ring_number" required
                                               placeholder="e.g. GB-25-N12345"
                                               class="w-full rounded border-gray-300 text-xs px-2 py-1.5 focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <template x-for="(amt, j) in amounts" :key="j">
                                        <td class="px-2 py-2 text-center">
                                            <input type="checkbox" :checked="bird.stakes[amt]"
                                                   @change="bird.stakes[amt] = $event.target.checked; calcBirdTotal(idx)"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <input type="hidden" :name="'birds[' + idx + '][stakes][' + amt + ']'" :value="bird.stakes[amt] ? 1 : 0">
                                        </td>
                                    </template>
                                    <td class="px-2 py-2 text-center">
                                        <input type="checkbox" :checked="bird.stakes[nomLabel]"
                                               @change="bird.stakes[nomLabel] = $event.target.checked; calcBirdTotal(idx)"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <input type="hidden" :name="'birds[' + idx + '][stakes][' + nomLabel + ']'" :value="bird.stakes[nomLabel] ? 1 : 0">
                                    </td>
                                    <td class="px-3 py-2 text-right font-bold text-gray-900 tabular-nums">
                                        <span x-text="'£' + bird.total.toFixed(2)"></span>
                                        <input type="hidden" :name="'birds[' + idx + '][bird_total]'" :value="bird.total.toFixed(2)">
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <button type="button" @click="removeBird(idx)" x-show="birds.length > 1"
                                                class="text-red-400 hover:text-red-600 text-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t border-gray-200 flex items-center justify-between">
                    <button type="button" @click="addBird()"
                            class="text-sm font-medium hover:underline" style="color: var(--accent);">
                        + Add Bird
                    </button>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 uppercase tracking-wider">Grand Total</span>
                        <span class="ml-2 text-lg font-black text-gray-900" x-text="'£' + grandTotal.toFixed(2)"></span>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-3 rounded-lg font-bold text-white text-sm transition-colors hover:opacity-90"
                    style="background: var(--accent);">
                Submit Pool Entry
            </button>

            <p class="text-xs text-gray-400 mt-3 text-center">All pools must be paid in full prior to liberation.</p>
        </form>

        {{-- Back link --}}
        <div class="mt-6 text-center">
            <a href="{{ route('enter') }}" class="text-sm text-gray-400 hover:text-gray-700">&larr; Back to Enter Your Birds</a>
        </div>
    </div>

    <script>
    function poolForm() {
        const hotspotAmounts = @json($hotspotAmounts);
        const raceAmounts = @json($raceAmounts);
        const hotspotNom = @json($hotspotNom);
        const raceNom = @json($raceNom);

        const allRacePoints = @json($racePoints);

        function parseAmount(str) {
            const cleaned = str.replace(/[^0-9.p]/g, '');
            if (cleaned.includes('p')) return parseFloat(cleaned.replace('p', '')) / 100;
            return parseFloat(cleaned) || 0;
        }

        function parseNom(str) {
            return parseFloat(str.replace(/[^0-9.]/g, '')) || 0;
        }

        function newBird(amounts, nom) {
            const stakes = {};
            amounts.forEach(a => stakes[a] = false);
            stakes[nom] = false;
            return { ring_number: '', stakes, total: 0 };
        }

        return {
            poolType: 'hotspot',
            racePoint: '',
            amounts: hotspotAmounts,
            nomLabel: hotspotNom,
            birds: [newBird(hotspotAmounts, hotspotNom)],
            grandTotal: 0,

            get filteredRaces() {
                return allRacePoints.filter(p => {
                    if (this.poolType === 'hotspot') return p.type === 'hotspot';
                    return p.type === 'final' || p.type === 'semi' || p.type === 'super';
                });
            },

            setType(type) {
                this.poolType = type;
                this.amounts = type === 'hotspot' ? hotspotAmounts : raceAmounts;
                this.nomLabel = type === 'hotspot' ? hotspotNom : raceNom;
                this.birds = [newBird(this.amounts, this.nomLabel)];
                this.grandTotal = 0;
                this.racePoint = '';
            },

            addBird() {
                this.birds.push(newBird(this.amounts, this.nomLabel));
            },

            removeBird(idx) {
                this.birds.splice(idx, 1);
                this.calcGrandTotal();
            },

            calcBirdTotal(idx) {
                let total = 0;
                const bird = this.birds[idx];
                this.amounts.forEach(amt => {
                    if (bird.stakes[amt]) total += parseAmount(amt);
                });
                if (bird.stakes[this.nomLabel]) total += parseNom(this.nomLabel);
                bird.total = total;
                this.calcGrandTotal();
            },

            calcGrandTotal() {
                this.grandTotal = this.birds.reduce((sum, b) => sum + b.total, 0);
            }
        };
    }
    </script>
@endsection

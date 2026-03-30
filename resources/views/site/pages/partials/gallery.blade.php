@php
    $photos = \App\Models\GalleryPhoto::ordered()->get();
    $categories = $photos->pluck('category')->filter()->unique()->values();
@endphp

@if($photos->isNotEmpty())
    <div class="mt-10" x-data="{ filter: 'all' }">
        @if($categories->count() > 1)
            <div class="flex flex-wrap gap-2 mb-6">
                <button @click="filter = 'all'"
                        :class="filter === 'all' ? 'text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        :style="filter === 'all' ? 'background: var(--accent)' : ''"
                        class="px-4 py-1.5 rounded-full text-sm font-medium transition-all">
                    {{ __('t.all') }}
                </button>
                @foreach($categories as $cat)
                    <button @click="filter = '{{ e($cat) }}'"
                            :class="filter === '{{ e($cat) }}' ? 'text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            :style="filter === '{{ e($cat) }}' ? 'background: var(--accent)' : ''"
                            class="px-4 py-1.5 rounded-full text-sm font-medium transition-all">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($photos as $photo)
                <a href="{{ asset('storage/' . $photo->path) }}" target="_blank"
                   x-show="filter === 'all'{{ $photo->category ? " || filter === '" . e($photo->category) . "'" : '' }}"
                   x-transition
                   class="group relative rounded-lg overflow-hidden aspect-square">
                    <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->caption }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @if($photo->caption)
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-white text-sm">{{ $photo->caption }}</p>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@else
    <p class="mt-6 text-gray-500 italic">{{ __('t.photos_coming_soon') }}</p>
@endif

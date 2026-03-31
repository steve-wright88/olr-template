@php
    $photos = \App\Models\GalleryPhoto::ordered()->get();
    $categories = $photos->pluck('category')->filter()->unique()->values();
@endphp

@if($photos->isNotEmpty())
    <div class="mt-10" x-data="galleryApp()">
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
            @foreach($photos as $i => $photo)
                <button type="button" @click="open({{ $i }})"
                   x-show="filter === 'all'{{ $photo->category ? " || filter === '" . e($photo->category) . "'" : '' }}"
                   x-transition
                   class="group relative rounded-lg overflow-hidden aspect-square cursor-pointer">
                    <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->caption }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    @if($photo->caption)
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-white text-sm">{{ $photo->caption }}</p>
                        </div>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Lightbox --}}
        <div x-show="lightbox" x-transition.opacity x-cloak
             @keydown.escape.window="close()" @keydown.left.window="prev()" @keydown.right.window="next()"
             @touchstart="touchStartX = $event.touches[0].clientX" @touchend="handleSwipe($event)"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.60);" @click.self="close()">

            {{-- Close --}}
            <button @click="close()" class="absolute top-4 right-4 z-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full p-2 transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Prev --}}
            <button @click.stop="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 bg-white/20 hover:bg-white/40 backdrop-blur-sm rounded-full p-3 transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>

            {{-- Image --}}
            <div class="flex flex-col items-center px-16 sm:px-20" style="max-width: min(90vw, 1000px);">
                <img :src="photos[current]?.src" :alt="photos[current]?.caption" class="max-w-full max-h-[75vh] object-contain rounded-lg shadow-2xl">
                <p x-show="photos[current]?.caption" x-text="photos[current]?.caption" class="text-white/80 text-sm mt-3 text-center"></p>
                <p class="text-white/40 text-xs mt-1" x-text="(current + 1) + ' / ' + photos.length"></p>
            </div>

            {{-- Next --}}
            <button @click.stop="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 bg-white/20 hover:bg-white/40 backdrop-blur-sm rounded-full p-3 transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <script>
    function galleryApp() {
        return {
            filter: 'all',
            lightbox: false,
            current: 0,
            photos: @json($photos->map(fn($p) => ['src' => asset('storage/' . $p->path), 'caption' => $p->caption, 'category' => $p->category])->values()),

            touchStartX: 0,

            open(index) { this.current = index; this.lightbox = true; document.body.style.overflow = 'hidden'; },
            close() { this.lightbox = false; document.body.style.overflow = ''; },
            next() { this.current = (this.current + 1) % this.photos.length; },
            prev() { this.current = (this.current - 1 + this.photos.length) % this.photos.length; },
            handleSwipe(e) {
                const diff = this.touchStartX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 50) { diff > 0 ? this.next() : this.prev(); }
            },
        };
    }
    </script>
@else
    <div class="mt-10 py-16 text-center border-2 border-dashed border-gray-200 rounded-xl">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-gray-400 font-medium">Photos coming soon</p>
        <p class="text-gray-300 text-sm mt-1">Check back as we add images throughout the season.</p>
    </div>
@endif

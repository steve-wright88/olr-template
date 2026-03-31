@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gallery</h1>
    </div>

    {{-- Upload Form --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Upload Photos</h2>
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Select photos</label>
                    <input type="file" name="photos[]" multiple accept="image/*" required
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors">
                    <p class="text-xs text-gray-400 mt-1">You can select multiple photos at once. Max 5MB each.</p>
                    @error('photos') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    @error('photos.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Category (optional)</label>
                        @if($categories->isNotEmpty())
                            <select name="category"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]">
                                <option value="">No category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                                <option value="">-- New category --</option>
                            </select>
                        @endif
                        <input type="text" name="new_category" placeholder="e.g. Loft, Race Day, Birds, Events"
                               class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] {{ $categories->isNotEmpty() ? 'mt-2' : '' }}">
                    </div>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90 flex-shrink-0"
                            style="background:var(--accent);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Upload
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Photo Grid --}}
    @if($photos->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg px-6 py-10 text-center">
            <p class="text-gray-500">No photos yet. Upload some to get started.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($photos as $photo)
                <div class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ asset($photo->path) }}" alt="{{ $photo->caption }}"
                         class="w-full aspect-square object-cover">

                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-end">
                        <div class="w-full p-3 space-y-2">
                            @if($photo->caption)
                                <p class="text-white text-xs truncate">{{ $photo->caption }}</p>
                            @endif
                            @if($photo->category)
                                <span class="inline-block bg-white/20 text-white text-[10px] font-medium px-2 py-0.5 rounded-full">{{ $photo->category }}</span>
                            @endif
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.gallery.update', $photo) }}" class="flex-1" x-data>
                                    @csrf @method('PUT')
                                    <input type="text" name="caption" value="{{ $photo->caption }}" placeholder="Add caption..."
                                           class="w-full bg-white/20 text-white placeholder-white/60 text-xs rounded px-2 py-1.5 focus:outline-none focus:bg-white/30"
                                           @keydown.enter="$el.closest('form').submit()">
                                </form>
                                <form method="POST" action="{{ route('admin.gallery.destroy', $photo) }}"
                                      onsubmit="return confirm('Delete this photo?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-white/70 hover:text-red-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

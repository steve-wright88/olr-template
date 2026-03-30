@extends('layouts.admin')

@section('title', $post->exists ? 'Edit Post' : 'New Post')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $post->exists ? 'Edit Post' : 'New Post' }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $post->exists ? 'Update this post' : 'Share an update, news article, or livestream' }}</p>
    </div>

    <form method="POST"
          action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
          class="max-w-3xl space-y-6">
        @csrf
        @if($post->exists) @method('PUT') @endif

        {{-- Post Type --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
            <div class="flex gap-3">
                @foreach(['update' => 'Quick Update', 'news' => 'News Article', 'livestream' => 'Livestream'] as $val => $label)
                    <label class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition-colors
                        {{ old('post_type', $post->post_type ?? 'update') === $val ? 'border-[#0077CC] bg-blue-50 text-blue-900' : 'border-gray-200 text-gray-500 hover:border-gray-300 bg-white' }}">
                        <input type="radio" name="post_type" value="{{ $val }}"
                               {{ old('post_type', $post->post_type ?? 'update') === $val ? 'checked' : '' }}
                               class="sr-only" onchange="this.form.querySelectorAll('label').forEach(l => l.className = l.className.replace(/border-\[#0077CC\]|bg-blue-50|text-blue-900/g, '').replace('  ', ' ')); this.closest('label').classList.add('border-[#0077CC]', 'bg-blue-50', 'text-blue-900');">
                        <span class="text-sm font-semibold">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('post_type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}"
                   class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                   placeholder="Give your post a title">
            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Body --}}
        <div>
            <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
            <textarea id="body" name="body" rows="12"
                      class="richtext w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors resize-y"
                      placeholder="Write your message here...">{{ old('body', $post->body) }}</textarea>
            @error('body') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Excerpt --}}
        <div>
            <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-2">
                Excerpt <span class="text-gray-400 font-normal">(optional, shown in previews)</span>
            </label>
            <textarea id="excerpt" name="excerpt" rows="2"
                      class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors resize-y"
                      placeholder="Brief summary...">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        {{-- Livestream URL --}}
        <div x-data="{ showLivestream: '{{ old('post_type', $post->post_type ?? 'update') }}' === 'livestream' }"
             x-show="showLivestream" x-cloak
             x-init="document.querySelectorAll('input[name=post_type]').forEach(r => r.addEventListener('change', e => showLivestream = e.target.value === 'livestream'))">
            <label for="livestream_url" class="block text-sm font-semibold text-gray-700 mb-2">
                Livestream URL
            </label>
            <input type="url" id="livestream_url" name="livestream_url" value="{{ old('livestream_url', $post->livestream_url) }}"
                   class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                   placeholder="https://youtube.com/watch?v=... or Facebook Live URL">
            @error('livestream_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Options --}}
        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1"
                       {{ old('is_published', $post->exists ? $post->is_published : true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-[#0077CC] focus:ring-[#0077CC]/30">
                <span class="text-sm font-medium text-gray-700">Publish immediately</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_pinned" value="0">
                <input type="checkbox" name="is_pinned" value="1"
                       {{ old('is_pinned', $post->is_pinned) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-[#0077CC] focus:ring-[#0077CC]/30">
                <span class="text-sm font-medium text-gray-700">Pin to top</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                    style="background:var(--accent);">
                {{ $post->exists ? 'Update Post' : 'Publish Post' }}
            </button>
            <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Cancel</a>
        </div>
    </form>
@endsection

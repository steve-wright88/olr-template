@extends('layouts.admin')

@section('title', $page->exists ? 'Edit Page' : 'New Page')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $page->exists ? 'Edit Page' : 'New Page' }}</h1>
    </div>

    <form method="POST"
          action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
          class="max-w-3xl space-y-6">
        @csrf
        @if($page->exists) @method('PUT') @endif

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}"
                   class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                   placeholder="Page title" required>
            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Slug --}}
        <div>
            <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                Slug <span class="text-gray-400 font-normal">(auto-generated if left empty)</span>
            </label>
            <div class="flex items-center">
                <span class="text-gray-400 text-sm mr-2">/page/</span>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}"
                       class="flex-1 bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors"
                       placeholder="page-slug">
            </div>
            @error('slug') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Body --}}
        <div>
            <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
            <textarea id="body" name="body" rows="20"
                      class="richtext w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors resize-y"
                      placeholder="Page content..." required>{{ old('body', $page->body) }}</textarea>
            @error('body') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Sort Order --}}
        <div>
            <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}"
                   class="w-32 bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#0077CC]/20 focus:border-[#0077CC] transition-colors">
        </div>

        {{-- Published --}}
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1"
                   {{ old('is_published', $page->exists ? $page->is_published : true) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-[#0077CC] focus:ring-[#0077CC]/30">
            <span class="text-sm font-medium text-gray-700">Published</span>
        </label>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                    style="background:var(--accent);">
                {{ $page->exists ? 'Update Page' : 'Create Page' }}
            </button>
            <a href="{{ route('admin.pages.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Cancel</a>
        </div>
    </form>
@endsection

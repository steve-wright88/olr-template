@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pages</h1>
            <p class="text-gray-500 text-sm mt-1">Manage static pages (about, rules, contact, etc.)</p>
        </div>
        <a href="{{ route('admin.pages.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
           style="background:var(--accent);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Page
        </a>
    </div>

    @if($pages->isEmpty())
        <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
            <p class="text-gray-500 text-lg">No pages yet.</p>
            <a href="{{ route('admin.pages.create') }}" class="inline-block mt-4 text-sm font-semibold" style="color:var(--accent);">Create your first page &rarr;</a>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="text-left px-5 py-3 w-8 font-semibold">#</th>
                        <th class="text-left px-5 py-3 font-semibold">Title</th>
                        <th class="text-left px-5 py-3 font-semibold">Slug</th>
                        <th class="text-left px-5 py-3 font-semibold">Status</th>
                        <th class="text-right px-5 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pages as $page)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5 text-gray-400">{{ $page->sort_order }}</td>
                            <td class="px-5 py-3.5 font-medium text-gray-900">{{ $page->title }}</td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-gray-500 hover:text-gray-900 transition-colors">
                                    /page/{{ $page->slug }} &nearr;
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($page->is_published)
                                    <span class="text-blue-700 text-xs font-semibold uppercase">Published</span>
                                @else
                                    <span class="text-gray-400 text-xs font-semibold uppercase">Draft</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">Edit</a>
                                    <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

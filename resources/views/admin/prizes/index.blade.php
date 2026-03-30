@extends('layouts.admin')

@section('title', 'Prize Money')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Prize Money</h1>
    </div>

    {{-- Add Category Form --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-8">
        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Add Category</h2>
        <form method="POST" action="{{ route('admin.prizes.store') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" id="name" required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                       placeholder="e.g. Grand Final">
            </div>
            <div class="w-48">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" id="type"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="positions">Positions (table)</option>
                    <option value="award">Award (single)</option>
                </select>
            </div>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-opacity"
                    style="background: var(--accent);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Category
            </button>
        </form>
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Reorder Form --}}
    <div class="mb-6" x-data="prizeOrder()" x-init="init()">
        <div id="prize-list" class="space-y-6">
    @forelse($categories as $category)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm" data-id="{{ $category->id }}" x-data="{ editing: false }">
            {{-- Category Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col mr-1">
                        @if(!$loop->first)
                            <button type="button" @click="moveUp({{ $category->id }})" class="text-gray-400 hover:text-gray-700 p-0.5" title="Move up">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </button>
                        @endif
                        @if(!$loop->last)
                            <button type="button" @click="moveDown({{ $category->id }})" class="text-gray-400 hover:text-gray-700 p-0.5" title="Move down">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $category->type === 'positions' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                        {{ $category->type === 'positions' ? 'Positions' : 'Award' }}
                    </span>
                    <h3 class="text-lg font-bold text-gray-900" x-show="!editing">{{ $category->name }}</h3>
                    @if(!$category->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <button @click="editing = !editing" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">
                        <span x-text="editing ? 'Cancel' : 'Edit'"></span>
                    </button>
                    <form method="POST" action="{{ route('admin.prizes.destroy', $category) }}" onsubmit="return confirm('Delete this category and all its positions?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 transition-colors">Delete</button>
                    </form>
                </div>
            </div>

            {{-- Edit Category Form --}}
            <div x-show="editing" x-cloak class="p-5 bg-gray-50 border-b border-gray-100">
                <form method="POST" action="{{ route('admin.prizes.update', $category) }}" class="flex flex-wrap items-end gap-4">
                    @csrf
                    @method('PUT')
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ $category->name }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="positions" {{ $category->type === 'positions' ? 'selected' : '' }}>Positions</option>
                            <option value="award" {{ $category->type === 'award' ? 'selected' : '' }}>Award</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Active
                    </label>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-opacity"
                            style="background: var(--accent);">
                        Save
                    </button>
                </form>
            </div>

            {{-- Positions - Single Form --}}
            <form method="POST" action="{{ route('admin.prizes.positions.bulk', $category) }}" class="p-5">
                @csrf
                @if($category->positions->isNotEmpty())
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 pr-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">Label</th>
                                <th class="text-left py-2 pr-4 font-semibold text-gray-500 uppercase text-xs tracking-wider">Amount</th>
                                <th class="text-right py-2 font-semibold text-gray-500 uppercase text-xs tracking-wider w-20">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->positions as $position)
                                <tr class="border-b border-gray-100">
                                    <td class="py-1.5 pr-3">
                                        <input type="hidden" name="positions[{{ $position->id }}][id]" value="{{ $position->id }}">
                                        <input type="text" name="positions[{{ $position->id }}][label]" value="{{ $position->label }}"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    </td>
                                    <td class="py-1.5 pr-3">
                                        <input type="text" name="positions[{{ $position->id }}][amount]" value="{{ $position->amount }}"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    </td>
                                    <td class="py-1.5 text-right">
                                        <label class="text-xs text-red-400 cursor-pointer flex items-center justify-end gap-1">
                                            <input type="checkbox" name="delete[]" value="{{ $position->id }}" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                            Delete
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-gray-400 italic mb-4">No positions yet.</p>
                @endif

                {{-- Add new position row --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-500 mb-1">New Label</label>
                        <input type="text" name="new_label" placeholder="e.g. 1st"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-500 mb-1">New Amount</label>
                        <input type="text" name="new_amount" placeholder="e.g. £5,000 or TBC"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-opacity"
                            style="background: var(--accent);">
                        Save All Prizes
                    </button>
                </div>
            </form>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center">
            <p class="text-gray-400 text-sm">No prize categories yet. Add one above to get started.</p>
        </div>
    @endforelse
        </div>
    </div>

    <script>
        function prizeOrder() {
            return {
                init() {},
                moveUp(id) { this.swap(id, 'up'); },
                moveDown(id) { this.swap(id, 'down'); },
                swap(id, direction) {
                    const list = document.getElementById('prize-list');
                    const items = [...list.children];
                    const index = items.findIndex(el => el.dataset.id == id);
                    const swapIndex = direction === 'up' ? index - 1 : index + 1;
                    if (swapIndex < 0 || swapIndex >= items.length) return;

                    // Swap in DOM
                    if (direction === 'up') {
                        list.insertBefore(items[index], items[swapIndex]);
                    } else {
                        list.insertBefore(items[swapIndex], items[index]);
                    }

                    // Submit new order
                    const order = [...list.children].map(el => el.dataset.id);
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.prizes.reorder") }}';
                    form.innerHTML = `@csrf` + order.map((id, i) => `<input type="hidden" name="order[]" value="${id}">`).join('');
                    document.body.appendChild(form);
                    form.submit();
                }
            };
        }
    </script>
@endsection

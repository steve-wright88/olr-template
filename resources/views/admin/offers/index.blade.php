@extends('layouts.admin')

@section('title', 'Entry Offers')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Entry Offers</h1>
        <a href="{{ route('admin.entry-settings') }}" class="text-sm font-medium hover:underline" style="color: var(--accent);">Entry Settings &rarr;</a>
    </div>

    {{-- Add New Offer --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Add New Offer</h2>

        <form method="POST" action="{{ route('admin.offers.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Offer Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                           placeholder="e.g. 5 Bird Package">
                </div>
                <div>
                    <label for="number_of_birds" class="block text-sm font-medium text-gray-700 mb-1">Birds Included</label>
                    <input type="number" id="number_of_birds" name="number_of_birds" value="{{ old('number_of_birds') }}" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                <div>
                    <label for="bonus_birds" class="block text-sm font-medium text-gray-700 mb-1">Bonus (Free) Birds</label>
                    <input type="number" id="bonus_birds" name="bonus_birds" value="{{ old('bonus_birds', 0) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="flex items-end pb-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input type="text" id="description" name="description" value="{{ old('description') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                       placeholder="e.g. 5 birds for &pound;500 + 1 FREE bird!">
            </div>

            <div class="mt-4">
                <button type="submit" class="px-6 py-2.5 rounded-lg font-semibold text-white text-sm transition-colors hover:opacity-90" style="background: var(--accent);">
                    Add Offer
                </button>
            </div>
        </form>
    </div>

    {{-- Existing Offers --}}
    @if($offers->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg px-6 py-10 text-center">
            <p class="text-gray-500">No offers yet. Create your first offer above.</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Offer</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Birds</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Price</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Bonus</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($offers as $offer)
                        <tr class="hover:bg-gray-50" x-data="{ editing: false }">
                            {{-- Display Row --}}
                            <td class="px-4 py-3" x-show="!editing">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $offer->name }}</span>
                                    @if($offer->description)
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $offer->description }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600" x-show="!editing">{{ $offer->number_of_birds }}</td>
                            <td class="px-4 py-3 text-gray-600" x-show="!editing">{{ number_format($offer->price, 2) }}</td>
                            <td class="px-4 py-3 text-gray-600" x-show="!editing">
                                @if($offer->bonus_birds > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">+{{ $offer->bonus_birds }} free</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3" x-show="!editing">
                                @if($offer->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right" x-show="!editing">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editing = true" class="text-gray-400 hover:text-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" class="inline"
                                          onsubmit="return confirm('Delete this offer?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            {{-- Edit Row --}}
                            <td colspan="6" class="px-4 py-3" x-show="editing" x-cloak>
                                <form method="POST" action="{{ route('admin.offers.update', $offer) }}">
                                    @csrf @method('PUT')
                                    <div class="grid grid-cols-2 sm:grid-cols-6 gap-3">
                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                                            <input type="text" name="name" value="{{ $offer->name }}" required
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Birds</label>
                                            <input type="number" name="number_of_birds" value="{{ $offer->number_of_birds }}" min="1" required
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Price</label>
                                            <input type="number" name="price" value="{{ $offer->price }}" step="0.01" min="0" required
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Bonus</label>
                                            <input type="number" name="bonus_birds" value="{{ $offer->bonus_birds }}" min="0"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Sort</label>
                                            <input type="number" name="sort_order" value="{{ $offer->sort_order }}" min="0"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                                            <input type="text" name="description" value="{{ $offer->description }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div class="flex items-end gap-3 pb-1">
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="hidden" name="is_active" value="0">
                                                <input type="checkbox" name="is_active" value="1" {{ $offer->is_active ? 'checked' : '' }}
                                                       class="w-4 h-4 rounded border-gray-300">
                                                <span class="text-sm font-medium text-gray-700">Active</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 mt-3">
                                        <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-white text-sm transition-colors hover:opacity-90" style="background: var(--accent);">
                                            Save
                                        </button>
                                        <button type="button" @click="editing = false" class="px-4 py-2 rounded-lg font-semibold text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

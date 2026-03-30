@extends('layouts.admin')

@section('title', $agent->exists ? 'Edit Agent' : 'Add Agent')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $agent->exists ? 'Edit Agent' : 'Add Agent' }}</h1>
    </div>

    <form method="POST"
          action="{{ $agent->exists ? route('admin.agents.update', $agent) : route('admin.agents.store') }}"
          enctype="multipart/form-data"
          class="max-w-2xl space-y-6">
        @csrf
        @if($agent->exists) @method('PUT') @endif

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $agent->name) }}"
                   class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] transition-colors"
                   placeholder="Agent name" required>
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Country & Region --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="country" class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                <input type="text" id="country" name="country" value="{{ old('country', $agent->country) }}"
                       class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] transition-colors"
                       placeholder="e.g. Holland" required>
                @error('country') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="region" class="block text-sm font-semibold text-gray-700 mb-2">
                    Region <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <input type="text" id="region" name="region" value="{{ old('region', $agent->region) }}"
                       class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] transition-colors"
                       placeholder="e.g. North Holland">
                @error('region') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Email & Phone --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $agent->email) }}"
                       class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] transition-colors"
                       placeholder="agent@example.com">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $agent->phone) }}"
                       class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] transition-colors"
                       placeholder="+44 7000 000000">
                @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Photo --}}
        <div>
            <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">Photo</label>
            @if($agent->photo)
                <div class="mb-3 flex items-center gap-3">
                    <img src="{{ asset('images/' . $agent->photo) }}" alt="{{ $agent->name }}"
                         class="w-16 h-16 rounded-full object-cover">
                    <span class="text-sm text-gray-500">Current photo</span>
                </div>
            @endif
            <input type="file" id="photo" name="photo" accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors">
            @error('photo') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Sort Order --}}
        <div>
            <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $agent->sort_order ?? 0) }}"
                   class="w-32 bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)] transition-colors">
        </div>

        {{-- Active --}}
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $agent->exists ? $agent->is_active : true) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-[color:var(--accent)] focus:ring-[color:var(--accent)]/30">
            <span class="text-sm font-medium text-gray-700">Active</span>
        </label>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                    style="background:var(--accent);">
                {{ $agent->exists ? 'Update Agent' : 'Add Agent' }}
            </button>
            <a href="{{ route('admin.agents.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">Cancel</a>
        </div>
    </form>
@endsection

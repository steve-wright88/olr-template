@extends('layouts.admin')

@section('title', 'Entry Settings')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Entry Settings</h1>
        <a href="{{ route('admin.entries.index') }}" class="text-sm font-medium hover:underline" style="color: var(--accent);">View Entries &rarr;</a>
    </div>

    <form method="POST" action="{{ route('admin.entry-settings.update') }}" class="space-y-8">
        @csrf

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">General</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="entry_year" class="block text-sm font-medium text-gray-700 mb-1">Season Year</label>
                    <input type="text" id="entry_year" name="entry_year" value="{{ $settings['entry_year'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label for="entry_fee" class="block text-sm font-medium text-gray-700 mb-1">Entry Fee</label>
                    <input type="text" id="entry_fee" name="entry_fee" value="{{ $settings['entry_fee'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label for="entry_currency" class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                    <input type="text" id="entry_currency" name="entry_currency" value="{{ $settings['entry_currency'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="entry_max_birds" class="block text-sm font-medium text-gray-700 mb-1">Max Birds Per Entry</label>
                    <input type="number" id="entry_max_birds" name="entry_max_birds" value="{{ $settings['entry_max_birds'] }}" min="1" max="50" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label for="entry_deadline" class="block text-sm font-medium text-gray-700 mb-1">Entry Deadline</label>
                    <input type="date" id="entry_deadline" name="entry_deadline" value="{{ $settings['entry_deadline'] }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>

            <div class="mt-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="entry_is_open" value="0">
                    <input type="checkbox" name="entry_is_open" value="1" {{ $settings['entry_is_open'] === '1' ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300">
                    <span class="text-sm font-medium text-gray-700">Entries are open</span>
                </label>
                <p class="text-xs text-gray-400 mt-1">When unchecked, the form will show "Entries Closed" to visitors.</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Entry Page Content</h2>

            <div>
                <label for="entry_notes" class="block text-sm font-medium text-gray-700 mb-1">Information shown on entry page</label>
                <textarea id="entry_notes" name="entry_notes" rows="5"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                          placeholder="Payment instructions, shipping address, deadlines, etc.">{{ $settings['entry_notes'] }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Shown above the entry form. Include payment details, shipping info, etc.</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Bird Acceptance Dates</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bird_acceptance_start" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="bird_acceptance_start" name="bird_acceptance_start" value="{{ $settings['bird_acceptance_start'] }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label for="bird_acceptance_end" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="bird_acceptance_end" name="bird_acceptance_end" value="{{ $settings['bird_acceptance_end'] }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>

            <div class="mt-4 space-y-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="show_acceptance_dates_site" value="0">
                    <input type="checkbox" name="show_acceptance_dates_site" value="1" {{ ($settings['show_acceptance_dates_site'] ?? '1') === '1' ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300">
                    <span class="text-sm font-medium text-gray-700">Show on entry page</span>
                </label>
                <br>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="show_acceptance_dates_pdf" value="0">
                    <input type="checkbox" name="show_acceptance_dates_pdf" value="1" {{ ($settings['show_acceptance_dates_pdf'] ?? '1') === '1' ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300">
                    <span class="text-sm font-medium text-gray-700">Show on PDF</span>
                </label>
            </div>

            <p class="text-xs text-gray-400 mt-2">The dates when birds will be accepted. Shown to entrants on the entry page and/or the PDF form.</p>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 rounded-lg font-semibold text-white text-sm transition-colors hover:opacity-90" style="background: var(--accent);">
                Save Settings
            </button>
            <a href="{{ route('admin.entry-pdf') }}" class="text-sm font-medium hover:underline" style="color: var(--accent);">
                Manage Entry Form PDF &rarr;
            </a>
        </div>
    </form>
@endsection

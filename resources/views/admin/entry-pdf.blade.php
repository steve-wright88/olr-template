@extends('layouts.admin')

@section('title', 'Entry Form PDF')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Entry Form PDF</h1>
        <p class="mt-1 text-gray-500">Create a downloadable PDF entry form for your website. The site banner image will be used as the PDF header.</p>
    </div>

    {{-- Current PDF status --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Current PDF</h2>
        @if($pdfExists)
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">PDF is ready for download</span>
                </div>
                <a href="{{ asset('downloads/entry-form.pdf') }}" target="_blank"
                   class="inline-flex items-center gap-2 text-sm font-medium hover:underline" style="color: var(--accent);">
                    Preview PDF
                </a>
            </div>
            <p class="text-xs text-gray-400 mt-2">Visitors can download this from the "Enter Your Birds" page.</p>
        @else
            <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="text-sm">No PDF generated yet. Fill in the details below and generate one.</span>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.entry-pdf.update') }}" class="space-y-8">
        @csrf

        {{-- PDF Details --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">PDF Details</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="entry_year" class="block text-sm font-medium text-gray-700 mb-1">Season Year</label>
                    <input type="text" id="entry_year" name="entry_year" value="{{ $settings['entry_year'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]">
                </div>
                <div>
                    <label for="entry_fee" class="block text-sm font-medium text-gray-700 mb-1">Entry Fee</label>
                    <input type="text" id="entry_fee" name="entry_fee" value="{{ $settings['entry_fee'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]">
                </div>
                <div>
                    <label for="entry_currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <input type="text" id="entry_currency" name="entry_currency" value="{{ $settings['entry_currency'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="entry_max_birds" class="block text-sm font-medium text-gray-700 mb-1">Max Birds on Form</label>
                    <input type="number" id="entry_max_birds" name="entry_max_birds" value="{{ $settings['entry_max_birds'] }}" min="1" max="50" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]">
                    <p class="text-xs text-gray-400 mt-1">Number of bird rows on the printable PDF.</p>
                </div>
                <div>
                    <label for="entry_deadline" class="block text-sm font-medium text-gray-700 mb-1">Entry Deadline</label>
                    <input type="date" id="entry_deadline" name="entry_deadline" value="{{ $settings['entry_deadline'] }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]">
                </div>
            </div>

            <div class="mt-4">
                <label for="entry_pdf_intro" class="block text-sm font-medium text-gray-700 mb-1">Intro Text on PDF</label>
                <textarea id="entry_pdf_intro" name="entry_pdf_intro" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--accent)]/20 focus:border-[color:var(--accent)]"
                          placeholder="Instructions or welcome message at the top of the form">{{ $settings['entry_pdf_intro'] }}</textarea>
            </div>
        </div>

        {{-- PDF Fields --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-2">Fields on PDF</h2>
            <p class="text-sm text-gray-500 mb-4">Choose which fields appear on the printable entry form.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @php
                    $fieldLabels = [
                        'flyer_name' => "Flyer's Name",
                        'syndicate_name' => 'Syndicate Name',
                        'email' => 'Email Address',
                        'phone' => 'Mobile / Phone',
                        'team_name' => 'Team Name',
                        'number_of_birds' => 'Number of Birds',
                        'address' => 'Address',
                        'country' => 'Country',
                        'entry_fee' => 'Entry Fee Info',
                        'acceptance_dates' => 'Bird Acceptance Dates',
                        'pigeon_name' => 'Pigeon Name Column',
                        'pigeon_sex' => 'Sex Column',
                        'pigeon_colour' => 'Colour Column',
                    ];
                @endphp
                @foreach($fieldLabels as $key => $label)
                    <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                        <input type="hidden" name="pdf_fields[{{ $key }}]" value="0">
                        <input type="checkbox" name="pdf_fields[{{ $key }}]" value="1"
                               {{ ($fields[$key] ?? false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4">
            <button type="submit" name="action" value="save"
                    class="px-6 py-2.5 rounded-lg font-semibold text-white text-sm transition-colors hover:opacity-90" style="background: var(--accent);">
                Save Details
            </button>
            <button type="submit" name="action" value="generate"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-sm border-2 border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Save &amp; Generate PDF
            </button>
        </div>
    </form>
@endsection

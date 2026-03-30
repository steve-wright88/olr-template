<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EntryController extends Controller
{
    public function index(Request $request): View
    {
        $year = $request->get('year', Setting::get('entry_year', (string) date('Y')));
        $status = $request->get('status');

        $entries = Entry::with('birds')
            ->forYear($year)
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(25);

        $years = Entry::selectRaw('DISTINCT season_year')->orderByDesc('season_year')->pluck('season_year');

        return view('admin.entries.index', compact('entries', 'year', 'status', 'years'));
    }

    public function show(Entry $entry): View
    {
        $entry->load('birds');

        return view('admin.entries.show', compact('entry'));
    }

    public function updateStatus(Request $request, Entry $entry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,rejected',
        ]);

        $entry->update($validated);

        return back()->with('success', 'Entry status updated to ' . $validated['status'] . '.');
    }

    public function destroy(Entry $entry): RedirectResponse
    {
        $entry->delete();

        return redirect()->route('admin.entries.index')->with('success', 'Entry deleted.');
    }
}

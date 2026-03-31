<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoolEntry;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PoolEntryController extends Controller
{
    public function index(Request $request): View
    {
        $year = $request->input('year', Setting::get('entry_year', (string) date('Y')));
        $type = $request->input('type');
        $status = $request->input('status');

        $query = PoolEntry::with('birds')->forYear($year)->latest();

        if ($type) $query->forType($type);
        if ($status) $query->where('status', $status);

        $entries = $query->paginate(25)->withQueryString();

        return view('admin.pool-entries.index', compact('entries', 'year', 'type', 'status'));
    }

    public function show(PoolEntry $poolEntry): View
    {
        $poolEntry->load('birds');
        return view('admin.pool-entries.show', compact('poolEntry'));
    }

    public function updateStatus(Request $request, PoolEntry $poolEntry): RedirectResponse
    {
        $validated = $request->validate(['status' => 'required|in:pending,confirmed,rejected']);
        $poolEntry->update($validated);
        return back()->with('success', "Pool entry {$poolEntry->reference} marked as {$validated['status']}.");
    }

    public function destroy(PoolEntry $poolEntry): RedirectResponse
    {
        $ref = $poolEntry->reference;
        $poolEntry->delete();
        return redirect()->route('admin.pool-entries.index')->with('success', "Pool entry {$ref} deleted.");
    }
}

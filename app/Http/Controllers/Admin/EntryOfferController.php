<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntryOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EntryOfferController extends Controller
{
    public function index(): View
    {
        $offers = EntryOffer::ordered()->get();

        return view('admin.offers.index', compact('offers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number_of_birds' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'bonus_birds' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['bonus_birds'] = $validated['bonus_birds'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? EntryOffer::max('sort_order') + 1;

        EntryOffer::create($validated);

        return redirect()->route('admin.offers.index')->with('success', 'Offer created.');
    }

    public function update(Request $request, EntryOffer $entryOffer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number_of_birds' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'bonus_birds' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['bonus_birds'] = $validated['bonus_birds'] ?? 0;

        $entryOffer->update($validated);

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated.');
    }

    public function destroy(EntryOffer $entryOffer): RedirectResponse
    {
        $entryOffer->delete();

        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted.');
    }
}

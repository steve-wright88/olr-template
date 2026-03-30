<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrizeCategory;
use App\Models\PrizePosition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrizeCategoryController extends Controller
{
    public function index(): View
    {
        $categories = PrizeCategory::ordered()->with('positions')->get();

        return view('admin.prizes.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:positions,award',
        ]);

        $validated['sort_order'] = (PrizeCategory::max('sort_order') ?? 0) + 1;

        PrizeCategory::create($validated);

        return redirect()->route('admin.prizes.index')->with('success', 'Prize category added.');
    }

    public function update(Request $request, PrizeCategory $prizeCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:positions,award',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $prizeCategory->update($validated);

        return redirect()->route('admin.prizes.index')->with('success', 'Prize category updated.');
    }

    public function destroy(PrizeCategory $prizeCategory): RedirectResponse
    {
        $prizeCategory->delete();

        return redirect()->route('admin.prizes.index')->with('success', 'Prize category removed.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:prize_categories,id',
        ]);

        foreach ($validated['order'] as $index => $id) {
            PrizeCategory::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return redirect()->route('admin.prizes.index')->with('success', 'Order updated.');
    }

    public function storePosition(Request $request, PrizeCategory $prizeCategory): RedirectResponse
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
        ]);

        $validated['sort_order'] = ($prizeCategory->positions()->max('sort_order') ?? 0) + 1;

        $prizeCategory->positions()->create($validated);

        return redirect()->route('admin.prizes.index')->with('success', 'Position added.');
    }

    public function updatePosition(Request $request, PrizePosition $prizePosition): RedirectResponse
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
        ]);

        $prizePosition->update($validated);

        return redirect()->route('admin.prizes.index')->with('success', 'Position updated.');
    }

    public function destroyPosition(PrizePosition $prizePosition): RedirectResponse
    {
        $prizePosition->delete();

        return redirect()->route('admin.prizes.index')->with('success', 'Position removed.');
    }
}

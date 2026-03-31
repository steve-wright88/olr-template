<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $photos = GalleryPhoto::ordered()->get();
        $categories = GalleryPhoto::whereNotNull('category')->distinct()->pluck('category');

        return view('admin.gallery.index', compact('photos', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|max:5120',
            'category' => 'nullable|string|max:255',
        ]);

        $category = $request->input('category') ?: $request->input('new_category');

        foreach ($request->file('photos') as $file) {
            $filename = uniqid() . '_' . $file->hashName();
            $file->move(public_path('images/gallery'), $filename);

            GalleryPhoto::create([
                'path' => 'images/gallery/' . $filename,
                'category' => $category,
                'sort_order' => 0,
            ]);
        }

        $count = count($request->file('photos'));

        return back()->with('success', "{$count} photo(s) uploaded.");
    }

    public function update(Request $request, GalleryPhoto $photo): RedirectResponse
    {
        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        $photo->update($validated);

        return back()->with('success', 'Photo updated.');
    }

    public function destroy(GalleryPhoto $photo): RedirectResponse
    {
        $photo->delete();

        return back()->with('success', 'Photo removed.');
    }
}

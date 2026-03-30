<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::latest()->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'post_type' => 'required|in:news,update,livestream',
            'livestream_url' => 'nullable|url|max:500',
            'is_pinned' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        $validated['is_pinned'] = $request->boolean('is_pinned');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $request->boolean('is_published') ? now() : null;
        $validated['user_id'] = auth()->id();

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'post_type' => 'required|in:news,update,livestream',
            'livestream_url' => 'nullable|url|max:500',
            'is_pinned' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $validated['is_pinned'] = $request->boolean('is_pinned');
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->boolean('is_published') && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }
}

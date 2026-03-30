<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\EmbedService;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $posts = Post::published()->latest()->paginate(12);

        return view('site.news.index', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $embedHtml = $post->livestream_url
            ? EmbedService::embed($post->livestream_url)
            : null;

        return view('site.news.show', [
            'post' => $post,
            'embedHtml' => $embedHtml,
        ]);
    }
}

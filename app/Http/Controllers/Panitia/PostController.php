<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('panitia.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('panitia.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'excerpt' => 'nullable|string|max:255',
            'content' => 'required|string',
            'is_published' => 'required|boolean',
        ]);

        $post = new Post($validated);
        $post->user_id = Auth::id();

        if ($request->is_published) {
            $post->published_at = now();
        }

        $post->save();

        return redirect()->route('panitia.posts.index')->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function edit(Post $post)
    {
        return view('panitia.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'excerpt' => 'nullable|string|max:255',
            'content' => 'required|string',
            'is_published' => 'required|boolean',
        ]);

        if ($request->is_published && !$post->is_published) {
            $post->published_at = now();
        } elseif (!$request->is_published) {
            $post->published_at = null;
        }

        $post->update($validated);

        return redirect()->route('panitia.posts.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('panitia.posts.index')->with('success', 'Berita berhasil dihapus!');
    }
}

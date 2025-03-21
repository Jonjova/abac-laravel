<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        $this->authorize('view posts');
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $this->authorize('create posts');
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create posts');
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Post::create($request->only('title', 'content'));

        return redirect()->route('posts.index')->with('success', 'Post creado correctamente.');
    }
}

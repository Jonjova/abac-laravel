<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Post::class);
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

       
        try {
            Post::create($request->only('title', 'content'));

            return redirect()->route('posts.index')->with([
                'type' => 'success',
                'message' => 'Post creado correctamente'
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'type' => 'error',
                'message' => 'Error al crear el post'
            ]);
        }
        
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            $post->update($request->only('title', 'content'));
            return redirect()->route('posts.index')->with([
                'type' => 'success',
                'message' => 'Post creado correctamente'
            ]);
            
        } catch (\Exception $e) {
            return back()->with([
                'type' => 'error',
                'message' => 'Error al crear el post'
            ]);
        }
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        try {
            $post->delete();
            return redirect()->route('posts.index')->with([
            'type' => 'success',
            'message' => 'Post eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return back()->with([
            'type' => 'error',
            'message' => 'Error al eliminar el post'
            ]);
        }
    }
}

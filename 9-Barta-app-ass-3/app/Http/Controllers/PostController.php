<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ContentRequest;

class PostController extends Controller
{
    /**
     * Show single post
     */
    public function show(string $id){
        $post = Post::with('user:id,name,username')
            ->find($id);

        if (!$post) {
            return redirect()->route('dashboard')->with('error', 'Post not found.');
        }

        return view('posts.single', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContentRequest $request)
    {
        $validated = $request->validated();

        Post::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Post created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::with('user:id,name,username') // Load the related user
            ->find($id); // Find the post by its ID

        if (!$post) {
            return redirect()->route('dashboard')->with('error', 'Post not found.');
        }

        if (Auth::id() !== $post->user_id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to edit this post.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContentRequest $request, string $id)
    {
        $validated = $request->validated();

        $post = Post::find($id);

        if (!$post) {
            return redirect()->route('dashboard')->with('error', 'Post not found.');
        }

        if (Auth::id() !== $post->user_id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to update this post.');
        }

        $post->update([
            'content' => $validated['content'],
        ]);

        return redirect()->route('profile.show', Auth::user()->username)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return redirect()->route('dashboard')->with('error', 'Post not found.');
        }

        if ($post->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to delete this post.');
        }

        $post->delete();

        return redirect()->route('profile.show', Auth::user()->username)
            ->with('success', 'Post deleted successfully!');
    }
}

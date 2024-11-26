<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        DB::table('posts')->insert([
            'content' => $request->content,
            'user_id' => Auth::id(),  // User who created the post
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Post created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the post by ID using DB facade
        $post = DB::table('posts')->where('id', $id)->first();

        // Check if post exists
        if (!$post) {
            return redirect()->route('home')->with('error', 'Post not found.');
        }

        // Check if the authenticated user is the owner of the post
        $userId = Auth::id();
        if ($userId !== $post->user_id) {
            return redirect()->route('home')->with('error', 'You are not authorized to edit this post.');
        }

        // Pass the post data to the edit view
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

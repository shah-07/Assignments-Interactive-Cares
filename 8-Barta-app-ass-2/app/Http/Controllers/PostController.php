<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Show single post
     */
    public function show(string $id){
        $post = DB::table('posts')
            ->where('id', $id)
            ->first();

        if (!$post) {
            return redirect()->route('dashboard')->with('error', 'Post not found.');
        }

        $post->formatted_date = Carbon::parse($post->created_at)->diffForHumans();

        $user = DB::table('users')
            ->where('users.id', $post->user_id)
            ->select('users.name','users.username')
            ->first();

        return view('posts.single', compact('user', 'post'));
    }

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
        $post = DB::table('posts')->where('id', $id)->first();

        if (!$post) {
            return redirect()->route('dashboard')->with('error', 'Post not found.');
        }

        $userId = Auth::id();
        if ($userId !== $post->user_id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to edit this post.');
        }

        $user = DB::table('users')
            ->where('users.id', $post->user_id)
            ->select('users.name','users.username')
            ->first();

        return view('posts.edit', compact('user','post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        DB::table('posts')
            ->where('id', $id)
            ->update([
                'content' => $validated['content'],
                'updated_at' => now(),
            ]);

        $userName = Auth::user()->username;

        return redirect()->route('profile.show', $userName)->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = DB::table('posts', )->where('id', $id)->first();

        if (!$post) {
            return redirect()->route('dashboard' )->with('error', 'Post not found.');
        }

        if ($post->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to delete this post.');
        }

        $userName = Auth::user()->username;

        DB::table('posts')->where('id', $id)->delete();

        return redirect()->route('profile.show', $userName)->with('success', 'Post deleted successfully!');
    }
}

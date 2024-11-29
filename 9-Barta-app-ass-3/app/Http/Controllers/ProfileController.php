<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $username)
    {
        $user = User::with('profile')->where('username', $username)->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        $posts = Post::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.show', compact('user', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user()->load('profile');

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $user->name = $validated['first-name'] . ' ' . $validated['last-name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['bio' => $validated['bio'] ?? '']
        );

        return redirect()->route('profile.show', $user->username)
                         ->with('success', 'Profile updated successfully!');
    }
}

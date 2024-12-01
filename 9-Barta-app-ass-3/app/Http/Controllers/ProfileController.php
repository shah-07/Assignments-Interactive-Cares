<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $profileData = [
            'bio' => $validated['bio'] ?? '',
        ];

        if ($request->hasFile('avatar')) {
            if ($user->profile->avatar && $user->profile->avatar !== 'profile/avatar.jpg' && Storage::disk('public')->exists($user->profile->avatar)) {
                Storage::disk('public')->delete($user->profile->avatar);
            }

            $profileData['avatar'] = $request->file('avatar')->store('profile', 'public');
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('profile.show', $user->username)
                         ->with('success', 'Profile updated successfully!');
    }

    public function search(Request $request)
    {
        // Validate search input
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        // Get the search term
        $query = $request->input('query');

        // If query is not empty, perform the search
        if ($query) {
            $users = User::with('profile')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('username', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->get();
        } else {
            $users = collect(); // No users if no query
        }

        // Return the view with results
        return view('user.search', compact('users', 'query'));
    }
}

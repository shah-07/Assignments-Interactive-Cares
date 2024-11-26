<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $userId = Auth::id();

        $user = DB::table('users')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->select('users.name', 'profiles.bio')
            ->where('users.id', $userId)
            ->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $userId = Auth::id();

        $user = DB::table('users')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('users.id', $userId)
            ->select('users.name', 'users.email', 'profiles.bio')
            ->first();

        // Split the name into first and last name
        $nameParts = explode(' ', $user->name, 2);
        $user->first_name = $nameParts[0];
        $user->last_name = $nameParts[1] ?? '';

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        $validated = $request->validated();

        $updateData = [
            'name' => $validated['first-name'] . ' ' . $validated['last-name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        DB::table('users')
        ->where('id', $userId)
        ->update($updateData);

        DB::table('profiles')
        ->updateOrInsert(
            ['user_id' => $userId],
            ['bio' => $validated['bio'] ?? '']
        );

        return redirect()->route('profile.show', ['username' => $user->username])->with('success', 'Profile updated successfully!');
    }
}

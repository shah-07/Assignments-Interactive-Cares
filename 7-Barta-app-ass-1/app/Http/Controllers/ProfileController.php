<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $id = Auth::id();
        // Fetch the user data based on the ID
        $userData = DB::table('users')->where('id', $id)->first();

        if (!$userData) {
            abort(404, 'User not found');
        }

        return view('profile.edit', ['user' => $userData]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}

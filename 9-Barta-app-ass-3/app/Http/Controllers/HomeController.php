<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(404, 'User not found');
        }

        $posts = Post::with('user:id,name,username')
            ->latest()
            ->get();

        return view("dashboard", compact('user', 'posts'));
    }
}

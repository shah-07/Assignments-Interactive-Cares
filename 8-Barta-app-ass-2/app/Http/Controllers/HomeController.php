<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){

        if (!Auth::user()) {
            abort(404, 'User not found');
        }

        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.id', 'posts.content', 'posts.created_at', 'users.name', 'users.username')
            ->orderBy('posts.created_at', 'desc')
            ->get()
            ->map(function ($post) {
                $post->formatted_date = Carbon::parse($post->created_at)->diffForHumans();
                return $post;
            });

        return view("dashboard", compact( 'posts'));
    }
}

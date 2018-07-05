<?php

namespace App\Http\Controllers;

use App\Post;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Post $post)
    {
        auth()->user()->favoriteJobs()->toggle([$post->id]);
    }
}

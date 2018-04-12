<?php

namespace App\Http\Controllers;

use App\{Post, Apply};

class ApplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function save()
    {
        $postTitle = implode(' ', explode('-', request('title')));
        $post = Post::where('title', $postTitle)->where('identity', request('identity'))->firstOrFail();

        $apply = new Apply;
        $apply->user_id = auth()->id();
        $apply->post_id = $post->id;
        $apply->save();
    }
}

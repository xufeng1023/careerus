<?php

namespace App\Http\Controllers;

use App\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin'])->except(['all']);
    }

    public function all()
    {
        $posts = Post::with('company')->get();

        return view('posts', compact('posts'));
    }

    public function show(Post $post)
    {;
        $post->load('catagory');

        return view('post', compact('post'));
    }

    public function allAdmin()
    {
        $posts = Post::with('creator')->get();
        
        return view('admin.posts', compact('posts'));
    }

    public function save()
    {
        $data = request()->all();
        $data['user_id'] = auth()->id();
        $data['identity'] = str_random(50).md5(time());

        Post::create($data);

        return back();
    }

    public function update(Post $post)
    {
        $post->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }
}

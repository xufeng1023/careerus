<?php

namespace App\Http\Controllers;

use App\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin'])->except(['all', 'show']);
    }

    public function all()
    {
        if(!request('s') && !request('l')) {
            return back();
        }

        $query = Post::with('company');

        if(request('s') && request('l')) {
            $query = $query->where('title', 'LIKE', '%'.request('s').'%')
                            ->where('location', 'LIKE', '%'.request('l').'%');
        }

        if(request('s') && !request('l')) {
            $query = $query->where('title', 'LIKE', '%'.request('s').'%');
        }

        if(!request('s') && request('l')) {
            $query = $query->where('location', 'LIKE', '%'.request('l').'%');
        }

        $posts = $query->get();

        return view('posts', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load('catagory');

        return view('post', compact('post'));
    }

    public function allAdmin()
    {
        if(request('id')) $posts[] = Post::find(request('id'));
        else $posts = Post::with('creator')->get();
        
        return view('admin.posts', compact('posts'));
    }

    public function save()
    {
        $data = request()->all();
        $data['user_id'] = auth()->id();

        if(app()->environment() !== 'testing') {
            $data['identity'] = str_random(50).md5(time());
        }

        Post::create($data);

        return back();
    }

    public function update(Post $post)
    {
        $post->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }
}

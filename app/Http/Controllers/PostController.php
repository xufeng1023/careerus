<?php

namespace App\Http\Controllers;

use App\{Post, Catagory};

class PostController extends Controller
{
    public function index()
    {
        $categories = Post::all()->unique('catagory_id')->pluck('catagory.name');

        return view('welcome', compact('categories'));
    }

    public function all()
    {
        if(!request('s') && !request('l') && !request('ct')) {
            return back();
        }

        $query = Post::with('company.visaJobs');

        if(request('s')) {
            $query = $query->where('title', 'LIKE', '%'.request('s').'%');
        }

        if(request('l')) {
            $query = $query->where('location', 'LIKE', '%'.request('l').'%');
        }

        if(request('ct')) {
            $category = Catagory::where('name', request('ct'))->first();

            if($category) $query = $query->where('catagory_id', $category->id);
        }

        $posts = $query->get();

        return view('posts', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load([
            'catagory', 
            'company.visaJobs' => function($query) {
                $query->orderBy('year', 'asc');
            }
        ]);

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

    public function search()
    {
        $posts = Post::where('location', 'LIKE', '%'.request('s').'%')->take(5)->get();
        
        return $posts->pluck('location');
    }
}
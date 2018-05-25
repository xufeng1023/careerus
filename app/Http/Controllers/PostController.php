<?php

namespace App\Http\Controllers;

use App\{Post, Catagory, Tag};

class PostController extends Controller
{
    public function index()
    {
        $categories = Post::all()->unique('catagory_id')->pluck('catagory.name');

        return view('welcome', compact('categories'));
    }

    public function all()
    {
        $filtered = array_where(request()->all(), function ($value, $key) {
            return !is_null($value);
        });

        if(!$filtered) return redirect('/');

        $query = Post::with('company.visaJobs');

        if(request('tp')) {
            $query = $query->where('job_type', request('tp'));
        }

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

        if(request('t')) {
            $tag = Tag::where('name', request('t'))->first();
            if($tag) {
                $query = $query->whereExists(function($q) use($tag) {
                    $q->select('post_id')->from('post_tag')->where('tag_id', $tag->id)->whereRaw('post_tag.post_id = posts.id');
                });
            }
        }

        $posts = $query->get();

        $usedTags = Tag::whereExists(function($q) {
            $q->select('tag_id')->from('post_tag')->whereRaw('post_tag.tag_id = tags.id');
        })->get();

        $categories = Post::all()->unique('catagory_id')->pluck('catagory.name');

        $locations = Post::all()->unique('location')->pluck('location');

        $types = ['Full-time', 'Part-time', 'Internship'];

        return view('posts', compact('posts', 'usedTags', 'categories', 'locations', 'types'));
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
        if(request('id')) $posts[] = Post::find(request('id'))->load('tags');
        else $posts = Post::with('creator')->latest()->get();

        $tags = Tag::all();
        
        return view('admin.posts', compact('posts', 'tags'));
    }

    public function save()
    {
        $category = Catagory::findOrFail(request('catagory_id'));

        $data = request()->all();
        $data['user_id'] = auth()->id();
        $data['sponsor_odds'] = ($category->rfe + 45 + request('sponsor_odds') + rand(70, 100)) * 0.25;

        if(app()->environment() !== 'testing') {
            $data['identity'] = str_random(50).md5(time());
        }

        $post = Post::create($data);

        if(request('tags')) {
            $post->tags()->attach(request('tags'));
        }

        return back();
    }

    public function update(Post $post)
    {
        $post->update(request()->all());

        $post->tags()->sync(request('tags'));

        return back()->with('updated', trans('admin.updated'));
    }

    public function search()
    {
        $posts = Post::where('location', 'LIKE', '%'.request('s').'%')->take(5)->get();
        
        return $posts->pluck('location');
    }
}
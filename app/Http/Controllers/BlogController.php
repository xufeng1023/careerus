<?php

namespace App\Http\Controllers;

use App\Blog;

class BlogController extends Controller
{
    public function all()
    {
        $blogs = Blog::latest()->get();

        return view('blogs', compact('blogs'));
    }

    public function allAdmin()
    {
        if(request('id')) $blogs[] = Blog::whereTitle(request('id'))->first();
        else $blogs = Blog::latest()->get();

        return view('admin.blog', compact('blogs'));
    }

    public function save()
    {
        Blog::create([
            'user_id' => auth()->id(),
            'title' => preg_replace('/\//', '', request('title')),
            'content' => request('content'),
            'description' => request('description')
        ]);

        return '/admin/blog';
    }

    public function show(Blog $blog)
    {
        return view('blog', compact('blog'));
    }

    public function update(Blog $blog)
    {
        $blog->update(request()->all());
    }
}

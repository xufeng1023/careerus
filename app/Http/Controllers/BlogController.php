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
        $blogs = Blog::latest()->get();

        return view('admin.blog', compact('blogs'));
    }

    public function save()
    {
        Blog::create([
            'user_id' => auth()->id(),
            'title' => request('title'),
            'content' => request('content')
        ]);

        return '/admin/blog';
    }

    public function update(Blog $blog)
    {
        $blog->update(request()->all());
    }
}

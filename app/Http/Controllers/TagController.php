<?php

namespace App\Http\Controllers;

use App\Tag;

class TagController extends Controller
{
    public function allAdmin()
    {
        if(request('id')) $tags[] = Tag::find(request('id'));
        else $tags = Tag::orderBy('id', 'desc')->get();

        return view('admin.tags', compact('tags'));
    }

    public function save()
    {
        $tag = Tag::create(request()->all());

        if(request()->ajax()) {
            return ['msg' => trans('admin.updated'), 'data' => $tag];
        }

        return back();
    }

    public function update(Tag $tag)
    {
        $tag->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }

    public function delete(Tag $tag)
    {
        $tag->remove();
    }
}

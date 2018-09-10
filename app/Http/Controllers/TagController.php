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

    public function all()
    {
        return Tag::orderBy('id', 'desc')->get()->random(50)->pluck('name');
    }

    public function save()
    {
        try {
            $tag = Tag::create(request()->all());
        } catch(\Illuminate\Database\QueryException $e) {
            return response('重复的标签', 500);
        }
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

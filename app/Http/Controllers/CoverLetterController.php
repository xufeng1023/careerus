<?php

namespace App\Http\Controllers;

use App\CoverLetter;

class CoverLetterController extends Controller
{
    public function all()
    {
        if(request('id')) $templates[] = CoverLetter::find(request('id'));
        else $templates = CoverLetter::orderBy('id', 'desc')->get();

        return view('admin.coverLetter', compact('templates'));
    }

    public function save()
    {
        CoverLetter::create(request()->all());

        return back();
    }

    public function update(CoverLetter $coverLetter)
    {
        $coverLetter->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }
}

<?php

namespace App\Http\Controllers;

use App\{Post, Apply};

class ApplyController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function form()
    {
        return view('apply');
    }

    public function save()
    {
        (new Apply)->apply(
            (new Post)->findPost()
        );
    }
}

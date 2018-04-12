<?php

namespace App\Http\Controllers;

use App\Catagory;

class CatagoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function allAdmin()
    {
        $catagories = Catagory::all();

        return view('admin.catagory', compact('catagories'));
    }

    public function save()
    {
        Catagory::create(request()->all());

        return back();
    }
}

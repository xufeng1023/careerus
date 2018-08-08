<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function update()
    {
        $data = request()->all();

        unset($data['_token']);
        
        foreach($data as $key => $val) {
            \Cache::forever($key, $val);
        }
        
        return back()->with('updated', trans('admin.updated'));
    }
}

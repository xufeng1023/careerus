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
    
        $path = base_path('.env');

        if (!file_exists($path)) return response([], 500);

        $content = file_get_contents($path);

        foreach(request()->all() as $key => $val) {
            $key = strtoupper($key);
            $content = str_replace($key.'='.env($key), $key.'='.$val, $content);
        }

        file_put_contents($path, $content);

        return back()->with('updated', trans('admin.updated'));
    }
}

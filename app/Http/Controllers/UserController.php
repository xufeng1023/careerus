<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function all()
    {
        if(request('id')) $users[] = User::find(request('id'));
        else $users = User::all();

        return view('admin.users', compact('users'));
    }

    public function save()
    {
        User::create(request()->all());

        return back();
    }

    public function update(User $user)
    {
        $user->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }
}

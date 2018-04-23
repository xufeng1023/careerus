<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
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

    public function applies()
    {
        $applies = auth()->user()->applies()->orderBy('created_at','desc')->get();

        $applies->load('post');

        return view('dashboard.applies', compact('applies'));
    }

    public function account()
    {
        return view('dashboard.account');
    }

    public function payment()
    {
        return view('dashboard.payment');
    }

    public function accountUpdate()
    {
        request()->validate([
            'name' => 'required|regex:/^[a-zA-Z]{1}[a-zA-Z ]*$/|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id().',id',
            'phone' => 'required|regex:/^[1-9]{1}[0-9]{9}$/|unique:users,phone,'.auth()->id().',id'
        ]);

        auth()->user()->update(request()->all());

        return [trans('front.updated ok')];
    }

    public function passwordUpdate()
    {
        request()->validate([
            'oldPass' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $credentials = ['email' => auth()->user()->email, 'password' => request('oldPass')];

        if(!\Auth::once($credentials)) {
            return response(['errors' => ['oldPass' => trans('front.old pass bad')]], 422);
        }

        auth()->user()->update(['password' => \Hash::make(request('password'))]);

        return [trans('front.updated ok')];
    }

    public function resumeUpdate()
    {
        request()->validate([
            'resume' => 'required|file|mimes:doc,docx,txt,pdf,rtf'
        ]);

        auth()->user()->update(['resume' => request('resume')->store('resumes')]);

        return [trans('front.updated ok')];
    }
}

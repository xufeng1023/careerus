<?php

namespace App\Http\Controllers\Auth;

use App\{User, Post, Apply};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|regex:/^[a-zA-Z]{1}[a-zA-Z ]*$/|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|regex:/^[1-9]{1}[0-9]{9}$/|unique:users',
            'resume' => 'required|file|mimes:doc,docx,txt,pdf,rtf',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'resume' => request()->resume->store('resumes'),
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {
        if($request->job && $request->identity) {
            $post = (new Post)->findPost($request->job, $request->identity);

            (new Apply)->apply($post);

            event(new \App\Events\StudentAppliedEvent($post));

            return '/dashboard/applies';
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        $userInfo = Socialite::driver('google')->user();
        dd($userInfo);
        $user = User::updateOrCreate(
            [
                'email' => $userInfo->email
            ],
            [
                'name' => $userInfo->name,
                'confirmed' => 1,
                'login_provider' => 'google'
            ]
        );

        \Auth::login($user, true);

        return redirect('/dashboard/account');
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     if($request->title && $request->identity) {
            
    //     }
    // }
}

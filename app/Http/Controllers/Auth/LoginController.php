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

    protected $redirectTo;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->redirectTo = url()->previous();
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')
            //->with(['redirect_to' => url()->previous()])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $userInfo = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            [
                'email' => $userInfo->email
            ],
            [
                'name' => explode('@', $userInfo->name)[0],
                'confirmed' => 1,
                'login_provider' => 'google'
            ]
        );

        \Auth::login($user, true);

        return redirect('/dashboard/account');
    }

    protected function authenticated(Request $request, $user)
    {
        if($request->ajax()) {
            return view('_applyForm', compact('user'));
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $userSocial = Socialite::driver('facebook')->user();

        $find_old_user = User::where('email' , $userSocial->email)->first();
        if ($find_old_user) {
            Auth::login($find_old_user);
            return redirect()->route('dashboard');
        }else{
            $user = new User();
            $user->name = $userSocial->name;
            $user->email = $userSocial->email;
            $user->username = "Facebook_".$userSocial->id;
            $user->password = bcrypt(123456);
            $user->save();
            Auth::login($user);
            return redirect()->route('account')->with(['message' => 'Successfully Login With Facebook but Please Change Your Username and Please Change Your Password From Default 123456']);
        }
    }
}

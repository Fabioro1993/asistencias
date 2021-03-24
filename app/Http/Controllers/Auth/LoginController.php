<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function showLoginForm()
    // {
    //     // Get URLs
    //     $urlPrevious = url()->previous();
    //     $urlBase = url()->to('/');
    //     // Set the previous url that we came from to redirect to after successful login but only if is internal
    //     session()->put('url.intended', $urlBase);
    //     return view('auth.login');
    // }

    // Login
    /**public function showLoginForm()
    {
       $pageConfigs = ['bodyCustomClass' => 'login-bg', 'isCustomizer' => false];
  
        return view('/auth/login', [
            'pageConfigs' => $pageConfigs
        ]);
    }*/
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function logout(Request $request)
    // {
    //     $this->guard()->logout();

    //     $request->session()->invalidate();

    //     return $this->loggedOut($request) ?: redirect('/login');
    // }
}

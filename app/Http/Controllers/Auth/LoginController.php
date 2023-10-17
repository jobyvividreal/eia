<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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

    // Custom log in function
    public function login(Request $request)
    {   
        $input = $request->all();
   
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        $remember_me = $request->has('remember_me') ? true : false;
   
        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), $remember_me)) {
                
                if (! auth()->user()->status == 1) {
                    Auth::logout();
                    return redirect()->route('login')->with('error','Account is not active, Please try again later or contact support.');
                }

                if (auth()->user()->is_admin == 1) {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->route('dashboard');
                }
        } else {
            return redirect()->route('login')->with('error','The Username or Password is incorrect.');
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Traits\CustomizeAuthenticatesUsers;
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

    use AuthenticatesUsers, CustomizeAuthenticatesUsers {
        CustomizeAuthenticatesUsers::showLoginForm insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */


    public function redirectTo()
    {
        $request = request();
        $user = Auth::user();
        $role = $user->roles[0]->name;

        if($role == 'Client' && auth()->user()->status == 0){
            Auth::logout();
            session()->flash('error', 'Your account is not active. Please contact the admin.');
            return route('login');
        }
        // dd($role);

        switch ($role) {
            case 'Admin':
                return '/dashboard';
                break;
            case 'Manager':
                return '/dashboard';
                break;
            case 'Cashier':
                return '/dashboard';
                break;
            case 'Client':
                // if ($request->session()->get('url.intended') != route('login')) {
                //     $prev_url = $request->session()->get('_previous.url');
                //     $request->session()->put('url.intended', $prev_url);
                //     // return '/dashboard';
                // }
                return '/';
                break;
            default:
                return '/';
                break;
        }
    }
    // protected $redirectTo = '/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}

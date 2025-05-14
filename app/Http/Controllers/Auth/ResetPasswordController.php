<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Traits\CustomizeAuthenticatesUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords, CustomizeAuthenticatesUsers {
        CustomizeAuthenticatesUsers::showResetForm insteadof ResetsPasswords;
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectTo()
    {
        $user = Auth::user();
        $role = $user->roles[0]->name;

        switch ($role) {
            case 'Admin':
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
}

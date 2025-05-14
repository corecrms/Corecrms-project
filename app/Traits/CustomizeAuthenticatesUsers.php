<?php
// App\Traits\CustomizesLogin.php

namespace App\Traits;

use Illuminate\Http\Request;

trait CustomizeAuthenticatesUsers
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('back.auth.login');
    }

    public function showRegistrationForm()
    {
        return view('back.auth.register');
    }

    public function showLinkRequestForm()
    {

        return view('back.auth.passwords.email');
        // return "Hello";
    }

    public function showResetForm(Request $request){
        $token = $request->route()->parameter('token');

        return view('back.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}

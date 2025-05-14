<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use App\Providers\RouteServiceProvider;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Traits\CustomizeAuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, CustomizeAuthenticatesUsers {
        CustomizeAuthenticatesUsers::showRegistrationForm insteadof RegistersUsers;
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

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


        switch ($role) {
            case 'Admin':
                return '/dashboard';
                break;
            case 'Client':
                if ($request->session()->get('url.intended') != route('login')) {
                    $prev_url = $request->session()->get('_previous.url');
                    $request->session()->put('url.intended', $prev_url);
                    // return '/dashboard';
                }
                return '/';
                break;
            default:
                return '/';
                break;
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    // protected function create(array $data)
    // {
    //     return User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //     ]);
    // }

    protected function create(array $data)
    {

        $user = new User();
        $user->name = $data['first_name'].' '.$data['last_name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->status = 0;

        if ($user->save()) {

            // if(!$user->hasRole('Customer')){
            //     $user->assignRole('Admin');
            // }
            Customer::create([
                'user_id' => $user->id,
            ]);
            $user->assignRole('Client');
            return $user;
        }

        return redirect('/register')->with('error', 'Something went wrong.');
    }
}

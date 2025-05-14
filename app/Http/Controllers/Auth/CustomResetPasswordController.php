<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class CustomResetPasswordController extends Controller
{
    
    public function reset(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return redirect()->route('profile')->with('error', 'Email not found.');
        }

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return redirect()->route('profile')->with('success', 'Password reset successfully.');
    }
}

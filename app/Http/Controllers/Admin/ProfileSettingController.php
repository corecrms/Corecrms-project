<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileSettingController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image',
        ]);

        $user = auth()->user();

        // check if image exists
        if ($request->hasFile('image') && $user->image) {
            // delete image
            Storage::disk('public')->delete($user->image);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName() . '-' . time() . '.' . $image->getClientOriginalExtension();
            $data['image'] = 'users/' . $user->id . '/' . $filename;

            Storage::disk('public')->put($data['image'], file_get_contents($image));
        }

        $data['image'] = 'storage/' . $data['image'];

        $updated = $user->update($data);

        if (!$updated) {
            return redirect()->back()->with('error', 'Profile settings failed to update!');
        }

        return redirect()->route('profile.index')->with('success', 'Profile settings updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $user = auth()->user();

        if($request->hasFile('img'))
        {
            $file = $request->file('img');
            $filename = "profileImg".rand(1111,9999).'.'.$file->extension();
            $file->storeAs('public/images/profile',$filename);

            if (!Hash::check($data['current_password'], $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect!');
            }
    
            $updated = $user->update([
                'password' => Hash::make($data['password']),
                'image' =>  "/images/profile/".$filename,
            ]);
    
            if (!$updated) {
                return redirect()->back()->with('error', 'Password failed to update!');
            }
    
            return redirect()->route('profile.index')->with('success', 'Password updated successfully.');
        }
        else{
            if (!Hash::check($data['current_password'], $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect!');
            }
    
            $updated = $user->update([
                'password' => Hash::make($data['password']),
            ]);
    
            if (!$updated) {
                return redirect()->back()->with('error', 'Password failed to update!');
            }
    
            return redirect()->route('profile.index')->with('success', 'Password updated successfully.');
        }
    }
}

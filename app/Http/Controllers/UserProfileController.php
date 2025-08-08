<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function show()
    {
        $religions = Religion::all();
        return view('pages.user-profile',compact('religions'));
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['max:100'],
            // 'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore(auth()->user()->id)],
            // 'gender' => ['required', Rule::in(['male', 'female', 'others'])],
            // 'religion_id' => ['required', 'exists:religions,id'],
            'dob' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max file size 2MB
            'icnumber' => ['nullable', 'max:20'],
            'country' => ['nullable', 'max:200'],
            'state' => ['nullable', 'max:200'],
            'city' => ['nullable', 'max:200'],
            'postcode' => ['nullable', 'max:200'],
            'contact' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:500'],
        ]);
        
        auth()->user()->update([
            'name' => $attributes['name'],
            // 'email' => $attributes['email'],
            // 'gender' => $attributes['gender'],
            // 'religion_id' => $attributes['religion_id'],
            'dob' => $attributes['dob'],
            'icnumber' => $attributes['icnumber'],
            'country' => $attributes['country'] ?? '',
            'state' => $attributes['state'] ?? '',
            'city' => $attributes['city'] ?? '',
            'postcode' => $attributes['postcode'] ?? '',
            'contact' => $attributes['contact'] ?? '',
            'address' => $attributes['address'] ?? '',
            'role' => auth()->user()->role,
        ]);
        
        if (auth()->user()->role === 'o') {
            $organizerAttributes = [];
        
            if ($request->hasFile('photo')) {
                // Delete the old photo if it exists
                if (auth()->user()->organizer->photo) {
                    Storage::delete(auth()->user()->organizer->photo);
                }
                
                // Store the new photo
                $organizerAttributes['photo'] = $request->file('photo')->store('organizer', 'public');
            }

            if ($request->hasFile('photo')) {
                // Delete the old organizer photo if it exists
                if (auth()->user()->organizer->photo) {
                    Storage::delete(auth()->user()->organizer->photo);
                }
        
                // Store the new organizer photo
            }
            // $organizerAttributes['photo'] = $attributes['photo'] ?? auth()->user()->organizer->photo;
            $organizerAttributes['about'] = $attributes['about'];
            $organizerAttributes['address'] = $attributes['address'];
            
            auth()->user()->organizer->update($organizerAttributes);
        }
        return redirect()->back()->with('succes', 'Profile succesfully updated');
    }
    
    public function changePassword(Request $request){
        $request->validate([
            'old_password' => 'required|min:4|max:64',
            'password' => 'required|min:4|max:64',
        ]);

        $user = Auth::user();

        if(!Hash::check($request->old_password, $user->password)){
            return redirect()->back()->with('error', "Old password does not match.");
        }

        $user->password = Hash::make($request->password);
        // $user->password = $request->password;
        $user->save();

        return redirect()->back()->with('succes', 'Password changed successfully');
    }
}

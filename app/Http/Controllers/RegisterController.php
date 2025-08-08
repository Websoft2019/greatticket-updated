<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;

use App\Models\Religion;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        abort(404);
        $religions = Religion::all();
        return view('auth.register',compact('religions'));
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|max:255',
            // 'gender' => 'required|in:male,female,others',
            // 'dob' => 'required|date|before_or_equal:' . Carbon::today()->toDateString(),
            // 'religion_id' => 'required|exists:religions,id',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255',
            'contact' => 'nullable|min:10|max:15',
        ]);

        // Hash the password before saving
        $attributes['password'] = Hash::make($attributes['password']);

        // Create the user and log them in
        $user = User::create($attributes);
        auth()->login($user);

        // Redirect to returnurl if provided
        if ($request->filled('returnurl')) {
            return redirect()->to($request->get('returnurl'));
        }

        return redirect()->route('getHome');
    }
}

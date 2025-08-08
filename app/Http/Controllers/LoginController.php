<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    public function showOrganizer()
    {
        return view('auth.organizer-login');
    }

    public function showAdmin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            $role = auth()->user()->role;
    
            if ($role === 'u') {
                if ($request->filled('returnurl')) {
                    return redirect()->to($request->get('returnurl')); // ✅ fixed this line
                } else {
                    return redirect()->route('getHome');
                }
            }
    
            // If not 'u', log out and deny access
            Auth::logout();
            session()->flash('error', 'Access denied: You do not have permission to log in as a user.');
            return redirect()->back();
        }
    
        // If credentials are wrong
        session()->flash('error', 'Invalid email or password.');
        return redirect()->back();
    }
    

    public function organizerLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            if ($user->role === 'o') {
                $request->session()->regenerate();
                return redirect()->route('organizer.dashboard');
            }

            Auth::logout(); // Log out the user if they don't have the correct role
            session()->flash('error', 'Access denied: You do not have permission to log in as an organizer.');
            return redirect()->back();
        }

        session()->flash('error', 'Invalid email or password.');
        return redirect()->back();
    }


    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $role = auth()->user()->role;
            if ($role == 'a') {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }
            Auth::logout(); // Log out the user if they don't have the correct role
            session()->flash('error', 'Access denied: You do not have permission to log in as an admin.');
            return redirect()->back();
        }

        session()->flash('error', 'Invalid email or password.');
        return redirect()->back();
    }

    public function guest()
    {
        try {
            $session_id = Session::get('id') ?? Str::uuid();
            $email = Str::uuid() . ".greatticket@gmail.com";
            $password = Str::random(16);
            $religion = Religion::firstOrFail();
            $user = User::create([
                'session_id' => $session_id,
                'name' => 'Guest User',
                'email' => $email,
                'password' => $password,
                'religion_id' => $religion->id,
            ]);
            Auth::login($user);
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

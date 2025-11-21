<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        // Redirect to dashboard if already authenticated
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        // Redirect to dashboard if already authenticated
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Clone default/global categories for the new user so they have their own editable set.
        try {
            \App\Models\Category::whereNull('user_id')->get()->each(function ($cat) use ($user) {
                \App\Models\Category::create([
                    'user_id' => $user->id,
                    'name' => $cat->name,
                    'type' => $cat->type,
                    'icon' => $cat->icon,
                    'color' => $cat->color,
                ]);
            });
        } catch (\Throwable $e) {
            // Non-fatal: if cloning fails, the app will still work because global categories exist.
        }

        return redirect()->route('login')->with('success', 'Registration successful! Please log in with your credentials.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}

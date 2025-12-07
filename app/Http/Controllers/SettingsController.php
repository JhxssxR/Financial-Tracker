<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        return view('settings');
    }

    /**
     * Update user currency preference
     */
    public function updateCurrency(Request $request)
    {
        $currencies = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CAD' => '$',
            'AUD' => '$',
            'CHF' => 'Fr',
            'CNY' => '¥',
            'INR' => '₹',
            'PHP' => '₱',
        ];

        $request->validate([
            'currency_code' => 'required|in:' . implode(',', array_keys($currencies)),
        ]);

        $currencyCode = $request->currency_code;
        $currencySymbol = $currencies[$currencyCode];

        $user = Auth::user();
        
        // Update the user in the database
        $user->currency_code = $currencyCode;
        $user->currency_symbol = $currencySymbol;
        $user->save();

        // Force refresh the user instance in the session
        Auth::setUser($user->fresh());
        
        // Also regenerate the session to ensure changes are persisted
        $request->session()->put('_flash.old', []);
        $request->session()->regenerate(false);

        return response()->json([
            'success' => true,
            'currency_code' => $currencyCode,
            'currency_symbol' => $currencySymbol,
        ]);
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.'
        ]);
    }

    /**
     * Update user email
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'required',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.'
            ], 400);
        }

        // Update email
        $user->email = $request->new_email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Email updated successfully.'
        ]);
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Delete all user related data
        $user->transactions()->delete();
        $user->budgets()->delete();
        $user->savings()->delete();
        $user->notifications()->delete();
        $user->categories()->delete();

        // Logout user
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Delete user account
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.'
        ]);
    }
}


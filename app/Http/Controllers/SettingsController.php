<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}


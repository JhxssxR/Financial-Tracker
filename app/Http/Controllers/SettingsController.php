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

        Auth::user()->update([
            'currency_code' => $currencyCode,
            'currency_symbol' => $currencySymbol,
        ]);

        return response()->json([
            'success' => true,
            'currency_code' => $currencyCode,
            'currency_symbol' => $currencySymbol,
        ]);
    }
}


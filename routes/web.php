<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;

// Authentication routes (for guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout route (for authenticated users only)
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Redirect root to dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // App sections
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/savings', [SavingsController::class, 'index'])->name('savings.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/currency', [SettingsController::class, 'updateCurrency'])->name('settings.currency');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    
    // Debug route
    Route::get('/test-currency', function() {
        $user = auth()->user();
        return response()->json([
            'email' => $user->email,
            'currency_code' => $user->currency_code,
            'currency_symbol' => $user->currency_symbol,
            'helper_symbol' => currency_symbol(),
            'helper_code' => currency_code(),
        ]);
    });
    
    // Debug route for reports data
    Route::get('/debug-reports', function() {
        $user = auth()->user()->fresh();
        return response()->json([
            'user_currency_symbol' => $user->currency_symbol,
            'user_currency_code' => $user->currency_code,
            'helper_currency_symbol' => currency_symbol(),
            'helper_currency_code' => currency_code(),
            'formatted_zero' => format_currency(0),
            'formatted_15000' => format_currency(15000),
        ]);
    });
});

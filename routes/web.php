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
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/savings', [SavingsController::class, 'index'])->name('savings.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
});

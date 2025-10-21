<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\ReportController;

// Redirect root to dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// App sections
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
Route::get('/savings', [SavingsController::class, 'index'])->name('savings.index');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Auth pages (UI only)
Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register');

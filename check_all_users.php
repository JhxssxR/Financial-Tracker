<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Transaction;
use App\Models\User;

echo "All Users:\n";
echo "==========\n";
$users = User::all();
foreach ($users as $user) {
    $income = Transaction::where('user_id', $user->id)
        ->where('type', 'income')
        ->whereMonth('transaction_date', now()->month)
        ->whereYear('transaction_date', now()->year)
        ->sum('amount');
    
    $expenses = Transaction::where('user_id', $user->id)
        ->where('type', 'expense')
        ->whereMonth('transaction_date', now()->month)
        ->whereYear('transaction_date', now()->year)
        ->sum('amount');
    
    echo "User: {$user->email}\n";
    echo "  Income (Oct 2025): " . format_currency($income) . "\n";
    echo "  Expenses (Oct 2025): " . format_currency($expenses) . "\n";
    echo "  Currency: {$user->currency_code} ({$user->currency_symbol})\n\n";
}

// Show all transactions
echo "\nAll Transactions:\n";
echo "=================\n";
$transactions = Transaction::with('user')->orderBy('transaction_date', 'desc')->get();
foreach ($transactions as $txn) {
    echo "{$txn->transaction_date->format('Y-m-d')} | {$txn->user->email} | {$txn->type} | " . format_currency($txn->amount) . " | {$txn->description}\n";
}

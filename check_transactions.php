<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Transaction;

echo "Current date: " . now() . "\n";
echo "Current month: " . now()->month . "\n";
echo "Current year: " . now()->year . "\n\n";

$txn = Transaction::latest()->first();
if ($txn) {
    echo "Latest transaction:\n";
    echo "  Date: " . $txn->transaction_date . "\n";
    echo "  Month: " . $txn->transaction_date->month . "\n";
    echo "  Year: " . $txn->transaction_date->year . "\n";
    echo "  Type: " . $txn->type . "\n";
    echo "  Amount: " . $txn->amount . "\n";
}

// Check current month transactions
$user = App\Models\User::first();
$currentMonthIncome = Transaction::where('user_id', $user->id)
    ->where('type', 'income')
    ->whereMonth('transaction_date', now()->month)
    ->whereYear('transaction_date', now()->year)
    ->sum('amount');

echo "\nCurrent month income for {$user->email}: " . format_currency($currentMonthIncome) . "\n";

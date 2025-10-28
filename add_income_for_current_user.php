<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;

// Get the jhasser1 user (the one you're logged in as)
$user = User::where('email', 'jhasser1@email.com')->first();

if (!$user) {
    echo "❌ User not found\n";
    exit(1);
}

echo "Adding income transaction for: {$user->email}\n";
echo "Currency: {$user->currency_code} ({$user->currency_symbol})\n\n";

// Get an income category
$category = Category::where('type', 'income')->first();

// Create an income transaction for October 2025
$transaction = Transaction::create([
    'user_id' => $user->id,
    'category_id' => $category->id,
    'type' => 'income',
    'description' => 'Freelance Project',
    'amount' => 3500.00,
    'transaction_date' => '2025-10-25',
]);

echo "✅ Created transaction:\n";
echo "  Type: {$transaction->type}\n";
echo "  Description: {$transaction->description}\n";
echo "  Amount: " . format_currency($transaction->amount) . "\n";
echo "  Date: {$transaction->transaction_date->format('Y-m-d')}\n\n";

// Calculate new totals
$totalIncome = Transaction::where('user_id', $user->id)
    ->where('type', 'income')
    ->whereMonth('transaction_date', now()->month)
    ->whereYear('transaction_date', now()->year)
    ->sum('amount');

$totalExpenses = Transaction::where('user_id', $user->id)
    ->where('type', 'expense')
    ->whereMonth('transaction_date', now()->month)
    ->whereYear('transaction_date', now()->year)
    ->sum('amount');

echo "📊 Your Dashboard Summary (October 2025):\n";
echo "  Income: " . format_currency($totalIncome) . "\n";
echo "  Expenses: " . format_currency($totalExpenses) . "\n";
echo "  Net: " . format_currency($totalIncome - $totalExpenses) . "\n\n";

echo "✅ Now refresh your dashboard to see the updated income: " . format_currency($totalIncome) . "\n";

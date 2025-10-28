<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;

// Get first user
$user = User::first();

if (!$user) {
    echo "❌ No user found. Please create a user first.\n";
    exit(1);
}

echo "Testing transaction system for user: {$user->email}\n\n";

// Get some categories
$incomeCategory = Category::where('type', 'income')->first();
$expenseCategory = Category::where('type', 'expense')->first();

if (!$incomeCategory || !$expenseCategory) {
    echo "❌ Categories not found. Running seeder...\n";
    Artisan::call('db:seed', ['--class' => 'CategorySeeder']);
    $incomeCategory = Category::where('type', 'income')->first();
    $expenseCategory = Category::where('type', 'expense')->first();
}

// Create test transactions
echo "Creating test transactions...\n";

$income = Transaction::create([
    'user_id' => $user->id,
    'category_id' => $incomeCategory->id,
    'type' => 'income',
    'description' => 'Monthly Salary',
    'amount' => 5000.00,
    'transaction_date' => now(),
]);

$expense = Transaction::create([
    'user_id' => $user->id,
    'category_id' => $expenseCategory->id,
    'type' => 'expense',
    'description' => 'Grocery Shopping',
    'amount' => 150.50,
    'transaction_date' => now(),
]);

echo "✅ Created income transaction: {$income->description} - " . currency_symbol() . number_format($income->amount, 2) . "\n";
echo "✅ Created expense transaction: {$expense->description} - " . currency_symbol() . number_format($expense->amount, 2) . "\n\n";

// Show totals
$totalIncome = Transaction::where('user_id', $user->id)->where('type', 'income')->sum('amount');
$totalExpenses = Transaction::where('user_id', $user->id)->where('type', 'expense')->sum('amount');
$totalTransactions = Transaction::where('user_id', $user->id)->count();

echo "📊 Summary:\n";
echo "Total Transactions: {$totalTransactions}\n";
echo "Total Income: " . format_currency($totalIncome) . "\n";
echo "Total Expenses: " . format_currency($totalExpenses) . "\n";
echo "Net: " . format_currency($totalIncome - $totalExpenses) . "\n\n";

echo "✅ All systems working! Go to /transactions to see your transactions.\n";

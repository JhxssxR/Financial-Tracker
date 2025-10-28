<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Login as first user
$user = App\Models\User::first();
Auth::login($user);

echo "Testing Currency Helpers:\n";
echo "========================\n";
echo "User: {$user->email}\n";
echo "User currency_code from DB: {$user->currency_code}\n";
echo "User currency_symbol from DB: {$user->currency_symbol}\n";
echo "\n";
echo "currency_code() helper: " . currency_code() . "\n";
echo "currency_symbol() helper: " . currency_symbol() . "\n";
echo "format_currency(1234.56): " . format_currency(1234.56) . "\n";
echo "format_currency(0): " . format_currency(0) . "\n";

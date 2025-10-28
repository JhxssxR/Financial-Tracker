<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update all users to PHP currency
DB::table('users')->update([
    'currency_code' => 'PHP',
    'currency_symbol' => '₱'
]);

echo "✅ All users updated to Philippine Peso (₱)\n";

// Verify
$users = DB::table('users')->select('id', 'email', 'currency_code', 'currency_symbol')->get();
foreach ($users as $user) {
    echo "User {$user->id}: {$user->email} - {$user->currency_code} {$user->currency_symbol}\n";
}

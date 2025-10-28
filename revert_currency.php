<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

DB::table('users')->update(['currency_code' => 'EUR', 'currency_symbol' => '€']);

echo "Reverted all users to EUR (€)\n";
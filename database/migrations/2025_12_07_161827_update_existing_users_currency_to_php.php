<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing users who have EUR as their default currency to PHP
        DB::table('users')
            ->where('currency_code', 'EUR')
            ->update([
                'currency_code' => 'PHP',
                'currency_symbol' => '₱'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to EUR if needed
        DB::table('users')
            ->where('currency_code', 'PHP')
            ->update([
                'currency_code' => 'EUR',
                'currency_symbol' => '€'
            ]);
    }
};

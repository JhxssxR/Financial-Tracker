<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Income Categories
            ['name' => 'Salary', 'type' => 'income', 'icon' => '💼', 'color' => '#10b981'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '💻', 'color' => '#3b82f6'],
            ['name' => 'Investment', 'type' => 'income', 'icon' => '📈', 'color' => '#8b5cf6'],
            ['name' => 'Other Income', 'type' => 'income', 'icon' => '💰', 'color' => '#14b8a6'],

            // Expense Categories
            ['name' => 'Food & Dining', 'type' => 'expense', 'icon' => '🍔', 'color' => '#ef4444'],
            ['name' => 'Transportation', 'type' => 'expense', 'icon' => '🚗', 'color' => '#f59e0b'],
            ['name' => 'Shopping', 'type' => 'expense', 'icon' => '🛍️', 'color' => '#ec4899'],
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => '🎬', 'color' => '#a855f7'],
            ['name' => 'Bills & Utilities', 'type' => 'expense', 'icon' => '📄', 'color' => '#6366f1'],
            ['name' => 'Healthcare', 'type' => 'expense', 'icon' => '⚕️', 'color' => '#06b6d4'],
            ['name' => 'Education', 'type' => 'expense', 'icon' => '📚', 'color' => '#0ea5e9'],
            ['name' => 'Housing', 'type' => 'expense', 'icon' => '🏠', 'color' => '#64748b'],
            ['name' => 'Other Expenses', 'type' => 'expense', 'icon' => '📦', 'color' => '#94a3b8'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

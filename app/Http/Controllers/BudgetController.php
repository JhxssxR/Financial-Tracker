<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all budgets for the current user with category relationship
        $budgets = Budget::where('user_id', $user->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(3);
        
        // Calculate spent for each budget from transactions
        foreach ($budgets as $budget) {
            // Calculate actual spent from transactions
            $budget->spent = Transaction::where('user_id', $user->id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$budget->start_date, $budget->end_date])
                ->sum('amount');
            
            // Calculate percentage for progress bar
            $budget->percentage = $budget->amount > 0 
                ? ($budget->spent / $budget->amount) * 100 
                : 0;
            
            // Calculate remaining amount
            $budget->remaining = $budget->amount - $budget->spent;
            
            // Calculate days remaining in budget period
            $endDate = \Carbon\Carbon::parse($budget->end_date);
            $daysRemaining = now()->diffInDays($endDate, false);
            $budget->days_remaining = $daysRemaining;
            $budget->is_expired = $daysRemaining < 0;
        }

        // Create notifications for budgets that are in warning/critical/over states.
        // Use firstOrCreate to avoid duplicate notifications for the same budget/title.
        foreach ($budgets as $budget) {
            $percentage = $budget->percentage;
            $categoryName = $budget->category->name ?? $budget->name;

            if ($percentage > 75) {
                if ($percentage > 100) {
                    $title = "Budget Over: {$categoryName}";
                    $message = "Your {$categoryName} budget is over by " . number_format($percentage, 0) . "% (spent: " . number_format($budget->spent, 2) . ").";
                } elseif ($percentage > 90) {
                    $title = "Budget Critical: {$categoryName}";
                    $message = "Your {$categoryName} budget is critical at " . number_format($percentage, 0) . "% used.";
                } else {
                    $title = "Budget Warning: {$categoryName}";
                    $message = "Your {$categoryName} budget is at " . number_format($percentage, 0) . "% used.";
                }

                // Avoid creating exact duplicate notifications with same title for the user
                Notification::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => 'budget',
                        'title' => $title,
                    ],
                    [
                        'message' => $message,
                        'is_read' => false,
                    ]
                );
            }
        }
        
        // Calculate summary totals
        $totalBudgeted = $budgets->sum('amount');
        $totalSpent = $budgets->sum('spent');
        $totalRemaining = $totalBudgeted - $totalSpent;
        $spentPercentage = $totalBudgeted > 0 ? ($totalSpent / $totalBudgeted) * 100 : 0;
        
        // Get categories for dropdown (expense categories only)
        $categories = Category::where('type', 'expense')->get();
        
        return view('budgets', compact(
            'budgets',
            'totalBudgeted',
            'totalSpent',
            'totalRemaining',
            'spentPercentage',
            'categories'
        ));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:monthly,weekly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['spent'] = 0;
        
        $budget = Budget::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Budget created successfully!',
            'budget' => $budget
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $budget = Budget::where('user_id', Auth::id())->findOrFail($id);
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:monthly,weekly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $budget->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Budget updated successfully!',
            'budget' => $budget
        ]);
    }
    
    public function destroy($id)
    {
        $budget = Budget::where('user_id', Auth::id())->findOrFail($id);
        $budget->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Budget deleted successfully!'
        ]);
    }
}

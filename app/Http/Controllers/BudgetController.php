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

        // NOTE: Notification creation is handled at the moment budgets are created/updated
        // or when transactions are added. Avoid creating notifications here on view render
        // to prevent duplicates when the page is visited.
        
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

        // After creating a budget, compute actual spent from existing transactions
        $spent = Transaction::where('user_id', Auth::id())
            ->where('category_id', $budget->category_id)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$budget->start_date, $budget->end_date])
            ->sum('amount');

        $budget->spent = $spent;
        $budget->save();

        // Determine if we need to create a notification (retroactive)
        $newPercent = $budget->amount > 0 ? ($budget->spent / $budget->amount) * 100 : 0;
        $notificationsPayload = null;

        if ($newPercent > 75) {
            $categoryName = $budget->category->name ?? $budget->name;

            if ($newPercent > 100) {
                $title = "Budget Over: {$categoryName}";
                $message = "Your {$categoryName} budget is over by " . number_format($newPercent - 100, 0) . "% (spent: " . number_format($budget->spent, 2) . ").";
            } elseif ($newPercent > 90) {
                $title = "Budget Critical: {$categoryName}";
                $message = "Your {$categoryName} budget is critical at " . number_format($newPercent, 0) . "% used.";
            } else {
                $title = "Budget Warning: {$categoryName}";
                $message = "Your {$categoryName} budget is at " . number_format($newPercent, 0) . "% used.";
            }

            $notif = Notification::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'title' => $title,
                ],
                [
                    'type' => 'budget',
                    'message' => $message,
                    'is_read' => false,
                ]
            );

            $newCount = Notification::where('user_id', Auth::id())->where('is_read', false)->count();
            $latest = Notification::where('user_id', Auth::id())->where('is_read', false)->latest('created_at')->first();

            $notificationsPayload = [
                'newCount' => $newCount,
                'latestUnread' => $latest ? [
                    'id' => $latest->id,
                    'title' => $latest->title,
                    'message' => $latest->message,
                    'created_at' => $latest->created_at->toDateTimeString(),
                ] : null,
            ];
        }

        $response = [
            'success' => true,
            'message' => 'Budget created successfully!',
            'budget' => $budget,
        ];

        if ($notificationsPayload) {
            $response['notifications'] = $notificationsPayload;
        }

        return response()->json($response);
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

        // Recalculate spent after update (in case dates or category changed)
        $spent = Transaction::where('user_id', Auth::id())
            ->where('category_id', $budget->category_id)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$budget->start_date, $budget->end_date])
            ->sum('amount');

        $oldPercent = $budget->amount > 0 ? (($budget->spent ?? 0) / $budget->amount) * 100 : 0;
        $budget->spent = $spent;
        $budget->save();

        $newPercent = $budget->amount > 0 ? ($budget->spent / $budget->amount) * 100 : 0;
        $notificationsPayload = null;

        // Create notification only if crossing thresholds (retroactive on update)
        if (($oldPercent <= 75 && $newPercent > 75) || ($oldPercent <= 90 && $newPercent > 90) || ($oldPercent <= 100 && $newPercent > 100)) {
            $categoryName = $budget->category->name ?? $budget->name;

            if ($newPercent > 100) {
                $title = "Budget Over: {$categoryName}";
                $message = "Your {$categoryName} budget is over by " . number_format($newPercent - 100, 0) . "% (spent: " . number_format($budget->spent, 2) . ").";
            } elseif ($newPercent > 90) {
                $title = "Budget Critical: {$categoryName}";
                $message = "Your {$categoryName} budget is critical at " . number_format($newPercent, 0) . "% used.";
            } else {
                $title = "Budget Warning: {$categoryName}";
                $message = "Your {$categoryName} budget is at " . number_format($newPercent, 0) . "% used.";
            }

            $notif = Notification::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'title' => $title,
                ],
                [
                    'type' => 'budget',
                    'message' => $message,
                    'is_read' => false,
                ]
            );

            $newCount = Notification::where('user_id', Auth::id())->where('is_read', false)->count();
            $latest = Notification::where('user_id', Auth::id())->where('is_read', false)->latest('created_at')->first();

            $notificationsPayload = [
                'newCount' => $newCount,
                'latestUnread' => $latest ? [
                    'id' => $latest->id,
                    'title' => $latest->title,
                    'message' => $latest->message,
                    'created_at' => $latest->created_at->toDateTimeString(),
                ] : null,
            ];
        }

        $response = [
            'success' => true,
            'message' => 'Budget updated successfully!',
            'budget' => $budget,
        ];

        if ($notificationsPayload) {
            $response['notifications'] = $notificationsPayload;
        }

        return response()->json($response);
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

<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class ChatbotController extends BaseController
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Send a message to the chatbot
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $user = Auth::user();
        
        // Get user's financial context
        $context = $this->getUserFinancialContext($user);

        $response = $this->chatbotService->sendMessage(
            $request->message,
            $context
        );

        return response()->json($response);
    }

    /**
     * Get user's financial context for chatbot
     */
    private function getUserFinancialContext($user): array
    {
        $currentMonth = now();
        
        try {
            $totalIncome = $user->transactions()
                ->where('type', 'income')
                ->whereMonth('transaction_date', $currentMonth->month)
                ->whereYear('transaction_date', $currentMonth->year)
                ->sum('amount') ?? 0;

            $totalExpenses = $user->transactions()
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $currentMonth->month)
                ->whereYear('transaction_date', $currentMonth->year)
                ->sum('amount') ?? 0;

            $totalBudget = $user->budgets()->sum('amount') ?? 0;
            $totalSavings = $user->savings()->sum('current_amount') ?? 0;

            $topCategories = $user->transactions()
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $currentMonth->month)
                ->whereYear('transaction_date', $currentMonth->year)
                ->with('category')
                ->get()
                ->groupBy('category.name')
                ->map(fn($group) => $group->sum('amount'))
                ->sortDesc()
                ->take(3)
                ->keys()
                ->toArray();
        } catch (\Exception $e) {
            // If any database query fails, return basic context
            $totalIncome = 0;
            $totalExpenses = 0;
            $totalBudget = 0;
            $totalSavings = 0;
            $topCategories = [];
        }

        return [
            'currency' => $user->currency_symbol ?? '₱',
            'total_income' => number_format($totalIncome, 2),
            'total_expenses' => number_format($totalExpenses, 2),
            'net_savings' => number_format($totalIncome - $totalExpenses, 2),
            'budget' => number_format($totalBudget, 2),
            'savings' => number_format($totalSavings, 2),
            'top_categories' => $topCategories,
        ];
    }
}

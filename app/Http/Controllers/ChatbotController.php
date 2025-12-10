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
            // Get ALL-TIME totals
            $allTimeIncome = $user->transactions()
                ->where('type', 'income')
                ->sum('amount') ?? 0;

            $allTimeExpenses = $user->transactions()
                ->where('type', 'expense')
                ->sum('amount') ?? 0;

            // Get current month totals
            $monthIncome = $user->transactions()
                ->where('type', 'income')
                ->whereMonth('transaction_date', $currentMonth->month)
                ->whereYear('transaction_date', $currentMonth->year)
                ->sum('amount') ?? 0;

            $monthExpenses = $user->transactions()
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

            // Get user currency
            $currencySymbol = $user->currency_symbol ?? '₱';
            $currencyCode = $user->currency_code ?? 'PHP';
        } catch (\Exception $e) {
            // If any database query fails, return basic context
            $allTimeIncome = 0;
            $allTimeExpenses = 0;
            $monthIncome = 0;
            $monthExpenses = 0;
            $totalBudget = 0;
            $totalSavings = 0;
            $topCategories = [];
            $currencySymbol = '₱';
            $currencyCode = 'PHP';
        }

        return [
            'currency_symbol' => $currencySymbol,
            'currency_code' => $currencyCode,
            'all_time_income' => $allTimeIncome,
            'all_time_expenses' => $allTimeExpenses,
            'month_income' => $monthIncome,
            'month_expenses' => $monthExpenses,
            'net_savings' => $allTimeIncome - $allTimeExpenses,
            'month_net' => $monthIncome - $monthExpenses,
            'budget' => $totalBudget,
            'savings' => $totalSavings,
            'top_categories' => $topCategories,
        ];
    }
}

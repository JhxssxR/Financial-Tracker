<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Saving;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get current month transactions
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $transactions = Transaction::where('user_id', $user->id)
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->get();
        
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $totalSavings = Saving::where('user_id', $user->id)->sum('current_amount');
        
        // Calculate percentages
        $savingsPercentage = $totalIncome > 0 ? ($totalSavings / $totalIncome) * 100 : 0;
        $expensePercentage = $totalIncome > 0 ? ($totalExpenses / $totalIncome) * 100 : 0;
        
        // Get recent transactions (last 10)
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();
        
        // Get monthly data for the last 6 months for chart
        $monthlyData = $this->getMonthlyData($user->id);
        
        return view('dashboard', compact(
            'totalIncome',
            'totalExpenses',
            'totalSavings',
            'savingsPercentage',
            'expensePercentage',
            'recentTransactions',
            'monthlyData'
        ));
    }
    
    private function getMonthlyData($userId)
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            
            $monthIncome = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
            
            $monthExpense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
            
            $incomeData[] = (float)$monthIncome;
            $expenseData[] = (float)$monthExpense;
        }
        
        return [
            'months' => $months,
            'income' => $incomeData,
            'expenses' => $expenseData
        ];
    }
}

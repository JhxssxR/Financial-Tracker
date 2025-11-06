<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Saving;
use App\Models\SavingsTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all 2025 transactions (year-to-date)
        $currentYear = now()->year;
        
        $transactions = Transaction::where('user_id', $user->id)
            ->whereYear('transaction_date', $currentYear)
            ->get();
        
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        
        // Calculate total savings from SavingsTransaction (deposits - withdrawals)
        $totalDeposits = SavingsTransaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->sum('amount');
        $totalWithdrawals = SavingsTransaction::where('user_id', $user->id)
            ->where('type', 'withdraw')
            ->sum('amount');
        $totalSavings = $totalDeposits - $totalWithdrawals;
        
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
        
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        // Get all months from January to current month of 2025
        for ($month = 1; $month <= $currentMonth; $month++) {
            $date = now()->setMonth($month)->setYear($currentYear);
            $months[] = $date->format('M');
            
            $monthIncome = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $currentYear)
                ->sum('amount');
            
            $monthExpense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $currentYear)
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

<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Saving;
use App\Models\SavingsTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get ALL transactions (not filtered by year)
        $transactions = Transaction::where('user_id', $user->id)->get();
        
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

        // Notifications: unread count and latest unread notification
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $latestUnread = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->first();
        
        return view('dashboard', compact(
            'totalIncome',
            'totalExpenses',
            'totalSavings',
            'savingsPercentage',
            'expensePercentage',
            'recentTransactions',
            'monthlyData'
        ))->with([
            'unreadCount' => $unreadCount,
            'latestUnread' => $latestUnread,
        ]);
    }
    
    private function getMonthlyData($userId)
    {
        // Get the earliest transaction date
        $earliestTransaction = Transaction::where('user_id', $userId)
            ->orderBy('transaction_date', 'asc')
            ->first();
        
        if (!$earliestTransaction) {
            return [
                'months' => [],
                'income' => [],
                'expenses' => []
            ];
        }
        
        $startDate = $earliestTransaction->transaction_date->copy()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $months = [];
        $incomeData = [];
        $expenseData = [];
        
        // Loop through all months from earliest transaction to now
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            // Format: "Jan 2024", "Feb 2024", etc.
            $months[] = $currentDate->format('M Y');
            
            $monthIncome = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('transaction_date', $currentDate->month)
                ->whereYear('transaction_date', $currentDate->year)
                ->sum('amount');
            
            $monthExpense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $currentDate->month)
                ->whereYear('transaction_date', $currentDate->year)
                ->sum('amount');
            
            $incomeData[] = (float)$monthIncome;
            $expenseData[] = (float)$monthExpense;
            
            $currentDate->addMonth();
        }
        
        return [
            'months' => $months,
            'income' => $incomeData,
            'expenses' => $expenseData
        ];
    }
}

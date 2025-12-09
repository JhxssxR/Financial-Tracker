<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Saving;
use App\Models\SavingsTransaction;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get fresh user data with currency
        $user = Auth::user()->fresh();
        
        // Calculate totals (all time)
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
        
        $totalTransactions = $transactions->count();
        
        // Get monthly data for the last 6 months for chart
        $monthlyData = $this->getMonthlyData($user->id);
        
        // Get expense category breakdown
        $expenseCategoryData = $this->getExpenseCategoryData($user->id);

        // Return view with explicit no-cache headers
        return response()
            ->view('reports', [
                'currencySymbol' => $user->currency_symbol ?? '₱',
                'currencyCode' => $user->currency_code ?? 'PHP',
                'totalIncome' => $totalIncome,
                'totalExpenses' => $totalExpenses,
                'totalSavings' => $totalSavings,
                'totalTransactions' => $totalTransactions,
                'monthlyData' => $monthlyData,
                'expenseCategoryData' => $expenseCategoryData,
                'cb' => now()->timestamp,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
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
    
    private function getExpenseCategoryData($userId)
    {
        $expenseTransactions = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->with('category')
            ->get();
        
        $categoryTotals = [];
        $categoryColors = [];
        
        foreach ($expenseTransactions as $transaction) {
            $categoryName = $transaction->category->name ?? 'Uncategorized';
            $categoryColor = $transaction->category->color ?? '#94a3b8';
            
            if (!isset($categoryTotals[$categoryName])) {
                $categoryTotals[$categoryName] = 0;
                $categoryColors[$categoryName] = $categoryColor;
            }
            
            $categoryTotals[$categoryName] += $transaction->amount;
        }
        
        // Sort by amount descending
        arsort($categoryTotals);
        
        return [
            'labels' => array_keys($categoryTotals),
            'data' => array_values($categoryTotals),
            'colors' => array_values($categoryColors),
        ];
    }
    
    public function exportPdf()
    {
        $user = Auth::user()->fresh();
        
        // Calculate totals
        $transactions = Transaction::where('user_id', $user->id)->get();
        
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        
        // Calculate total savings
        $totalDeposits = SavingsTransaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->sum('amount');
        $totalWithdrawals = SavingsTransaction::where('user_id', $user->id)
            ->where('type', 'withdraw')
            ->sum('amount');
        $totalSavings = $totalDeposits - $totalWithdrawals;
        
        $totalTransactions = $transactions->count();
        
        // Get monthly data
        $monthlyData = $this->getMonthlyData($user->id);
        
        // Get expense category breakdown
        $expenseCategoryData = $this->getExpenseCategoryData($user->id);
        
        // Get recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();
        
        $data = [
            'user' => $user,
            'currencySymbol' => $user->currency_symbol ?? '₱',
            'currencyCode' => $user->currency_code ?? 'PHP',
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'totalSavings' => $totalSavings,
            'totalTransactions' => $totalTransactions,
            'monthlyData' => $monthlyData,
            'expenseCategoryData' => $expenseCategoryData,
            'recentTransactions' => $recentTransactions,
            'generatedDate' => now()->format('F d, Y'),
        ];
        
        $pdf = PDF::loadView('reports-pdf', $data)
            ->setPaper('a5', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);
        
        return $pdf->download('financial-report-' . now()->format('Y-m-d') . '.pdf');
    }
}

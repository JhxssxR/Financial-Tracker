<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Saving;

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
        $totalSavings = Saving::where('user_id', $user->id)->sum('current_amount');
        $totalTransactions = $transactions->count();
        
        // Get monthly data for the last 6 months for chart
        $monthlyData = $this->getMonthlyData($user->id);

        // Return view with explicit no-cache headers
        return response()
            ->view('reports', [
                'currencySymbol' => $user->currency_symbol ?? '€',
                'currencyCode' => $user->currency_code ?? 'EUR',
                'totalIncome' => $totalIncome,
                'totalExpenses' => $totalExpenses,
                'totalSavings' => $totalSavings,
                'totalTransactions' => $totalTransactions,
                'monthlyData' => $monthlyData,
                'cb' => now()->timestamp,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
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

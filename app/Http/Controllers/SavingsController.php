<?php

namespace App\Http\Controllers;

use App\Models\SavingsTransaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Paginate transactions (10 per page)
        $transactions = SavingsTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Calculate totals
        $totalDeposits = SavingsTransaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->sum('amount');
        
        $totalWithdrawals = SavingsTransaction::where('user_id', $user->id)
            ->where('type', 'withdraw')
            ->sum('amount');
        
        $totalBalance = $totalDeposits - $totalWithdrawals;
        
        return view('savings', compact('transactions', 'totalBalance', 'totalDeposits', 'totalWithdrawals'));
    }
    
    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['type'] = 'deposit';
        
        $transaction = SavingsTransaction::create($validated);
        
        // Create notification
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'savings',
            'title' => 'Deposit Successful',
            'message' => 'You deposited ' . format_currency($validated['amount']) . ' to your savings account.',
            'is_read' => false,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Deposit successful!',
        ]);
    }
    
    public function withdraw(Request $request)
    {
        $user = Auth::user();
        
        // Calculate current balance
        $totalDeposits = SavingsTransaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->sum('amount');
        
        $totalWithdrawals = SavingsTransaction::where('user_id', $user->id)
            ->where('type', 'withdraw')
            ->sum('amount');
        
        $currentBalance = $totalDeposits - $totalWithdrawals;
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $currentBalance,
            'description' => 'nullable|string|max:500',
        ]);
        
        if ($validated['amount'] > $currentBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance!',
            ], 400);
        }
        
        $validated['user_id'] = Auth::id();
        $validated['type'] = 'withdraw';
        
        $transaction = SavingsTransaction::create($validated);
        
        // Create notification
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'savings',
            'title' => 'Withdrawal Successful',
            'message' => 'You withdrew ' . format_currency($validated['amount']) . ' from your savings account.',
            'is_read' => false,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Withdrawal successful!',
        ]);
    }
}

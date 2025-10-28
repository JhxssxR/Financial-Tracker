<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all transactions for the current user
        $transactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        // Calculate totals
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $totalTransactions = $transactions->count();
        
        // Get categories for the dropdown
        $categories = Category::all()->groupBy('type');
        
        return view('transactions', compact('transactions', 'totalIncome', 'totalExpenses', 'totalTransactions', 'categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'transaction_date' => 'required|date',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        Transaction::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Transaction added successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $transaction->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully!'
        ]);
    }
}

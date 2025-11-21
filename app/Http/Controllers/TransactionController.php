<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Budget;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use PDF;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all transactions for the current user with pagination
        $transactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->paginate(5);
        
        // Calculate totals from all transactions (not just paginated)
        $allTransactions = Transaction::where('user_id', $user->id)->get();
        $totalIncome = $allTransactions->where('type', 'income')->sum('amount');
        $totalExpenses = $allTransactions->where('type', 'expense')->sum('amount');
        $totalTransactions = $allTransactions->count();
        
        // Get categories for the dropdown (global + user-specific) and remove duplicate names per type
        $categories = Category::forUser($user->id)->get()->groupBy('type')->map(function ($group) {
            return $group->unique('name')->values();
        });
        
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
        
        $transaction = Transaction::create($validated);

        // Compute year-to-date totals so UI can be updated live
        $currentYear = now()->year;

        $totalIncome = Transaction::where('user_id', Auth::id())
            ->where('type', 'income')
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $totalExpenses = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        // Prepare optional notifications payload
        $notificationsPayload = null;

        // If this is an expense, check for budgets on the same category and update spent
        if ($transaction->type === 'expense') {
            $txDate = $transaction->transaction_date;
            $budgets = Budget::where('user_id', Auth::id())
                ->where('category_id', $transaction->category_id)
                ->where(function ($q) use ($txDate) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', $txDate);
                })
                ->where(function ($q) use ($txDate) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', $txDate);
                })
                ->get();

            foreach ($budgets as $budget) {
                // Avoid division by zero
                if ((float) $budget->amount <= 0) {
                    continue;
                }

                $oldSpent = (float) $budget->spent;
                $newSpent = $oldSpent + (float) $transaction->amount;

                $oldPercent = $budget->amount > 0 ? ($oldSpent / $budget->amount) * 100 : 0;
                $newPercent = $budget->amount > 0 ? ($newSpent / $budget->amount) * 100 : 0;

                // Update spent on budget
                $budget->spent = $newSpent;
                $budget->save();

                // Check threshold crossings and create a notification if needed
                $createNotification = null;

                if ($oldPercent <= 75 && $newPercent > 75 && $newPercent <= 90) {
                    $createNotification = [
                        'type' => 'budget',
                        'title' => "Budget Warning: {$budget->name}",
                        'message' => "Your {$budget->name} budget is at " . number_format($newPercent, 0) . "% used.",
                    ];
                } elseif ($oldPercent <= 90 && $newPercent > 90 && $newPercent <= 100) {
                    $createNotification = [
                        'type' => 'budget',
                        'title' => "Budget Critical: {$budget->name}",
                        'message' => "Your {$budget->name} budget is critical at " . number_format($newPercent, 0) . "% used.",
                    ];
                } elseif ($oldPercent <= 100 && $newPercent > 100) {
                    $createNotification = [
                        'type' => 'budget',
                        'title' => "Budget Over: {$budget->name}",
                        'message' => "Your {$budget->name} budget is now over by " . number_format($newPercent - 100, 0) . "%.",
                    ];
                }

                if ($createNotification) {
                    // Deduplicate by user and title
                    $notif = Notification::firstOrCreate(
                        [
                            'user_id' => Auth::id(),
                            'title' => $createNotification['title'],
                        ],
                        [
                            'type' => $createNotification['type'],
                            'message' => $createNotification['message'],
                            'is_read' => false,
                        ]
                    );

                    // Build the notifications payload (authoritative)
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
            }
        }

        $response = [
            'success' => true,
            'message' => 'Transaction added successfully!',
            'transaction' => $transaction,
            'totals' => [
                'totalIncome' => format_currency($totalIncome),
                'totalExpenses' => format_currency($totalExpenses),
                'rawIncome' => (float) $totalIncome,
                'rawExpenses' => (float) $totalExpenses,
            ]
        ];

        if ($notificationsPayload) {
            $response['notifications'] = $notificationsPayload;
        }

        return response()->json($response);
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
    
    public function exportPdf()
    {
        $user = Auth::user()->fresh();
        
        // Get all transactions for the current user
        $transactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        // Calculate totals
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $totalTransactions = $transactions->count();
        
        // Group transactions by type
        $incomeTransactions = $transactions->where('type', 'income')->values();
        $expenseTransactions = $transactions->where('type', 'expense')->values();
        
        $data = [
            'user' => $user,
            'currencySymbol' => $user->currency_symbol ?? '€',
            'currencyCode' => $user->currency_code ?? 'EUR',
            'transactions' => $transactions,
            'incomeTransactions' => $incomeTransactions,
            'expenseTransactions' => $expenseTransactions,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'totalTransactions' => $totalTransactions,
            'generatedDate' => now()->format('F d, Y'),
        ];
        
        $pdf = PDF::loadView('transactions-pdf', $data)
            ->setPaper('a5', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);
        
        return $pdf->download('transactions-' . now()->format('Y-m-d') . '.pdf');
    }
}
